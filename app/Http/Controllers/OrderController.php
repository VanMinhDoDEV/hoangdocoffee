<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use App\Models\Warehouse;
use App\Models\WarehouseInventory;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $userId = auth()->id();
        if (!$userId) {
            return redirect()->route('login');
        }
        $orders = Order::with(['user'])->where('user_id', $userId)->latest()->paginate(10);
        $uiOrders = $orders->map(function($o){
            $name = optional($o->user)->name ?? ('User #'.$o->user_id);
            $parts = preg_split('/\s+/', trim($name));
            $initials = '';
            foreach ($parts as $i => $p) {
                if ($i >= 2) break;
                $initials .= strtoupper(substr($p, 0, 1));
            }
            $payment = ($o->status === 'completed') ? 'Paid' : (($o->status === 'failed') ? 'Failed' : (($o->status === 'cancelled') ? 'Cancelled' : 'Pending'));
            $delivery = $o->status === 'completed' ? 'Delivered' : ($o->status === 'new' ? 'Dispatched' : ucfirst((string)$o->status));
            return [
                'id' => (string)$o->id,
                'date' => (optional($o->placed_at)->format('M d, Y, H:i') ?: optional($o->created_at)->format('M d, Y, H:i')),
                'name' => $name,
                'email' => optional($o->user)->email ?? '',
                'payment' => $payment,
                'status' => $delivery,
                'method' => 'mastercard',
                'methodLast4' => '0000',
                'avatar' => null,
                'initials' => $initials !== '' ? $initials : 'U',
                'color' => '#3b82f6',
            ];
        })->values()->all();
        $stats = [
            'pending' => Order::where('user_id', $userId)->where('status', 'new')->count(),
            'completed' => Order::where('user_id', $userId)->where('status', 'completed')->count(),
            'refunded' => Order::where('user_id', $userId)->where('status', 'cancelled')->count(),
            'failed' => Order::where('user_id', $userId)->where('status', 'failed')->count(),
        ];
        return view('client.orders.index', compact('orders','uiOrders','stats'));
    }
    public function store(Request $request)
    {
        $rules = [
            'variant_id' => ['required', 'exists:product_variants,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ];

        // If user is not logged in, require email
        if (!auth()->check()) {
            $rules['email'] = ['required', 'email', 'max:255'];
            // We can also require name, phone, etc. but email is key for Shadow User
        }

        $data = $request->validate($rules);

        $userId = auth()->id();

        // Shadow User Logic
        if (!$userId) {
            $email = $data['email'];
            $user = User::where('email', $email)->first();

            if (!$user) {
                // Create Shadow User
                $user = User::create([
                    'name' => explode('@', $email)[0], // Default name from email
                    'email' => $email,
                    'password' => Hash::make(Str::random(16)), // Dummy password
                    'role' => 'customer',
                    'is_guest' => true,
                ]);
                // Send email invite logic here (omitted for now)
            }
            $userId = $user->id;
        }

        return DB::transaction(function () use ($data, $userId) {
            $variant = ProductVariant::where('id', $data['variant_id'])->lockForUpdate()->first();
            if (!$variant || $variant->inventory_quantity < $data['quantity']) {
                return back()->withErrors(['stock' => 'Số lượng không đủ trong kho']);
            }

            $order = Order::create([
                'user_id' => $userId,
                'status' => 'new',
                'subtotal' => $variant->price * $data['quantity'],
                'discount_amount' => 0,
                'total' => $variant->price * $data['quantity'],
                'placed_at' => now(),
            ]);

            $opts = $variant->options()->with(['attribute','attributeValue'])->get();
            $snapColor = null;
            $snapSize = null;
            foreach ($opts as $opt) {
                $code = strtolower((string)($opt->attribute->name ?? ''));
                $val = (string)($opt->attributeValue->value ?? '');
                if ($code === 'color' && $val !== '') { $snapColor = $val; }
                if ($code === 'size' && $val !== '') { $snapSize = $val; }
            }
            $snapColor = $snapColor ?: 'N/A';
            $snapSize = $snapSize ?: 'N/A';
            OrderItem::create([
                'order_id' => $order->id,
                'product_variant_id' => $variant->id,
                'quantity' => $data['quantity'],
                'unit_price' => $variant->price,
                'snapshot_name' => $variant->product->name,
                'snapshot_sku' => $variant->sku,
                'snapshot_color' => $snapColor,
                'snapshot_size' => $snapSize,
            ]);

            $variant->decrement('inventory_quantity', $data['quantity']);
            try {
                $w = Warehouse::where('code','MAIN')->first() ?: Warehouse::create(['name'=>'Main Warehouse','code'=>'MAIN','address'=>'','is_active'=>true]);
                $stock = WarehouseInventory::firstOrCreate(
                    ['warehouse_id' => $w->id, 'product_variant_id' => $variant->id],
                    ['quantity' => 0]
                );
                $stock->decrement('quantity', $data['quantity']);
                StockMovement::create([
                    'warehouse_id' => $w->id,
                    'product_variant_id' => $variant->id,
                    'quantity' => -$data['quantity'],
                    'type' => 'out',
                    'reason' => 'Sale Order #'.$order->id,
                    'reference_id' => $order->id,
                ]);
            } catch (\Exception $e) {}

            return redirect()->route('orders.show', $order->id)->with('status', 'Đặt hàng thành công!');
        });
    }

    public function show(int $orderId)
    {
        $order = Order::with(['items.productVariant', 'user'])->findOrFail($orderId);
        return view('client.orders.show_glamics', compact('order'));
    }

    public function getOrderDetailHtml(int $orderId)
    {
        $order = Order::with(['items.productVariant', 'user'])->findOrFail($orderId);
        return view('client.orders._show_modal', compact('order'))->render();
    }
}
