<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    private function getDateRange($request)
    {
        $period = $request->input('period', 'this_month');
        
        switch ($period) {
            case 'today':
                $start = Carbon::today();
                $end = Carbon::today()->endOfDay();
                break;
            case 'yesterday':
                $start = Carbon::yesterday();
                $end = Carbon::yesterday()->endOfDay();
                break;
            case 'this_week':
                $start = Carbon::now()->startOfWeek();
                $end = Carbon::now()->endOfWeek();
                break;
            case 'last_week':
                $start = Carbon::now()->subWeek()->startOfWeek();
                $end = Carbon::now()->subWeek()->endOfWeek();
                break;
            case 'this_month':
                $start = Carbon::now()->startOfMonth();
                $end = Carbon::now()->endOfMonth();
                break;
            case 'last_month':
                $start = Carbon::now()->subMonth()->startOfMonth();
                $end = Carbon::now()->endOfMonth();
                break;
            case 'this_year':
                $start = Carbon::now()->startOfYear();
                $end = Carbon::now()->endOfYear();
                break;
            case 'custom':
                $start = $request->has('start_date') ? Carbon::parse($request->start_date)->startOfDay() : Carbon::now()->startOfMonth();
                $end = $request->has('end_date') ? Carbon::parse($request->end_date)->endOfDay() : Carbon::now()->endOfMonth();
                break;
            default:
                $start = Carbon::now()->startOfMonth();
                $end = Carbon::now()->endOfMonth();
        }
        
        return [$start, $end];
    }

    public function index(Request $request)
    {
        // General Dashboard / Summary
        [$start, $end] = $this->getDateRange($request);
        
        $revenue = Order::where('status', 'completed')
            ->whereBetween('created_at', [$start, $end])
            ->sum('total');
            
        $ordersCount = Order::where('status', 'completed')
            ->whereBetween('created_at', [$start, $end])
            ->count();
            
        // Calculate Growth (vs Previous Period)
        $diffInDays = $start->diffInDays($end) + 1;
        $prevStart = $start->copy()->subDays($diffInDays);
        $prevEnd = $end->copy()->subDays($diffInDays);
        
        $prevRevenue = Order::where('status', 'completed')
            ->whereBetween('created_at', [$prevStart, $prevEnd])
            ->sum('total');
            
        if ($prevRevenue > 0) {
            $revenueGrowth = round((($revenue - $prevRevenue) / $prevRevenue) * 100, 1);
        } else {
            $revenueGrowth = $revenue > 0 ? 100 : 0;
        }

        // Calculate Estimated Profit (Revenue - Cost)
        $profit = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('product_variants', 'order_items.product_variant_id', '=', 'product_variants.id')
            ->where('orders.status', 'completed')
            ->whereBetween('orders.created_at', [$start, $end])
            ->select(DB::raw('SUM(order_items.quantity * (order_items.unit_price - COALESCE(product_variants.cost, 0))) as profit'))
            ->value('profit');
            
        $profit = $profit ?? 0;

        // Chart Data based on period
        $period = $request->input('period', 'this_month');
        $chartLabels = [];
        $chartData = [];
        
        if ($period == 'today' || $period == 'yesterday') {
            // Group by Hour
            $hourlyData = Order::where('status', 'completed')
                ->whereBetween('created_at', [$start, $end])
                ->select(DB::raw('HOUR(created_at) as hour'), DB::raw('SUM(total) as total'))
                ->groupBy('hour')
                ->pluck('total', 'hour')->toArray();
                
            for($i=0; $i<24; $i++) {
                $chartLabels[] = str_pad($i, 2, '0', STR_PAD_LEFT) . ':00';
                $chartData[] = $hourlyData[$i] ?? 0;
            }
        } elseif ($period == 'this_year') {
            // Group by Month
            $monthlyData = Order::where('status', 'completed')
                ->whereBetween('created_at', [$start, $end])
                ->select(DB::raw('MONTH(created_at) as month'), DB::raw('SUM(total) as total'))
                ->groupBy('month')
                ->pluck('total', 'month')->toArray();
                
            for($i=1; $i<=12; $i++) {
                $chartLabels[] = 'Tháng ' . $i;
                $chartData[] = $monthlyData[$i] ?? 0;
            }
        } else {
            // Group by Day (default for week/month/custom)
            $dailyData = Order::where('status', 'completed')
                ->whereBetween('created_at', [$start, $end])
                ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total) as total'))
                ->groupBy('date')
                ->pluck('total', 'date')->toArray();
            
            $current = $start->copy();
            while($current <= $end) {
                $dateStr = $current->format('Y-m-d');
                $chartLabels[] = $current->format('d/m');
                $chartData[] = $dailyData[$dateStr] ?? 0;
                $current->addDay();
            }
        }
        
        // Top selling products (limit 5)
        $topProducts = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('product_variants', 'order_items.product_variant_id', '=', 'product_variants.id')
            ->join('products', 'product_variants.product_id', '=', 'products.id')
            ->where('orders.status', 'completed')
            ->whereBetween('orders.created_at', [$start, $end])
            ->select('products.name', DB::raw('SUM(order_items.quantity) as total_qty'), DB::raw('SUM(order_items.unit_price * order_items.quantity) as total_revenue'))
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_revenue')
            ->limit(5)
            ->get();

        if ($request->ajax()) {
            return response()->json([
                'revenue' => number_format($revenue, 0, ',', '.') . ' đ',
                'ordersCount' => $ordersCount,
                'revenueGrowth' => $revenueGrowth,
                'profit' => number_format($profit, 0, ',', '.') . ' đ',
                'margin' => $revenue > 0 ? number_format(($profit / $revenue) * 100, 1) . '%' : '0%',
                'avgOrderValue' => $ordersCount > 0 ? number_format($revenue / $ordersCount, 0, ',', '.') . ' đ' : '0 đ',
                'chartLabels' => $chartLabels,
                'chartData' => $chartData,
                'topProducts' => $topProducts,
                'periodLabel' => $start->format('d/m/Y') . ' - ' . $end->format('d/m/Y')
            ]);
        }

        // Pass legacy variable names for view compatibility (or update view)
        // View expects $weeklyRevenueData/Labels. Let's map chartData to it or update view.
        // Let's pass chartData/Labels and update view to use them.
        return view('admin.reports.index', compact('revenue', 'ordersCount', 'revenueGrowth', 'profit', 'chartData', 'chartLabels', 'topProducts'));
    }

    public function revenue(Request $request)
    {
        // Detailed Revenue Report
        // Daily revenue for the current month
        [$start, $end] = $this->getDateRange($request);
        
        $dailyRevenue = Order::where('status', 'completed')
            ->whereBetween('created_at', [$start, $end])
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
            
        $labels = $dailyRevenue->pluck('date');
        $data = $dailyRevenue->pluck('total');

        if ($request->ajax()) {
            return response()->json([
                'dailyRevenue' => $dailyRevenue,
                'labels' => $labels,
                'data' => $data,
                'periodLabel' => $start->format('d/m/Y') . ' - ' . $end->format('d/m/Y')
            ]);
        }

        return view('admin.reports.revenue', compact('dailyRevenue', 'labels', 'data'));
    }

    public function products(Request $request)
    {
        [$start, $end] = $this->getDateRange($request);

        // Best Selling Products Report
        $topProducts = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('product_variants', 'order_items.product_variant_id', '=', 'product_variants.id')
            ->join('products', 'product_variants.product_id', '=', 'products.id')
            ->where('orders.status', 'completed')
            ->whereBetween('orders.created_at', [$start, $end])
            ->select('products.name', 'product_variants.sku', 'product_variants.price', DB::raw('SUM(order_items.quantity) as total_qty'), DB::raw('SUM(order_items.unit_price * order_items.quantity) as total_revenue'))
            ->groupBy('products.id', 'products.name', 'product_variants.sku', 'product_variants.price')
            ->orderByDesc('total_qty')
            ->limit(20)
            ->get();

        if ($request->ajax()) {
            return response()->json([
                'topProducts' => $topProducts,
                'periodLabel' => $start->format('d/m/Y') . ' - ' . $end->format('d/m/Y')
            ]);
        }

        return view('admin.reports.products', compact('topProducts'));
    }

    public function customers(Request $request)
    {
        [$start, $end] = $this->getDateRange($request);

        // Customer Report
        // New customers this month
        $newCustomers = User::whereBetween('created_at', [$start, $end])->count();
        
        // Returning Customers (Users with > 1 completed orders)
        $returningCustomers = DB::table('orders')
            ->where('status', 'completed')
            ->whereBetween('created_at', [$start, $end])
            ->select('user_id', DB::raw('count(*) as count'))
            ->groupBy('user_id')
            ->having('count', '>', 1)
            ->get()
            ->count(); // Count the number of users
            
        // CLV (Total Revenue / Total Users who ordered)
        // Get total revenue
        $totalRevenue = Order::where('status', 'completed')
            ->whereBetween('created_at', [$start, $end])
            ->sum('total');
        // Get unique users who ordered
        $usersWhoOrdered = Order::where('status', 'completed')
            ->whereBetween('created_at', [$start, $end])
            ->distinct('user_id')
            ->count('user_id');
        
        $clv = $usersWhoOrdered > 0 ? $totalRevenue / $usersWhoOrdered : 0;
        
        // Customer Segmentation Data
        // 1. New (Created this month)
        $segNew = $newCustomers;
        
        // 2. Loyal ( > 2 orders)
        $segLoyal = DB::table('orders')
            ->where('status', 'completed')
            ->whereBetween('created_at', [$start, $end])
            ->select('user_id', DB::raw('count(*) as count'))
            ->groupBy('user_id')
            ->having('count', '>', 2)
            ->get()
            ->count();
            
        // 3. VIP (Total spent > 5M)
        $segVip = DB::table('orders')
            ->where('status', 'completed')
            ->whereBetween('created_at', [$start, $end])
            ->select('user_id', DB::raw('SUM(total) as total'))
            ->groupBy('user_id')
            ->having('total', '>', 5000000)
            ->get()
            ->count();
            
        // 4. Inactive (Registered > 3 months ago, no order in last 3 months)
        // This is complex, let's just use "Potential" (Registered but no orders yet)
        $segPotential = User::whereBetween('created_at', [$start, $end])->doesntHave('orders')->count();
        
        $segmentationLabels = ['Mới', 'Thân thiết (>2 đơn)', 'VIP (>5tr)', 'Tiềm năng (Chưa mua)'];
        $segmentationData = [$segNew, $segLoyal, $segVip, $segPotential];

        // Top spending customers
        $topCustomers = DB::table('orders')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->where('orders.status', 'completed')
            ->whereBetween('orders.created_at', [$start, $end])
            ->select('users.name', 'users.email', DB::raw('COUNT(orders.id) as order_count'), DB::raw('SUM(orders.total) as total_spent'), DB::raw('MAX(orders.created_at) as last_order_date'))
            ->groupBy('users.id', 'users.name', 'users.email')
            ->orderByDesc('total_spent')
            ->limit(10)
            ->get();

        if ($request->ajax()) {
            return response()->json([
                'newCustomers' => $newCustomers,
                'returningCustomers' => $returningCustomers,
                'clv' => $clv,
                'topCustomers' => $topCustomers,
                'segmentationLabels' => $segmentationLabels,
                'segmentationData' => $segmentationData,
                'periodLabel' => $start->format('d/m/Y') . ' - ' . $end->format('d/m/Y')
            ]);
        }

        return view('admin.reports.customers', compact('newCustomers', 'returningCustomers', 'clv', 'topCustomers', 'segmentationLabels', 'segmentationData'));
    }
}
