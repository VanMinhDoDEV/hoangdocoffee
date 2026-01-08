<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
// Legacy Variant removed: use ProductVariant instead
use App\Models\ProductVariant;
use App\Models\Combo;
use App\Models\ComboLine;
use App\Models\Category;
use App\Models\User;
use App\Models\Post;
use App\Models\VolumePricing;
use App\Models\PromotionRule;
use App\Models\ProductAttribute;
use App\Models\ProductAttributeValue;
use App\Models\ProductVariantOption;
use App\Models\Option;
use App\Models\OptionValue;
use App\Models\VariantValue;
use App\Models\Warehouse;
use App\Models\WarehouseInventory;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;

class AdminController extends Controller
{
    private function skuSanitize(string $s, int $max = 16): string
    {
        $s = strtoupper(preg_replace('/[^A-Za-z0-9\-]/', '', $s));
        if (strlen($s) > $max) { $s = substr($s, 0, $max); }
        return $s;
    }
    private function base36now(int $len = 5): string
    {
        $n = (int) round(microtime(true) * 1000);
        $b = strtoupper(base_convert($n, 10, 36));
        return strlen($b) >= $len ? substr($b, -$len) : str_pad($b, $len, '0', STR_PAD_LEFT);
    }
    private function makePrefix(?string $collection = null, ?int $categoryId = null, ?string $vendor = null): string
    {
        $src = null;
        if ($categoryId) {
            $cat = \App\Models\Category::find($categoryId);
            $src = $cat ? $cat->name : null;
        }
        if (!$src) {
            $src = $collection ?: ($vendor ?: null);
        }
        $src = $src ?: 'PR';
        $norm = $this->normalizeAscii($src);
        $acc = substr($norm !== '' ? $norm : 'PR', 0, 2);
        return $acc !== '' ? $acc : 'PR';
    }
    private function normalizeAscii(string $s): string
    {
        $t = $s;
        if (function_exists('iconv')) {
            $r = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $t);
            if ($r !== false) { $t = $r; }
        }
        $t = strtr($t, ['đ' => 'd', 'Đ' => 'D']);
        $t = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $t));
        return $t;
    }
    private function ensureUniqueSku(string $sku): string
    {
        $sku = $this->skuSanitize($sku);
        if (!\App\Models\ProductVariant::where('sku', $sku)->exists()) return $sku;
        for ($i = 0; $i < 36; $i++) {
            $extra = strtoupper(base_convert($i, 10, 36));
            $cand = strlen($sku) >= 16 ? (substr($sku, 0, 15).$extra) : $this->skuSanitize($sku.$extra);
            if (!\App\Models\ProductVariant::where('sku', $cand)->exists()) return $cand;
        }
        $x = strtoupper(substr($this->base36now(1), 0, 1));
        $cand = strlen($sku) >= 16 ? (substr($sku, 0, 15).$x) : $this->skuSanitize($sku.$x);
        return $cand;
    }
    private function generateProductSkuBase(?string $collection, ?int $categoryId, ?string $vendor): string
    {
        $prefix = $this->makePrefix($collection, $categoryId, $vendor);
        $suffix = $this->base36now(5);
        return $this->skuSanitize($prefix.'-'.$suffix);
    }
    // Removed abbrSize and abbrColor as they are hardcoded for fashion
    // Use abbrValue for generic shortening
    private function abbrValue(?string $val): string
    {
        $v = trim((string)$val);
        if ($v === '') return '';
        if (preg_match('/^#?[0-9A-Fa-f]{3,6}$/', $v)) {
            $hex = strtoupper(ltrim($v, '#'));
            return substr($hex, 0, 3);
        }
        $s = strtoupper(preg_replace('/\s+/', '', $v));
        return substr($s, 0, 3);
    }
    private function variantAttrSig(array $values): string
    {
        $tokens = [];
        // Sort values by option name or id to ensure consistent order if needed
        // Here we just iterate as provided
        foreach ($values as $val) {
            $vv = (string)($val['value'] ?? '');
            $t = $this->abbrValue($vv);
            if ($t !== '') { $tokens[] = $t; }
        }
        return $this->skuSanitize(implode('-', $tokens));
    }
    private function buildVariantSku(string $productBaseSku, array $values = []): string
    {
        $sig = $this->variantAttrSig($values);
        $base = $this->skuSanitize($productBaseSku);
        // If sig is empty, we just append a time suffix or unique string
        // If sig is present, we append it to base
        
        $prefix = explode('-', $base)[0] ?? $base;
        // Logic: ProductSKU - VariantSig - Random
        // e.g. COFFEE01-250G-ROBUSTA-A1B2C
        
        $time = $this->base36now(5);
        $parts = [$base];
        if ($sig !== '') {
            $parts[] = $sig;
        }
        $parts[] = $time;
        
        $cand = $this->skuSanitize(implode('-', array_filter($parts)));
        $cand = $this->ensureUniqueSku($cand);
        return $cand;
    }
    public function dashboard()
    {
        $stats = [
            'products' => Product::count(),
            'orders' => Order::count(),
            'users' => User::count(),
            'revenue' => Order::sum('total'),
        ];
        
        // Sales Trend (Monthly)
        $currentYearSales = Order::selectRaw('MONTH(created_at) as month, SUM(total) as total')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->pluck('total', 'month')->toArray();
            
        $lastYearSales = Order::selectRaw('MONTH(created_at) as month, SUM(total) as total')
            ->whereYear('created_at', date('Y') - 1)
            ->groupBy('month')
            ->pluck('total', 'month')->toArray();

        $salesData = [
            'current' => [],
            'last' => []
        ];
        // Months 1-12
        for ($i = 1; $i <= 12; $i++) {
            $salesData['current'][] = $currentYearSales[$i] ?? 0;
            $salesData['last'][] = $lastYearSales[$i] ?? 0;
        }

        // Top Categories (by quantity sold)
        $topCategories = \Illuminate\Support\Facades\DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('product_variants', 'order_items.product_variant_id', '=', 'product_variants.id')
            ->join('products', 'product_variants.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select('categories.name', \Illuminate\Support\Facades\DB::raw('SUM(order_items.quantity) as total_sold'))
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total_sold')
            ->limit(4)
            ->get();
            
        $totalSoldAll = \Illuminate\Support\Facades\DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->sum('quantity');
            
        $topCategories = $topCategories->map(function($cat) use ($totalSoldAll) {
            $cat->percentage = $totalSoldAll > 0 ? round(($cat->total_sold / $totalSoldAll) * 100) : 0;
            return $cat;
        });

        $recentOrders = Order::with('user')->latest()->take(8)->get();
        
        return view('admin.dashboard.index', compact('stats', 'recentOrders', 'salesData', 'topCategories'));
    }

    public function globalSearch(Request $request)
    {
        $query = $request->get('q');
        
        if (empty($query)) {
            return response()->json([
                'products' => [],
                'customers' => []
            ]);
        }

        // Search Products
        $products = Product::where('name', 'like', "%{$query}%")
            ->orWhere('product_sku', 'like', "%{$query}%")
            ->with(['images' => function($q) {
                $q->where('is_primary', true)->orderBy('position')->limit(1);
            }])
            ->limit(5)
            ->get()
            ->map(function($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'sku' => $product->product_sku,
                    'image' => $product->images->first() ? $product->images->first()->url : null,
                    'price' => number_format($product->price),
                    'url' => route('admin.products.edit', $product->id)
                ];
            });

        // Search Customers (Users)
        $customers = User::where('name', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->orWhere('phone', 'like', "%{$query}%")
            ->limit(5)
            ->get()
            ->map(function($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'avatar' => $user->avatar ? \Illuminate\Support\Facades\Storage::url($user->avatar) : null,
                    'url' => route('admin.customers.show', $user->id) // Assuming there is a customer detail page
                ];
            });

        return response()->json([
            'products' => $products,
            'customers' => $customers
        ]);
    }

    public function products(\Illuminate\Http\Request $request)
    {
        $query = Product::withCount('variants')
            ->with([
                'images' => function ($q) { $q->where('is_primary', true)->orderBy('position')->limit(1); },
                'variants' => function ($q) { $q->select('id','product_id','inventory_quantity','sku','price','is_active'); },
                'category'
            ])
            ->latest();

        $status = $request->query('status');
        if ($status === 'active') { $query->where('is_active', true); }
        if ($status === 'hidden') { $query->where('is_active', false); }

        $categoryId = (int)($request->query('category_id') ?? 0);
        if ($categoryId) { $query->where('category_id', $categoryId); }

        $stock = $request->query('stock');
        if ($stock === 'in') { $query->where('in_stock', true); }
        if ($stock === 'out') { $query->where('in_stock', false); }

        $featured = $request->query('featured');
        if ($featured === '1') { $query->where('is_featured', true); }

        $search = trim((string)$request->query('q', ''));
        if ($search !== '') {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                  ->orWhere('slug', 'like', '%'.$search.'%')
                  ->orWhere('material', 'like', '%'.$search.'%');
            });
        }

        $products = $query->paginate(10)->appends($request->query());
        $allCategories = Category::orderBy('name')->get(['id','name']);
        $filters = [
            'status' => $status,
            'category_id' => $categoryId ?: '',
            'stock' => $stock,
            'featured' => $featured,
            'q' => $search,
        ];
        $statsProducts = [
            'total' => Product::count(),
            'active' => Product::where('is_active', true)->count(),
            'out_of_stock' => Product::where('in_stock', false)->count(),
            'variants' => ProductVariant::count(),
        ];
        return view('admin.products.index', compact('products','statsProducts','allCategories','filters'));
    }

    public function categories()
    {
        $categories = Category::orderBy('sort_order')->orderBy('name')->paginate(10);
        $allCategories = Category::orderBy('name')->get(['id','name']);
        return view('admin.categories.categories', compact('categories', 'allCategories'));
    }

    public function collections()
    {
        $collections = \App\Models\Collection::with(['images' => function($q){ $q->orderBy('position'); }])
            ->orderBy('sort_order')->orderBy('name')->paginate(10);
        $allCollections = \App\Models\Collection::orderBy('name')->get(['id','name']);
        return view('admin.collections.collections', compact('collections', 'allCollections'));
    }

    public function posts(\Illuminate\Http\Request $request)
    {
        $query = Post::with(['author', 'category', 'tags']);
        
        $search = trim((string)$request->query('q', ''));
        if ($search !== '') {
            $query->where('title', 'like', '%'.$search.'%');
        }

        $dateFilter = $request->query('date');
        if ($dateFilter === 'this_month') {
            $query->whereMonth('created_at', now()->month)
                  ->whereYear('created_at', now()->year);
        } elseif ($dateFilter === 'last_month') {
            $query->whereMonth('created_at', now()->subMonth()->month)
                  ->whereYear('created_at', now()->subMonth()->year);
        }

        $categoryFilter = $request->query('category');
        if ($categoryFilter && $categoryFilter !== 'all') {
            $query->where('category_id', $categoryFilter);
        }

        $posts = $query->latest()->paginate(10)->appends($request->query());
        
        $stats = [
            'total' => Post::count(),
        ];
        
        $filters = [
            'q' => $search,
            'date' => $dateFilter,
            'category' => $categoryFilter,
        ];

        $categories = \App\Models\PostCategory::orderBy('name')->get();

        return view('admin.posts.index', compact('filters', 'posts', 'stats', 'categories'));
    }

    private function readSettings(): array
    {
        $path = 'settings.json';
        if (!Storage::disk('local')->exists($path)) {
            return [
                'store' => [
                    'name' => '',
                    'email' => '',
                    'phone' => '',
                    'address' => '',
                    'logo_url' => '',
                    'theme_color' => '#0ea5e9'
                ],
                'payment' => [
                    'enabled_methods' => ['cod'],
                    'cod_enabled' => true,
                    'bank_transfer_enabled' => false,
                    'wallet_enabled' => false,
                    'credit_card_enabled' => false,
                    'bank_account_name' => '',
                    'bank_account_number' => '',
                    'bank_name' => '',
                    'bank_branch' => '',
                    'transfer_note' => '',
                    'min_order' => 0,
                    'cod_fee' => 0,
                    'cod_fee_type' => 'fixed',
                    'auto_confirm' => false,
                ],
                'shipping' => [
                    'mode' => 'company',
                    'carrier' => 'viettel_post',
                    'free_threshold' => null,
                    'flat_rate' => null,
                    'use_weight' => false,
                ],
            ];
        }
        $raw = Storage::disk('local')->get($path);
        $data = json_decode($raw, true);
        return is_array($data) ? $data : [];
    }
    private function writeSettings(array $data): void
    {
        $path = 'settings.json';
        Storage::disk('local')->put($path, json_encode($data, JSON_UNESCAPED_UNICODE));
    }
    public function settingsMenusSave(Request $request)
    {
        $data = $request->validate([
            'menus' => ['required'],
        ]);
        $payload = is_string($data['menus']) ? json_decode($data['menus'], true) : $data['menus'];
        if (!is_array($payload)) { $payload = []; }
        
        // Recursive sanitization function could be added here if needed
        // For now, we trust the structure from the UI builder
        
        $settings = $this->readSettings();
        $settings['menus'] = $payload;
        $this->writeSettings($settings);
        return response()->json(['status' => 'success', 'menus' => $settings['menus']]);
    }
    public function settingsGeneral()
    {
        $settings = $this->readSettings();
        // Calculate some stats for the overview
        $stats = [
            'users_count' => \App\Models\User::count(),
            'orders_today' => \App\Models\Order::whereDate('created_at', today())->count(),
            'revenue_month' => \App\Models\Order::whereMonth('created_at', now()->month)->where('status', 'completed')->sum('total'),
        ];
        
        // Fetch categories and collections for Menu Builder
        $categories = \App\Models\Category::orderBy('name')->get(['id', 'name', 'slug']);
        $collections = \App\Models\Collection::orderBy('name')->get(['id', 'name', 'slug']);
        $postCategories = \App\Models\PostCategory::orderBy('name')->get(['id', 'name', 'slug']);
            
            return view('admin.settings.general', [
            'settings' => $settings['store'] ?? [], 
            'stats' => $stats, 
            'menus' => $settings['menus'] ?? [],
            'categories' => $categories,
            'collections' => $collections,
            'postCategories' => $postCategories
        ]);
    }

    public function settingsStore()
    {
        $settings = $this->readSettings();
        return view('admin.settings.store', ['settings' => $settings['store'] ?? []]);
    }
    public function settingsStoreSave(Request $request)
    {
        $data = $request->validate([
            'name' => ['nullable','string','max:255'],
            'tagline' => ['nullable','string','max:255'],
            'seo_description' => ['nullable','string','max:255'],
            'email' => ['nullable','string','max:255'],
            'phone' => ['nullable','string','max:50'],
            'address' => ['nullable','string','max:500'],
            'website' => ['nullable','string','max:255'],
            'description' => ['nullable','string','max:2000'],
            'logo_url' => ['nullable','string','max:1000'],
            'favicon' => ['nullable','image','max:1024'],
            'header_logo' => ['nullable','image','max:5120'],
            'footer_logo' => ['nullable','image','max:5120'],
            'theme_color' => ['nullable','string','max:20'],
            'hours_weekdays_open' => ['nullable','string','max:10'],
            'hours_weekdays_close' => ['nullable','string','max:10'],
            'hours_sat_open' => ['nullable','string','max:10'],
            'hours_sat_close' => ['nullable','string','max:10'],
            'hours_sun_open' => ['nullable','string','max:10'],
            'hours_sun_close' => ['nullable','string','max:10'],
            'facebook_url' => ['nullable','string','max:255'],
            'instagram_url' => ['nullable','string','max:255'],
            'zalo_phone' => ['nullable','string','max:50'],
        ]);
        $settings = $this->readSettings();

        // Handle Favicon Upload
        if ($request->hasFile('favicon')) {
            $file = $request->file('favicon');
            $original = $file->getClientOriginalName();
            $name = pathinfo($original, PATHINFO_FILENAME);
            $ext = $file->getClientOriginalExtension();
            $filename = \Illuminate\Support\Str::slug($name).'.'.$ext;
            if (Storage::disk('public')->exists('favicons/'.$filename)) {
                Storage::disk('public')->delete('favicons/'.$filename);
            }
            $path = $file->storeAs('favicons', $filename, 'public');
            $settings['store']['favicon'] = '/storage/'.$path;
        }
        // Handle Header Logo Upload
        if ($request->hasFile('header_logo')) {
            $file = $request->file('header_logo');
            $original = $file->getClientOriginalName();
            $name = pathinfo($original, PATHINFO_FILENAME);
            $ext = $file->getClientOriginalExtension();
            $filename = \Illuminate\Support\Str::slug($name).'.'.$ext;
            if (Storage::disk('public')->exists('logos/'.$filename)) {
                Storage::disk('public')->delete('logos/'.$filename);
            }
            $path = $file->storeAs('logos', $filename, 'public');
            $settings['store']['header_logo_url'] = '/storage/'.$path;
        }
        // Handle Footer Logo Upload
        if ($request->hasFile('footer_logo')) {
            $file = $request->file('footer_logo');
            $original = $file->getClientOriginalName();
            $name = pathinfo($original, PATHINFO_FILENAME);
            $ext = $file->getClientOriginalExtension();
            $filename = \Illuminate\Support\Str::slug($name).'.'.$ext;
            if (Storage::disk('public')->exists('logos/'.$filename)) {
                Storage::disk('public')->delete('logos/'.$filename);
            }
            $path = $file->storeAs('logos', $filename, 'public');
            $settings['store']['footer_logo_url'] = '/storage/'.$path;
        }

        $settings['store'] = array_merge($settings['store'] ?? [], [
            'name' => $data['name'] ?? '',
            'tagline' => $data['tagline'] ?? '',
            'seo_description' => $data['seo_description'] ?? '',
            'email' => $data['email'] ?? '',
            'phone' => $data['phone'] ?? '',
            'address' => $data['address'] ?? '',
            'website' => $data['website'] ?? '',
            'description' => $data['description'] ?? '',
            'logo_url' => $data['logo_url'] ?? ($settings['store']['logo_url'] ?? ''),
            'theme_color' => $data['theme_color'] ?? '#0ea5e9',
            'hours_weekdays_open' => $data['hours_weekdays_open'] ?? '08:00',
            'hours_weekdays_close' => $data['hours_weekdays_close'] ?? '18:00',
            'hours_sat_open' => $data['hours_sat_open'] ?? '08:00',
            'hours_sat_close' => $data['hours_sat_close'] ?? '17:00',
            'hours_sun_open' => $data['hours_sun_open'] ?? '09:00',
            'hours_sun_close' => $data['hours_sun_close'] ?? '16:00',
            'facebook_url' => $data['facebook_url'] ?? '',
            'instagram_url' => $data['instagram_url'] ?? '',
            'zalo_phone' => $data['zalo_phone'] ?? '',
        ]);

        $this->writeSettings($settings);

        if ((string)$request->input('redirect_to') === 'general') {
            return redirect()->route('admin.settings.general')->with('status', 'Đã lưu thông tin tổng quan');
        }

        return redirect()->route('admin.settings.store')->with('status','Đã lưu thông tin cửa hàng');
    }
    public function settingsPayment()
    {
        $settings = $this->readSettings();
        return view('admin.settings.payment', ['settings' => $settings['payment'] ?? []]);
    }
    public function settingsPaymentSave(Request $request)
    {
        $data = $request->validate([
            'cod_enabled' => ['nullable','boolean'],
            'bank_transfer_enabled' => ['nullable','boolean'],
            'wallet_enabled' => ['nullable','boolean'],
            'credit_card_enabled' => ['nullable','boolean'],
            'bank_account_name' => ['nullable','string','max:255'],
            'bank_account_number' => ['nullable','string','max:50'],
            'bank_name' => ['nullable','string','max:255'],
            'bank_branch' => ['nullable','string','max:255'],
            'transfer_note' => ['nullable','string','max:255'],
            'min_order' => ['nullable','numeric','min:0'],
            'cod_fee' => ['nullable','numeric','min:0'],
            'cod_fee_type' => ['nullable','string','in:fixed,percent'],
            'auto_confirm' => ['nullable','boolean'],
        ]);
        $settings = $this->readSettings();
        $enabled = [];
        if ($data['cod_enabled'] ?? false) $enabled[] = 'cod';
        if ($data['bank_transfer_enabled'] ?? false) $enabled[] = 'bank_transfer';
        if ($data['wallet_enabled'] ?? false) $enabled[] = 'wallet';
        if ($data['credit_card_enabled'] ?? false) $enabled[] = 'credit';
        $settings['payment'] = [
            'enabled_methods' => $enabled,
            'cod_enabled' => (bool)($data['cod_enabled'] ?? false),
            'bank_transfer_enabled' => (bool)($data['bank_transfer_enabled'] ?? false),
            'wallet_enabled' => (bool)($data['wallet_enabled'] ?? false),
            'credit_card_enabled' => (bool)($data['credit_card_enabled'] ?? false),
            'bank_account_name' => $data['bank_account_name'] ?? '',
            'bank_account_number' => $data['bank_account_number'] ?? '',
            'bank_name' => $data['bank_name'] ?? '',
            'bank_branch' => $data['bank_branch'] ?? '',
            'transfer_note' => $data['transfer_note'] ?? '',
            'min_order' => isset($data['min_order']) ? (float)$data['min_order'] : 0,
            'cod_fee' => isset($data['cod_fee']) ? (float)$data['cod_fee'] : 0,
            'cod_fee_type' => $data['cod_fee_type'] ?? 'fixed',
            'auto_confirm' => (bool)($data['auto_confirm'] ?? false),
        ];
        $this->writeSettings($settings);
        return redirect()->route('admin.settings.payment')->with('status','Đã lưu cài đặt thanh toán');
    }
    public function settingsShipping()
    {
        $settings = $this->readSettings();
        return view('admin.settings.shipping', ['settings' => $settings['shipping'] ?? []]);
    }
    public function settingsShippingSave(Request $request)
    {
        $data = $request->validate([
            // Toggles for shipping methods
            'standard_enabled' => ['nullable','boolean'],
            'express_enabled' => ['nullable','boolean'],
            'sameday_enabled' => ['nullable','boolean'],
            'pickup_enabled' => ['nullable','boolean'],

            // Zone fees
            'inner_standard' => ['nullable','numeric','min:0'],
            'inner_express' => ['nullable','numeric','min:0'],
            'inner_sameday' => ['nullable','numeric','min:0'],
            'suburban_standard' => ['nullable','numeric','min:0'],
            'suburban_express' => ['nullable','numeric','min:0'],
            'suburban_sameday' => ['nullable','numeric','min:0'],
            'province_standard' => ['nullable','numeric','min:0'],
            'province_express' => ['nullable','numeric','min:0'],
            'province_sameday' => ['nullable','numeric','min:0'],

            // Additional settings
            'free_threshold' => ['nullable','numeric','min:0'],
            'max_weight' => ['nullable','numeric','min:0'],
            'processing_time' => ['nullable','string','in:24,48,72'],
            'use_weight' => ['nullable','boolean'],
            'cod_shipping' => ['nullable','boolean'],
        ]);

        $settings = $this->readSettings();

        $settings['shipping'] = array_merge($settings['shipping'] ?? [], [
            // Enabled methods
            'standard_enabled' => (bool)($data['standard_enabled'] ?? true),
            'express_enabled' => (bool)($data['express_enabled'] ?? true),
            'sameday_enabled' => (bool)($data['sameday_enabled'] ?? false),
            'pickup_enabled' => (bool)($data['pickup_enabled'] ?? true),

            // Zone fees (defaults aligned with UI)
            'inner_standard' => isset($data['inner_standard']) ? (float)$data['inner_standard'] : (float)($settings['shipping']['inner_standard'] ?? 25000),
            'inner_express' => isset($data['inner_express']) ? (float)$data['inner_express'] : (float)($settings['shipping']['inner_express'] ?? 45000),
            'inner_sameday' => isset($data['inner_sameday']) ? (float)$data['inner_sameday'] : (float)($settings['shipping']['inner_sameday'] ?? 65000),
            'suburban_standard' => isset($data['suburban_standard']) ? (float)$data['suburban_standard'] : (float)($settings['shipping']['suburban_standard'] ?? 35000),
            'suburban_express' => isset($data['suburban_express']) ? (float)$data['suburban_express'] : (float)($settings['shipping']['suburban_express'] ?? 60000),
            'suburban_sameday' => isset($data['suburban_sameday']) ? (float)$data['suburban_sameday'] : (float)($settings['shipping']['suburban_sameday'] ?? 0),
            'province_standard' => isset($data['province_standard']) ? (float)$data['province_standard'] : (float)($settings['shipping']['province_standard'] ?? 45000),
            'province_express' => isset($data['province_express']) ? (float)$data['province_express'] : (float)($settings['shipping']['province_express'] ?? 80000),
            'province_sameday' => isset($data['province_sameday']) ? (float)$data['province_sameday'] : (float)($settings['shipping']['province_sameday'] ?? 0),

            // Add-ons
            'free_threshold' => isset($data['free_threshold']) ? (float)$data['free_threshold'] : (isset($settings['shipping']['free_threshold']) ? (float)$settings['shipping']['free_threshold'] : null),
            'max_weight' => isset($data['max_weight']) ? (float)$data['max_weight'] : (float)($settings['shipping']['max_weight'] ?? 20),
            'processing_time' => $data['processing_time'] ?? ($settings['shipping']['processing_time'] ?? '24'),
            'use_weight' => (bool)($data['use_weight'] ?? ($settings['shipping']['use_weight'] ?? false)),
            'cod_shipping' => (bool)($data['cod_shipping'] ?? ($settings['shipping']['cod_shipping'] ?? true)),
        ]);

        $this->writeSettings($settings);
        return redirect()->route('admin.settings.shipping')->with('status','Đã lưu cài đặt vận chuyển');
    }

    public function productCreate()
    {
        $allCategories = Category::orderBy('name')->get(['id','name']);
        
        $settings = $this->readSettings();
        $paymentSettings = $settings['payment'] ?? [];
        return view('admin.products.create', compact('allCategories', 'paymentSettings'));
    }

    public function productShow($id)
    {
        $product = Product::findOrFail($id);
        if ($product->slug) {
            return redirect()->route('products.show', $product->slug);
        }
        return redirect()->route('admin.products');
    }

    public function productEdit($id)
    {
        $product = Product::with([
            'images' => function($q){ $q->whereNull('product_variant_id')->orderBy('position'); },
            'variants' => function($q){ $q->select('id','product_id','sku','price','compare_at_price','inventory_quantity','is_active','is_default'); },
            'variants.options.attribute',
            'variants.options.attributeValue',
            'variants.images'
        ])->findOrFail($id);
        $prefillVariants = [];
        foreach (($product->variants ?? collect()) as $v) {
            $values = [];
            foreach (($v->options ?? collect()) as $opt) {
                $code = strtolower((string)($opt->attribute->name ?? ''));
                $val = (string)($opt->attributeValue->value ?? '');
                if ($val === '' || $code === '') { continue; }
                $values[] = ['option_code' => $code, 'value' => $val];
            }
            $option = !empty($values) ? ($values[0]['option_code'] ?? null) : null;
            $vImages = [];
            foreach (($v->images ?? collect()) as $im) {
                $vImages[] = ['id' => 'existing_'.$im->id, 'url' => $im->url, 'name' => basename($im->url), 'size' => ''];
            }
            $prefillVariants[] = [
                'id' => 'variant_'.$v->id,
                'option' => $option,
                'values' => $values,
                'sku' => $v->sku,
                'stock' => (int)($v->inventory_quantity ?? 0),
                'price' => $v->compare_at_price !== null ? (float)$v->compare_at_price : (float)($v->price ?? 0),
                'sale_price' => $v->compare_at_price !== null ? (float)($v->price ?? 0) : 0.0,
                'is_active' => (bool)($v->is_active ?? true),
                'is_default' => (bool)($v->is_default ?? false),
                'images' => $vImages,
            ];
        }
        $allCategories = Category::orderBy('name')->get(['id','name']);
        
        $defaultVariant = $product->variants->firstWhere('is_default', true);
        $simpleProductStock = $defaultVariant ? (int)$defaultVariant->inventory_quantity : 0;

        $settings = $this->readSettings();
        $paymentSettings = $settings['payment'] ?? [];
        return view('admin.products.edit', compact('product','allCategories','prefillVariants', 'paymentSettings', 'simpleProductStock'));
    }

    public function productUpdate(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'slug' => ['nullable','string','max:255'],
            'product_sku' => ['nullable','string','max:255'],
            'description' => ['nullable','string'],
            'video_url' => ['nullable','url','max:255'],
            'article' => ['nullable','string'],
            'material' => ['nullable','string','max:255'],
            'materials' => ['nullable','array'],
            'materials.*' => ['nullable','string','max:255'],
            'vendor' => ['nullable','string','max:255'],
            'collection' => ['nullable','string','max:255'],
            'category_id' => ['nullable','integer','exists:categories,id'],
            'is_active' => ['nullable','boolean'],
            'is_featured' => ['nullable','boolean'],
            'in_stock' => ['nullable','boolean'],
            'addStock' => ['nullable','integer','min:0'],
            'price' => ['nullable','numeric','min:0'],
            'discounted_price' => ['nullable','numeric','min:0'],
            'shipping_mode' => ['nullable','string','in:seller,company'],
            'payment_method' => ['nullable','string','max:255'],
            'payment_methods' => ['nullable','string','max:255'],
            'shipping_weight' => ['nullable','numeric','min:0'],
            'shipping_dimensions' => ['nullable','string','max:255'],
            'tax' => ['nullable','boolean'],
            'status' => ['nullable','string','max:50'],
            'primary_image_url' => ['nullable','string','max:1000'],
            'images_urls' => ['nullable','array'],
            'images_urls.*' => ['nullable','string','max:1000'],
            'variants' => ['nullable','json'],
        ]);
        \DB::transaction(function () use ($product, $data) {
            $materialsArr = array_values(array_filter(array_map(function($v){ return trim((string)$v); }, $data['materials'] ?? [])));
            $materialsArr = array_values(array_unique($materialsArr));
            $materialStr = $materialsArr ? implode(', ', $materialsArr) : ($data['material'] ?? $product->material);
            $productSkuInput = isset($data['product_sku']) ? trim((string)$data['product_sku']) : '';
            $productSkuVal = $productSkuInput !== '' ? $this->skuSanitize($productSkuInput, 16) : $this->generateProductSkuBase($product->collection, $product->category_id, $product->vendor);
            $product->update([
                'name' => $data['name'],
                'slug' => $data['slug'] ?? $product->slug,
                'product_sku' => $productSkuVal,
                'description' => $data['description'] ?? $product->description,
                'video_url' => $data['video_url'] ?? $product->video_url,
                'article' => $data['article'] ?? $product->article,
                'material' => $materialStr,
                'vendor' => $data['vendor'] ?? $product->vendor,
                'collection' => $data['collection'] ?? $product->collection,
                'category_id' => $data['category_id'] ?? $product->category_id,
                'is_active' => isset($data['is_active']) ? (bool)$data['is_active'] : ($product->is_active ?? true),
                'is_featured' => (bool)($data['is_featured'] ?? false),
                'in_stock' => isset($data['in_stock']) ? (bool)$data['in_stock'] : ($product->in_stock ?? true),
                'price' => (isset($data['price']) && $data['price'] !== '') ? (float)$data['price'] : null,
                'discounted_price' => (isset($data['discounted_price']) && $data['discounted_price'] !== '') ? (float)$data['discounted_price'] : null,
                'shipping_mode' => $data['shipping_mode'] ?? $product->shipping_mode,
                'payment_method' => $data['payment_methods'] ?? ($data['payment_method'] ?? $product->payment_method),
                'shipping_weight' => $data['shipping_weight'] ?? $product->shipping_weight,
                'shipping_dimensions' => $data['shipping_dimensions'] ?? $product->shipping_dimensions,
                'tax' => $data['tax'] ?? $product->tax,
                'status' => $data['status'] ?? $product->status,
            ]);
            \App\Models\ProductImage::where('product_id', $product->id)->whereNull('product_variant_id')->delete();
            $position = 1;
            $primaryDone = false;
            if (!empty($data['primary_image_url'])) {
                \App\Models\ProductImage::create([
                    'product_id' => $product->id,
                    'product_variant_id' => null,
                    'url' => $data['primary_image_url'],
                    'is_primary' => true,
                    'position' => $position,
                ]);
                $position++;
                $primaryDone = true;
            }
            $images = $data['images_urls'] ?? [];
            if (!$primaryDone && !empty($images)) {
                \App\Models\ProductImage::create([
                    'product_id' => $product->id,
                    'product_variant_id' => null,
                    'url' => $images[0],
                    'is_primary' => true,
                    'position' => $position,
                ]);
                $position++;
                unset($images[0]);
            }
            foreach ($images as $url) {
                if ($url === ($data['primary_image_url'] ?? null)) { continue; }
                \App\Models\ProductImage::create([
                    'product_id' => $product->id,
                    'product_variant_id' => null,
                    'url' => $url,
                    'is_primary' => false,
                    'position' => $position,
                ]);
                $position++;
            }
            $oldVariantIds = \App\Models\ProductVariant::where('product_id', $product->id)->pluck('id');
            if ($oldVariantIds->count()) {
                \App\Models\ProductVariantOption::whereIn('variant_id', $oldVariantIds)->delete();
                \App\Models\ProductImage::where('product_id', $product->id)->whereIn('product_variant_id', $oldVariantIds)->delete();
                \App\Models\ProductVariant::whereIn('id', $oldVariantIds)->delete();
            }
            $variantsPayload = [];
            if (!empty($data['variants'])) {
                $variantsPayload = json_decode($data['variants'], true) ?: [];
            }
            $productBaseSku = $this->generateProductSkuBase($product->collection, $product->category_id, $product->vendor);
            if (empty($variantsPayload)) {
                $defaultSkuBase = $product->product_sku ?: $productBaseSku;
                $defaultSku = $this->ensureUniqueSku($defaultSkuBase.'-DF');
                $priceDefault = (isset($data['discounted_price']) && $data['discounted_price'] !== '' && (float)$data['discounted_price'] > 0)
                    ? (float)$data['discounted_price']
                    : ((isset($data['price']) && $data['price'] !== '') ? (float)$data['price'] : 0.0);
                $compareDefault = (isset($data['discounted_price']) && $data['discounted_price'] !== '' && (float)$data['discounted_price'] > 0 && isset($data['price']) && $data['price'] !== '')
                    ? (float)$data['price']
                    : null;
                $variant = \App\Models\ProductVariant::firstOrCreate([
                    'product_id' => $product->id,
                    'is_default' => true,
                ], [
                    'sku' => $defaultSku,
                    'price' => $priceDefault,
                    'compare_at_price' => $compareDefault,
                    'inventory_quantity' => 0,
                    'is_active' => (bool)($data['is_active'] ?? true),
                ]);
                $warehouse = $this->defaultWarehouse();
                $desiredQty = (int)($data['addStock'] ?? 0);
                $currentQty = (int)($variant->inventory_quantity ?? 0);
                $delta = $desiredQty - $currentQty;
                if ($delta !== 0) {
                    $this->adjustVariantInWarehouse($variant->id, $warehouse->id, $delta, 'adjustment', 'product_update_default', $product->id, 'Sync default variant stock');
                }
            }
            foreach ($variantsPayload as $v) {
                $values = $v['values'] ?? [];
                if (empty($values)) {
                    if (!empty($v['color'])) {
                        $values[] = ['option_code' => 'color', 'value' => $v['color'], 'slug' => \Illuminate\Support\Str::slug($v['color'])];
                    }
                    if (!empty($v['size'])) {
                        $values[] = ['option_code' => 'size', 'value' => $v['size'], 'slug' => \Illuminate\Support\Str::slug($v['size'])];
                    }
                }
                $skuInput = isset($v['sku']) ? trim((string)$v['sku']) : '';
                $sku = $skuInput !== '' ? $this->skuSanitize($skuInput) : $this->buildVariantSku($productBaseSku, $values);
                $sku = $this->ensureUniqueSku($sku);
                $variantPriceInput = isset($v['sale_price']) && $v['sale_price'] !== null && $v['sale_price'] > 0 ? (float)$v['sale_price'] : null;
                $variantListedInput = isset($v['price']) && $v['price'] !== null && $v['price'] > 0 ? (float)$v['price'] : null;
                $finalPrice = null;
                $finalCompare = null;
                if ($variantPriceInput !== null) {
                    $finalPrice = $variantPriceInput;
                    $finalCompare = $variantListedInput ?? (isset($data['price']) ? (float)$data['price'] : null);
                } elseif ($variantListedInput !== null) {
                    $finalPrice = $variantListedInput;
                    $finalCompare = null;
                } else {
                    if (isset($data['discounted_price']) && $data['discounted_price'] !== null && $data['discounted_price'] > 0) {
                        $finalPrice = (float)$data['discounted_price'];
                        $finalCompare = isset($data['price']) ? (float)$data['price'] : null;
                    } else {
                        $finalPrice = isset($data['price']) ? (float)$data['price'] : 0.0;
                        $finalCompare = null;
                    }
                }
                $variant = \App\Models\ProductVariant::create([
                    'product_id' => $product->id,
                    'sku' => $sku,
                    'inventory_quantity' => (int)($v['stock'] ?? 0),
                    'price' => $finalPrice,
                    'compare_at_price' => $finalCompare,
                    'is_active' => (bool)($v['is_active'] ?? true),
                ]);
                $warehouse = $this->defaultWarehouse();
                $initialQty = (int)($v['stock'] ?? 0);
                if ($initialQty !== 0) {
                    $this->adjustVariantInWarehouse($variant->id, $warehouse->id, $initialQty, 'receipt', 'product_update', $product->id, 'Sync stock on variant create');
                }
                foreach ($values as $val) {
                    $code = $val['option_code'] ?? $val['code'] ?? null;
                    $valueText = $val['value'] ?? null;
                    if (!$code || !$valueText) { continue; }
                    $attr = \App\Models\ProductAttribute::firstOrCreate(['name' => $code]);
                    $attrVal = \App\Models\ProductAttributeValue::firstOrCreate([
                        'attribute_id' => $attr->id,
                        'value' => $valueText,
                        'slug' => \Illuminate\Support\Str::slug($valueText),
                    ]);
                    \App\Models\ProductVariantOption::updateOrCreate([
                        'variant_id' => $variant->id,
                        'attribute_id' => $attr->id,
                    ], [
                        'attribute_value_id' => $attrVal->id,
                    ]);
                }
                if (!empty($v['images']) && is_array($v['images'])) {
                    foreach ($v['images'] as $url) {
                        if (!is_string($url) || strlen($url) < 5) { continue; }
                        \App\Models\ProductImage::create([
                            'product_id' => $product->id,
                            'product_variant_id' => $variant->id,
                            'url' => $url,
                            'is_primary' => false,
                            'position' => ++$position,
                        ]);
                    }
                }
            }
            $totalQty = \App\Models\ProductVariant::where('product_id', $product->id)->sum('inventory_quantity');
            if ($totalQty <= 0) { $totalQty = (int)($product->quantity ?? 0); }
            $product->update(['in_stock' => $totalQty > 0]);
        });
        return redirect()->route('admin.products')->with('status', 'Cập nhật sản phẩm thành công');
    }

    public function productDestroy($id)
    {
        $product = Product::findOrFail($id);
        \DB::transaction(function() use ($product) {
            $variantIds = ProductVariant::where('product_id', $product->id)->pluck('id');
            if ($variantIds->count()) {
                \App\Models\ProductVariantOption::whereIn('variant_id', $variantIds)->delete();
            }
            \App\Models\ProductImage::where('product_id', $product->id)->delete();
            // \App\Models\SizeChart::where('product_id', $product->id)->delete();
            ProductVariant::where('product_id', $product->id)->delete();
            $product->delete();
        });
        return redirect()->route('admin.products')->with('status', 'Đã xóa sản phẩm');
    }

    public function productsBulkDelete(Request $request)
    {
        $data = $request->validate([
            'ids' => ['required','array','min:1'],
            'ids.*' => ['integer','exists:products,id'],
        ]);
        \DB::transaction(function() use ($data) {
            foreach ($data['ids'] as $id) {
                $product = Product::find($id);
                if (!$product) { continue; }
                $variantIds = ProductVariant::where('product_id', $product->id)->pluck('id');
                if ($variantIds->count()) {
                    \App\Models\ProductVariantOption::whereIn('variant_id', $variantIds)->delete();
                }
                \App\Models\ProductImage::where('product_id', $product->id)->delete();
                // \App\Models\SizeChart::where('product_id', $product->id)->delete();
                ProductVariant::where('product_id', $product->id)->delete();
                $product->delete();
            }
        });
        if ($request->expectsJson()) {
            return response()->json(['status' => 'ok']);
        }
        return redirect()->route('admin.products')->with('status', 'Đã xóa các sản phẩm đã chọn');
    }

    public function productsJson(Request $request)
    {
        $query = Product::withCount('variants')
            ->with([
                'images' => function ($q) { $q->where('is_primary', true)->orderBy('position')->limit(1); },
                'variants' => function ($q) { $q->select('id','product_id','inventory_quantity','sku','price','is_active'); },
                'category'
            ])
            ->latest();

        $status = $request->query('status');
        if ($status === 'active') { $query->where('is_active', true); }
        if ($status === 'hidden') { $query->where('is_active', false); }
        $categoryId = (int)($request->query('category_id') ?? 0);
        if ($categoryId) { $query->where('category_id', $categoryId); }
        $stock = $request->query('stock');
        if ($stock === 'in') { $query->where('in_stock', true); }
        if ($stock === 'out') { $query->where('in_stock', false); }
        $featured = $request->query('featured');
        if ($featured === '1') { $query->where('is_featured', true); }
        $search = trim((string)$request->query('q', ''));
        if ($search !== '') {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                  ->orWhere('slug', 'like', '%'.$search.'%')
                  ->orWhere('material', 'like', '%'.$search.'%');
            });
        }

        $page = max(1, (int)$request->query('page', 1));
        $products = $query->paginate(10, ['*'], 'page', $page);
        $items = [];
        foreach ($products as $p) {
            $img = ($p->images[0]->url ?? null);
            $v = $p->variants->first();
            $stockSum = $p->variants->count() > 0 ? $p->variants->sum('inventory_quantity') : ((int)($p->quantity ?? 0));
            $items[] = [
                'id' => $p->id,
                'name' => $p->name,
                'slug' => $p->slug,
                'material' => $p->material,
                'image' => $img,
                'category' => $p->category->name ?? null,
                'stock' => $stockSum,
                'sku' => $p->product_sku ?? null,
                'price' => $v->price ?? ($p->discounted_price ?? $p->price ?? null),
                'variants_count' => $p->variants_count ?? $p->variants->count(),
                'is_active' => (bool)($p->is_active ?? true),
                'is_featured' => (bool)($p->is_featured ?? false),
            ];
        }
        return response()->json([
            'items' => $items,
            'pagination' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'total' => $products->total(),
                'per_page' => $products->perPage(),
                'from' => $products->firstItem(),
                'to' => $products->lastItem(),
            ],
        ]);
    }

    public function options(Request $request)
    {
        $sets = \App\Models\OptionSet::with(['options' => function ($q) {
            $q->with(['values' => function ($v) { $v->orderBy('sort_order')->orderBy('value'); }])
              ->orderBy('sort_order')
              ->orderBy('name');
        }])->orderBy('sort_order')->orderBy('name')->get();

        $optionsAll = Option::orderBy('sort_order')->orderBy('name')->get();

        $setId = (int)($request->query('set_id') ?? 0);
        $currentSet = $setId ? $sets->firstWhere('id', $setId) : ($sets->first() ?: null);

        $optionId = (int)($request->query('option_id') ?? 0);
        $currentOption = null;
        if ($currentSet) {
            $currentOption = $optionId
                ? $currentSet->options->firstWhere('id', $optionId)
                : ($currentSet->options->first() ?: null);
        }
        $values = $currentOption ? $currentOption->values : collect();

        return view('admin.options.index', [
            'sets' => $sets,
            'currentSet' => $currentSet,
            'optionsAll' => $optionsAll,
            'currentOption' => $currentOption,
            'values' => $values,
        ]);
    }

    public function optionStore(Request $request)
    {
        $action = $request->input('action');
        if ($action === 'create_option') {
            $data = $request->validate([
                'code' => ['required','string','max:50','alpha_dash','unique:options,code'],
                'semantic' => ['nullable','string','in:custom,color,size,material,pattern'],
                'name' => ['required','string','max:100'],
                'type' => ['nullable','string','in:select,text'],
                'sort_order' => ['nullable','integer','min:0'],
                'is_active' => ['nullable','boolean'],
            ]);
            $finalCode = (($data['semantic'] ?? 'custom') !== 'custom')
                ? strtolower($data['semantic'])
                : $data['code'];
            $created = Option::create([
                'code' => $finalCode,
                'name' => $data['name'],
                'type' => $data['type'] ?? 'select',
                'sort_order' => $data['sort_order'] ?? 0,
                'is_active' => (bool)($data['is_active'] ?? true),
            ]);
            $setId = (int)$request->input('set_id');
            if ($setId) {
                $set = \App\Models\OptionSet::find($setId);
                if ($set) {
                    \App\Models\OptionSetOption::firstOrCreate([
                        'option_set_id' => $setId,
                        'option_id' => $created->id,
                    ], [
                        'sort_order' => $data['sort_order'] ?? 0,
                    ]);
                }
            }
            if ($request->expectsJson()) {
                return response()->json(['status' => 'ok', 'message' => 'Tạo thuộc tính thành công', 'option' => $created], 201);
            }
            return redirect()->route('admin.products.attributes', $setId ? ['set_id' => $setId] : [])->with('status', 'Tạo thuộc tính thành công');
        }

        if ($action === 'create_value') {
            $data = $request->validate([
                'option_id' => ['required','integer','exists:options,id'],
                'value' => ['required','string','max:100'],
                'slug' => ['nullable','string','max:100'],
                'sort_order' => ['nullable','integer','min:0'],
                'is_active' => ['nullable','boolean'],
            ]);
            $slug = $data['slug'] ?? Str::slug($data['value']);
            $createdVal = OptionValue::firstOrCreate([
                'option_id' => $data['option_id'],
                'slug' => $slug,
            ], [
                'value' => $data['value'],
                'sort_order' => $data['sort_order'] ?? 0,
                'is_active' => (bool)($data['is_active'] ?? true),
            ]);
            if ($request->expectsJson()) {
                return response()->json(['status' => 'ok', 'message' => 'Thêm giá trị thành công', 'value' => $createdVal], 201);
            }
            return redirect()->route('admin.products.attributes', ['option_id' => $data['option_id']])->with('status', 'Thêm giá trị thành công');
        }

        if ($action === 'create_set') {
            $data = $request->validate([
                'name' => ['required','string','max:100'],
                'description' => ['nullable','string','max:1000'],
                'sort_order' => ['nullable','integer','min:0'],
                'is_active' => ['nullable','boolean'],
            ]);
            $base = Str::slug($data['name']);
            $code = $base ?: 'set';
            $suffix = 2;
            while (\App\Models\OptionSet::where('code', $code)->exists()) {
                $code = $base.'-'.$suffix++;
            }
            $createdSet = \App\Models\OptionSet::create([
                'code' => $code,
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
                'sort_order' => $data['sort_order'] ?? 0,
                'is_active' => (bool)($data['is_active'] ?? true),
            ]);
            if ($request->expectsJson()) {
                return response()->json(['status' => 'ok', 'message' => 'Tạo bộ thuộc tính thành công', 'set' => $createdSet], 201);
            }
            return redirect()->route('admin.products.attributes')->with('status', 'Tạo bộ thuộc tính thành công');
        }

        if ($action === 'update_set') {
            $setId = (int)$request->input('set_id');
            $data = $request->validate([
                'set_id' => ['required','integer','exists:option_sets,id'],
                'code' => ['required','string','max:50','alpha_dash','unique:option_sets,code,'.$setId.',id'],
                'name' => ['required','string','max:100'],
                'description' => ['nullable','string','max:1000'],
                'is_active' => ['nullable','boolean'],
            ]);
            $set = \App\Models\OptionSet::findOrFail($setId);
            $set->update([
                'code' => $data['code'],
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
                'is_active' => (bool)($data['is_active'] ?? true),
            ]);
            if ($request->expectsJson()) {
                return response()->json(['status' => 'ok', 'message' => 'Cập nhật bộ thuộc tính thành công', 'set' => $set]);
            }
            return redirect()->route('admin.products.attributes', ['set_id' => $setId])->with('status', 'Cập nhật bộ thuộc tính thành công');
        }

        if ($action === 'delete_set') {
            $data = $request->validate([
                'set_id' => ['required','integer','exists:option_sets,id'],
            ]);
            $set = \App\Models\OptionSet::findOrFail($data['set_id']);
            $set->delete();
            if ($request->expectsJson()) {
                return response()->json(['status' => 'ok', 'message' => 'Xóa bộ thuộc tính thành công']);
            }
            return redirect()->route('admin.products.attributes')->with('status', 'Xóa bộ thuộc tính thành công');
        }

        if ($action === 'attach_option') {
            $data = $request->validate([
                'set_id' => ['required','integer','exists:option_sets,id'],
                'option_id' => ['required','integer','exists:options,id'],
                'sort_order' => ['nullable','integer','min:0'],
            ]);
            $attached = \App\Models\OptionSetOption::firstOrCreate([
                'option_set_id' => $data['set_id'],
                'option_id' => $data['option_id'],
            ], [
                'sort_order' => $data['sort_order'] ?? 0,
            ]);
            if ($request->expectsJson()) {
                return response()->json(['status' => 'ok', 'message' => 'Thêm thuộc tính vào bộ thành công', 'attached' => $attached], 201);
            }
            return redirect()->route('admin.products.attributes', ['set_id' => $data['set_id']])->with('status', 'Thêm thuộc tính vào bộ thành công');
        }

        if ($action === 'update_option') {
            $optId = (int)$request->input('option_id');
            $data = $request->validate([
                'option_id' => ['required','integer','exists:options,id'],
                'code' => ['required','string','max:50','alpha_dash','unique:options,code,'.$optId.',id'],
                'name' => ['required','string','max:100'],
                'is_active' => ['nullable','boolean'],
            ]);
            $opt = Option::findOrFail($optId);
            $opt->update([
                'code' => $data['code'],
                'name' => $data['name'],
                'is_active' => (bool)($data['is_active'] ?? true),
            ]);
            if ($request->expectsJson()) {
                return response()->json(['status' => 'ok', 'message' => 'Cập nhật thuộc tính thành công', 'option' => $opt]);
            }
            return redirect()->route('admin.products.attributes')->with('status', 'Cập nhật thuộc tính thành công');
        }

        if ($action === 'delete_option') {
            $data = $request->validate([
                'option_id' => ['required','integer','exists:options,id'],
            ]);
            \App\Models\OptionSetOption::where('option_id', $data['option_id'])->delete();
            OptionValue::where('option_id', $data['option_id'])->delete();
            Option::where('id', $data['option_id'])->delete();
            if ($request->expectsJson()) {
                return response()->json(['status' => 'ok', 'message' => 'Xóa thuộc tính thành công']);
            }
            return redirect()->route('admin.products.attributes')->with('status', 'Xóa thuộc tính thành công');
        }

        return redirect()->route('admin.products.attributes')->with('status', 'Không có hành động hợp lệ');
    }

    public function optionValuesJson($optionId)
    {
        $option = Option::with(['values' => function ($q) {
            $q->orderBy('sort_order')->orderBy('value');
        }])->findOrFail((int)$optionId);
        return response()->json([
            'option' => [
                'id' => $option->id,
                'name' => $option->name,
                'code' => $option->code,
                'type' => $option->type,
            ],
            'values' => $option->values->map(function ($v) {
                return [
                    'id' => $v->id,
                    'value' => $v->value,
                    'slug' => $v->slug,
                    'sort_order' => $v->sort_order,
                    'is_active' => (bool)$v->is_active,
                ];
            }),
        ]);
    }

    public function productAttributesJson()
    {
        $attrs = \App\Models\ProductAttribute::with(['values' => function ($q) {
            $q->orderBy('value');
        }])->orderBy('name')->get();
        return response()->json([
            'attributes' => $attrs->map(function ($a) {
                return [
                    'id' => $a->id,
                    'code' => $a->name,
                    'label' => $a->name,
                    'type' => $a->type,
                    'values' => ($a->values ?? collect())->map(function ($v) {
                        return [
                            'id' => $v->id,
                            'value' => $v->value,
                            'slug' => $v->slug,
                        ];
                    }),
                ];
            }),
        ]);
    }

    public function productAttributeValuesJson($attributeId)
    {
        $attr = \App\Models\ProductAttribute::with(['values' => function ($q) {
            $q->orderBy('value');
        }])->findOrFail((int)$attributeId);
        return response()->json([
            'attribute' => [
                'id' => $attr->id,
                'code' => $attr->name,
                'label' => $attr->name,
                'type' => $attr->type,
            ],
            'values' => ($attr->values ?? collect())->map(function ($v) {
                return [
                    'id' => $v->id,
                    'value' => $v->value,
                    'slug' => $v->slug,
                ];
            }),
        ]);
    }

    public function productCollectionsJson()
    {
        $items = \App\Models\Collection::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->pluck('name');
        return response()->json(['collections' => $items]);
    }

    public function productMaterialsJson()
    {
        $rows = \App\Models\Product::query()
            ->whereNotNull('material')
            ->where('material', '!=', '')
            ->pluck('material');
        $all = [];
        foreach ($rows as $row) {
            foreach (explode(',', $row) as $part) {
                $s = trim($part);
                if ($s !== '') { $all[] = $s; }
            }
        }
        $materials = array_values(array_unique($all));
        sort($materials, SORT_STRING);
        return response()->json(['materials' => $materials]);
    }

    public function productMaterialDelete(Request $request)
    {
        $name = trim((string)$request->input('name', ''));
        if ($name === '') {
            return response()->json(['status' => 'error', 'message' => 'Tên chất liệu không hợp lệ'], 422);
        }

        $products = \App\Models\Product::where('material', 'LIKE', "%{$name}%")->get();
        $count = 0;

        foreach ($products as $product) {
            $materials = array_map('trim', explode(',', (string)$product->material));
            $originalCount = count($materials);
            $materials = array_filter($materials, function($m) use ($name) {
                return strcasecmp($m, $name) !== 0;
            });
            
            if (count($materials) !== $originalCount) {
                $product->material = implode(', ', $materials);
                $product->save();
                $count++;
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => "Đã xóa chất liệu '$name' khỏi $count sản phẩm."
        ]);
    }

    public function productStore(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'slug' => ['nullable','string','max:255','unique:products,slug'],
            'material' => ['nullable','string','max:255'],
            'materials' => ['nullable','array'],
            'materials.*' => ['nullable','string','max:255'],
            'product_sku' => ['nullable','string','max:255'],
            'description' => ['nullable','string'],
            'video_url' => ['nullable','url','max:255'],
            'article' => ['nullable','string'],
            'is_active' => ['nullable','boolean'],
            'is_featured' => ['nullable','boolean'],
            'primary_image_url' => ['nullable','string','max:1000'],
            'images_urls' => ['nullable','array'],
            'images_urls.*' => ['nullable','string','max:1000'],
            'variants' => ['nullable','json'],
            'vendor' => ['nullable','string','max:255'],
            'collection' => ['nullable','string','max:255'],
            'category_id' => ['nullable','integer','exists:categories,id'],
            'status' => ['nullable','string','in:active,draft,archived'],
            'tax' => ['nullable','boolean'],
            'price' => ['nullable','numeric','min:0'],
            'discounted_price' => ['nullable','numeric','min:0'],
            'in_stock' => ['nullable','boolean'],
            'shipping_weight' => ['nullable','numeric','min:0'],
            'shipping_dimensions' => ['nullable','string','max:255'],
            'shipping_mode' => ['nullable','string','in:seller,company'],
            'payment_method' => ['nullable','string','in:all,credit,cod,bank_transfer'],
            'payment_methods' => ['nullable','string','max:255'],
            'is_fragile' => ['nullable','boolean'],
            'is_biodegradable' => ['nullable','boolean'],
            'is_frozen' => ['nullable','boolean'],
            'max_temp' => ['nullable','string','max:255'],
            'expiry_date' => ['nullable','date'],
            'addStock' => ['nullable','integer','min:0'],
        ]);

        $slug = $data['slug'] ?? Str::slug($data['name']);
        $materialsArr = array_values(array_filter(array_map(function($v){ return trim((string)$v); }, $data['materials'] ?? [])));
        $materialsArr = array_values(array_unique($materialsArr));
        $materialStr = $materialsArr ? implode(', ', $materialsArr) : ($data['material'] ?? null);
        $productSkuInput = $data['product_sku'] ?? null;
        $productBaseSku = $productSkuInput ? $this->skuSanitize($productSkuInput) : $this->generateProductSkuBase($data['collection'] ?? null, $data['category_id'] ?? null, $data['vendor'] ?? null);
        $product = Product::create([
            'name' => $data['name'],
            'slug' => $slug,
            'product_sku' => $productSkuInput ?: $productBaseSku,
            'material' => $materialStr,
            'description' => $data['description'] ?? null,
            'video_url' => $data['video_url'] ?? null,
            'article' => $data['article'] ?? null,
            'is_active' => (bool)($data['is_active'] ?? true),
            'is_featured' => (bool)($data['is_featured'] ?? false),
            'vendor' => $data['vendor'] ?? null,
            'collection' => $data['collection'] ?? null,
            'category_id' => $data['category_id'] ?? null,
            'status' => $data['status'] ?? 'active',
            'tax' => (bool)($data['tax'] ?? false),
            'price' => (isset($data['price']) && $data['price'] !== '') ? (float)$data['price'] : null,
            'discounted_price' => (isset($data['discounted_price']) && $data['discounted_price'] !== '') ? (float)$data['discounted_price'] : null,
            'in_stock' => (bool)($data['in_stock'] ?? true),
            'shipping_weight' => $data['shipping_weight'] ?? null,
            'shipping_dimensions' => $data['shipping_dimensions'] ?? null,
            'shipping_mode' => $data['shipping_mode'] ?? 'company',
            'payment_method' => $data['payment_methods'] ?? ($data['payment_method'] ?? null),
            'is_fragile' => (bool)($data['is_fragile'] ?? false),
            'is_biodegradable' => (bool)($data['is_biodegradable'] ?? false),
            'is_frozen' => (bool)($data['is_frozen'] ?? false),
            'max_temp' => $data['max_temp'] ?? null,
            'expiry_date' => $data['expiry_date'] ?? null,
        ]);

        $position = 1;
        $primaryDone = false;
        if (!empty($data['primary_image_url'])) {
            \App\Models\ProductImage::create([
                'product_id' => $product->id,
                'product_variant_id' => null,
                'url' => $data['primary_image_url'],
                'is_primary' => true,
                'position' => $position,
            ]);
            $position++;
            $primaryDone = true;
        }

        $images = $data['images_urls'] ?? [];
        if (!$primaryDone && !empty($images)) {
            \App\Models\ProductImage::create([
                'product_id' => $product->id,
                'product_variant_id' => null,
                'url' => $images[0],
                'is_primary' => true,
                'position' => $position,
            ]);
            $position++;
            unset($images[0]);
        }
        foreach ($images as $url) {
            if ($url === ($data['primary_image_url'] ?? null)) { continue; }
            \App\Models\ProductImage::create([
                'product_id' => $product->id,
                'product_variant_id' => null,
                'url' => $url,
                'is_primary' => false,
                'position' => $position,
            ]);
            $position++;
        }

        $variantsPayload = [];
        if (!empty($data['variants'])) {
            $variantsPayload = json_decode($data['variants'], true) ?: [];
        }
        foreach ($variantsPayload as $v) {
            $values = $v['values'] ?? [];
            if (empty($values)) {
                if (!empty($v['color'])) {
                    $values[] = ['option_code' => 'color', 'value' => $v['color'], 'slug' => \Illuminate\Support\Str::slug($v['color'])];
                }
                if (!empty($v['size'])) {
                    $values[] = ['option_code' => 'size', 'value' => $v['size'], 'slug' => \Illuminate\Support\Str::slug($v['size'])];
                }
            }
            $skuInput = isset($v['sku']) ? trim((string)$v['sku']) : '';
            $sku = $skuInput !== '' ? $this->skuSanitize($skuInput) : $this->buildVariantSku($productBaseSku, $values);
            $sku = $this->ensureUniqueSku($sku);
            $variantPriceInput = isset($v['sale_price']) && $v['sale_price'] !== null && $v['sale_price'] > 0 ? (float)$v['sale_price'] : null;
            $variantListedInput = isset($v['price']) && $v['price'] !== null && $v['price'] > 0 ? (float)$v['price'] : null;
            $finalPrice = null;
            $finalCompare = null;
            if ($variantPriceInput !== null) {
                $finalPrice = $variantPriceInput;
                $finalCompare = $variantListedInput ?? (isset($data['price']) ? (float)$data['price'] : null);
            } elseif ($variantListedInput !== null) {
                $finalPrice = $variantListedInput;
                $finalCompare = null;
            } else {
                if (isset($data['discounted_price']) && $data['discounted_price'] !== null && $data['discounted_price'] > 0) {
                    $finalPrice = (float)$data['discounted_price'];
                    $finalCompare = isset($data['price']) ? (float)$data['price'] : null;
                } else {
                    $finalPrice = isset($data['price']) ? (float)$data['price'] : 0.0;
                    $finalCompare = null;
                }
            }
            $variant = ProductVariant::firstOrCreate([
                'sku' => $sku,
            ], [
                'product_id' => $product->id,
                'inventory_quantity' => (int)($v['stock'] ?? 0),
                'price' => $finalPrice,
                'compare_at_price' => $finalCompare,
                'is_active' => (bool)($v['is_active'] ?? true),
            ]);
            $warehouse = $this->defaultWarehouse();
            $desiredQty = (int)($v['stock'] ?? 0);
            $currentQty = (int)($variant->inventory_quantity ?? 0);
            $delta = $desiredQty - $currentQty;
            if ($delta !== 0) {
                $this->adjustVariantInWarehouse($variant->id, $warehouse->id, $delta, 'adjustment', 'product_store', $product->id, 'Sync initial stock for variant');
            }

            foreach ($values as $val) {
                $code = $val['option_code'] ?? $val['code'] ?? null;
                $valueText = $val['value'] ?? null;
                if (!$code || !$valueText) { continue; }
                $attr = ProductAttribute::firstOrCreate(['name' => $code]);
                $attrVal = ProductAttributeValue::firstOrCreate([
                    'attribute_id' => $attr->id,
                    'value' => $valueText,
                    'slug' => Str::slug($valueText),
                ]);
                $pv = ProductVariant::where('sku', $sku)->first();
                if ($pv) {
                    ProductVariantOption::updateOrCreate([
                        'variant_id' => $pv->id,
                        'attribute_id' => $attr->id,
                    ], [
                        'attribute_value_id' => $attrVal->id,
                    ]);
                }
            }

            if (!empty($v['images']) && is_array($v['images'])) {
                foreach ($v['images'] as $url) {
                    if (!is_string($url) || strlen($url) < 5) { continue; }
                    \App\Models\ProductImage::create([
                        'product_id' => $product->id,
                        'product_variant_id' => $variant->id,
                        'url' => $url,
                        'is_primary' => false,
                        'position' => ++$position,
                    ]);
                }
            }
        }

        if (empty($variantsPayload)) {
            $defaultSkuBase = $product->product_sku ?: $productBaseSku;
            $defaultSku = $this->ensureUniqueSku($defaultSkuBase.'-DF');
            $priceDefault = (isset($data['discounted_price']) && $data['discounted_price'] !== '' && (float)$data['discounted_price'] > 0)
                ? (float)$data['discounted_price']
                : ((isset($data['price']) && $data['price'] !== '') ? (float)$data['price'] : 0.0);
            $compareDefault = (isset($data['discounted_price']) && $data['discounted_price'] !== '' && (float)$data['discounted_price'] > 0 && isset($data['price']) && $data['price'] !== '')
                ? (float)$data['price']
                : null;
            $variant = ProductVariant::firstOrCreate([
                'product_id' => $product->id,
                'is_default' => true,
            ], [
                'sku' => $defaultSku,
                'price' => $priceDefault,
                'compare_at_price' => $compareDefault,
                'inventory_quantity' => 0,
                'is_active' => (bool)($data['is_active'] ?? true),
            ]);
            $warehouse = $this->defaultWarehouse();
            $desiredQty = (int)($data['addStock'] ?? 0);
            $currentQty = (int)($variant->inventory_quantity ?? 0);
            $delta = $desiredQty - $currentQty;
            if ($delta !== 0) {
                $this->adjustVariantInWarehouse($variant->id, $warehouse->id, $delta, 'adjustment', 'product_store_default', $product->id, 'Sync default variant stock');
            }
        }

        // Cập nhật trạng thái còn hàng theo tổng tồn kho biến thể hoặc số lượng sản phẩm chính nếu không có biến thể
        $totalQty = \App\Models\ProductVariant::where('product_id', $product->id)->sum('inventory_quantity');
        if ($totalQty <= 0) { $totalQty = (int)($product->quantity ?? 0); }
        $product->update(['in_stock' => $totalQty > 0]);

        return redirect()->route('admin.products')->with('status', 'Tạo sản phẩm thành công');
    }

    public function uploadProductImage(Request $request)
    {
        // Cho phép upload từ file hoặc tải từ URL
        $hasFile = $request->hasFile('file') || $request->hasFile('upload');
        $urlInput = trim((string)$request->input('url', ''));
        if (!$hasFile && !$urlInput) {
            return response()->json(['status' => 'error', 'message' => 'Thiếu file hoặc URL'], 422);
        }

        $datePath = date('Y/m/d');
        $dir = 'products/'.$datePath;

        if ($hasFile) {
            $field = $request->hasFile('upload') ? 'upload' : 'file';
            $request->validate([$field => ['required','image','max:10240']]);
            $file = $request->file($field);
            $originalName = $file->getClientOriginalName();
            $safeName = $this->sanitizeFilename($originalName);
            $sizeBytes = (int)($file->getSize() ?? 0);
            $filename = $this->uniqueFilename($dir, $safeName);
            \Illuminate\Support\Facades\Storage::disk('public')->putFileAs($dir, $file, $filename);
            $path = $dir.'/'.$filename;
            $publicUrl = '/storage/'.$path;
            return response()->json([
                'url' => $publicUrl,
                'name' => $originalName,
                'size' => $sizeBytes,
            ]);
        }

        // Tải ảnh từ URL và lưu, giữ nguyên tên ảnh nếu có
        $parsed = parse_url($urlInput);
        $basename = isset($parsed['path']) ? basename($parsed['path']) : '';
        if (!$basename || strpos($basename, '.') === false) {
            // fallback tên nếu URL không có tên file
            $basename = 'image-'.\Illuminate\Support\Str::random(8).'.jpg';
        }
        $filename = $this->uniqueFilename($dir, $this->sanitizeFilename($basename));
        try {
            $imgData = @file_get_contents($urlInput);
            if ($imgData === false) {
                return response()->json(['status' => 'error', 'message' => 'Tải ảnh từ URL thất bại'], 422);
            }
            $disk = \Illuminate\Support\Facades\Storage::disk('public');
            $disk->put($dir.'/'.$filename, $imgData);
        } catch (\Throwable $e) {
            return response()->json(['status' => 'error', 'message' => 'Không thể lưu ảnh'], 500);
        }
        $savedPath = $dir.'/'.$filename;
        $sizeBytes = (int)(\Illuminate\Support\Facades\Storage::disk('public')->size($savedPath) ?? 0);
        $publicUrl = '/storage/'.$savedPath;
        return response()->json([
            'url' => $publicUrl,
            'name' => $basename,
            'size' => $sizeBytes,
        ]);
    }

    private function uniqueFilename(string $dir, string $filename): string
    {
        $disk = \Illuminate\Support\Facades\Storage::disk('public');
        $name = pathinfo($filename, PATHINFO_FILENAME);
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $candidate = $filename;
        $i = 1;
        while ($disk->exists($dir.'/'.$candidate)) {
            $candidate = $name.'-'.$i++.($ext ? '.'.$ext : '');
        }
        return $candidate;
    }
    private function sanitizeFilename(string $name): string
    {
        $ext = pathinfo($name, PATHINFO_EXTENSION);
        $base = pathinfo($name, PATHINFO_FILENAME);
        $base = \Illuminate\Support\Str::ascii($base);
        $base = preg_replace('/[^A-Za-z0-9._-]+/', '-', $base ?? '');
        $base = trim(preg_replace('/-+/', '-', $base), '-');
        $ext = $ext ? strtolower($ext) : '';
        return $ext ? ($base.'.'.$ext) : $base;
    }

    public function categoryStore(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'slug' => ['nullable','string','max:255','unique:categories,slug'],
            'meta_title' => ['nullable','string','max:255'],
            'meta_description' => ['nullable','string','max:500'],
            'parent_id' => ['nullable','integer','exists:categories,id'],
            'sort_order' => ['nullable','integer','min:0'],
            'is_active' => ['nullable','boolean'],
            'primary_image_url' => ['nullable','string','max:1000'],
        ]);

        $slug = $data['slug'] ?? Str::slug($data['name']);
        $category = Category::create([
            'name' => $data['name'],
            'slug' => $slug,
            'parent_id' => $data['parent_id'] ?? null,
            'sort_order' => $data['sort_order'] ?? 0,
            'is_active' => (bool)($data['is_active'] ?? true),
        ]);
        try {
            if (\Illuminate\Support\Facades\Schema::hasColumn('categories','meta_title')) {
                $category->meta_title = $data['meta_title'] ?? null;
            }
            if (\Illuminate\Support\Facades\Schema::hasColumn('categories','meta_description')) {
                $category->meta_description = $data['meta_description'] ?? null;
            }
            if (\Illuminate\Support\Facades\Schema::hasColumn('categories','image_url')) {
                $img = $data['primary_image_url'] ?? null;
                if ($img) {
                    $category->image_url = $img;
                }
            }
            $category->save();
        } catch (\Throwable $e) {}

        return redirect()->route('admin.products.categories')->with('status', 'Tạo danh mục thành công');
    }

    public function categoryUpdate(Request $request, $id)
    {
        $category = Category::findOrFail((int)$id);
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'slug' => ['nullable','string','max:255','unique:categories,slug,'.$category->id],
            'meta_title' => ['nullable','string','max:255'],
            'meta_description' => ['nullable','string','max:500'],
            'parent_id' => ['nullable','integer','exists:categories,id'],
            'sort_order' => ['nullable','integer','min:0'],
            'is_active' => ['nullable','boolean'],
            'primary_image_url' => ['nullable','string','max:1000'],
        ]);
        $slug = $data['slug'] ?? Str::slug($data['name']);
        $category->update([
            'name' => $data['name'],
            'slug' => $slug,
            'parent_id' => $data['parent_id'] ?? null,
            'sort_order' => $data['sort_order'] ?? 0,
            'is_active' => (bool)($data['is_active'] ?? true),
        ]);
        try {
            if (\Illuminate\Support\Facades\Schema::hasColumn('categories','meta_title')) {
                $category->meta_title = $data['meta_title'] ?? null;
            }
            if (\Illuminate\Support\Facades\Schema::hasColumn('categories','meta_description')) {
                $category->meta_description = $data['meta_description'] ?? null;
            }
            if (\Illuminate\Support\Facades\Schema::hasColumn('categories','image_url')) {
                $category->image_url = $data['primary_image_url'] ?? null;
            }
            $category->save();
        } catch (\Throwable $e) {}
        return redirect()->route('admin.products.categories')->with('status', 'Cập nhật danh mục thành công');
    }

    public function categoryDestroy($id)
    {
        $category = Category::findOrFail((int)$id);
        $category->delete();
        return redirect()->route('admin.products.categories')->with('status', 'Đã xóa danh mục');
    }

    public function uploadCategoryImage(Request $request)
    {
        $hasFile = $request->hasFile('file') || $request->hasFile('upload');
        $urlInput = trim((string)$request->input('url', ''));
        if (!$hasFile && !$urlInput) {
            return response()->json(['status' => 'error', 'message' => 'Thiếu file hoặc URL'], 422);
        }
        $datePath = date('Y/m/d');
        $dir = 'categories/'.$datePath;
        if ($hasFile) {
            $field = $request->hasFile('upload') ? 'upload' : 'file';
            $request->validate([$field => ['required','image','max:10240']]);
            $file = $request->file($field);
            $originalName = $file->getClientOriginalName();
            $safeName = $this->sanitizeFilename($originalName);
            $sizeBytes = (int)($file->getSize() ?? 0);
            $filename = $this->uniqueFilename($dir, $safeName);
            \Illuminate\Support\Facades\Storage::disk('public')->putFileAs($dir, $file, $filename);
            $path = $dir.'/'.$filename;
            $publicUrl = '/storage/'.$path;
            return response()->json([
                'url' => $publicUrl,
                'name' => $originalName,
                'size' => $sizeBytes,
            ]);
        }
        $parsed = parse_url($urlInput);
        $basename = isset($parsed['path']) ? basename($parsed['path']) : '';
        if (!$basename || strpos($basename, '.') === false) {
            $basename = 'image_'.time().'.jpg';
        }
        $filename = $this->uniqueFilename($dir, $this->sanitizeFilename($basename));
        try {
            $img = file_get_contents($urlInput);
        } catch (\Throwable $e) {
            return response()->json(['status' => 'error', 'message' => 'Tải URL thất bại'], 422);
        }
        \Illuminate\Support\Facades\Storage::disk('public')->put($dir.'/'.$filename, $img);
        $sizeBytes = strlen($img);
        $publicUrl = rtrim($request->getSchemeAndHttpHost().$request->getBaseUrl(), '/').'/storage/'.$dir.'/'.$filename;
        return response()->json([
            'url' => $publicUrl,
            'name' => $basename,
            'size' => $sizeBytes,
        ]);
    }

    public function collectionStore(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'slug' => ['nullable','string','max:255','unique:collections,slug'],
            'meta_title' => ['nullable','string','max:255'],
            'meta_description' => ['nullable','string','max:500'],
            'sort_order' => ['nullable','integer','min:0'],
            'is_active' => ['nullable','boolean'],
            'primary_image_url' => ['nullable','string','max:1000'],
        ]);

        $slug = $data['slug'] ?? Str::slug($data['name']);
        $collection = \App\Models\Collection::create([
            'name' => $data['name'],
            'slug' => $slug,
            'meta_title' => $data['meta_title'] ?? null,
            'meta_description' => $data['meta_description'] ?? null,
            'image_url' => $data['primary_image_url'] ?? null,
            'sort_order' => $data['sort_order'] ?? 0,
            'is_active' => (bool)($data['is_active'] ?? true),
        ]);
        if (!empty($data['primary_image_url'])) {
            \App\Models\CollectionImage::create([
                'collection_id' => $collection->id,
                'url' => $data['primary_image_url'],
                'is_primary' => true,
                'position' => 1,
            ]);
        }

        return redirect()->route('admin.products.collections')->with('status', 'Tạo bộ sưu tập thành công');
    }

    public function collectionUpdate(Request $request, $id)
    {
        $collection = \App\Models\Collection::findOrFail((int)$id);
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'slug' => ['nullable','string','max:255','unique:collections,slug,'. $collection->id],
            'meta_title' => ['nullable','string','max:255'],
            'meta_description' => ['nullable','string','max:500'],
            'sort_order' => ['nullable','integer','min:0'],
            'is_active' => ['nullable','boolean'],
            'primary_image_url' => ['nullable','string','max:1000'],
        ]);
        $slug = $data['slug'] ?? Str::slug($data['name']);
        $collection->update([
            'name' => $data['name'],
            'slug' => $slug,
            'meta_title' => $data['meta_title'] ?? null,
            'meta_description' => $data['meta_description'] ?? null,
            'image_url' => $data['primary_image_url'] ?? null,
            'sort_order' => $data['sort_order'] ?? 0,
            'is_active' => (bool)($data['is_active'] ?? true),
        ]);
        // Update single primary image
        \App\Models\CollectionImage::where('collection_id', $collection->id)->delete();
        if (!empty($data['primary_image_url'])) {
            \App\Models\CollectionImage::create([
                'collection_id' => $collection->id,
                'url' => $data['primary_image_url'],
                'is_primary' => true,
                'position' => 1,
            ]);
        }
        return redirect()->route('admin.products.collections')->with('status', 'Cập nhật bộ sưu tập thành công');
    }

    public function collectionDestroy($id)
    {
        $collection = \App\Models\Collection::findOrFail((int)$id);
        $collection->delete();
        return redirect()->route('admin.products.collections')->with('status', 'Đã xóa bộ sưu tập');
    }

    public function uploadCollectionImage(Request $request)
    {
        $hasFile = $request->hasFile('file') || $request->hasFile('upload');
        $urlInput = trim((string)$request->input('url', ''));
        if (!$hasFile && !$urlInput) {
            return response()->json(['status' => 'error', 'message' => 'Thiếu file hoặc URL'], 422);
        }
        $datePath = date('Y/m/d');
        $dir = 'collections/'.$datePath;
        if ($hasFile) {
            $field = $request->hasFile('upload') ? 'upload' : 'file';
            $request->validate([$field => ['required','image','max:10240']]);
            $file = $request->file($field);
            $originalName = $file->getClientOriginalName();
            $safeName = $this->sanitizeFilename($originalName);
            $sizeBytes = (int)($file->getSize() ?? 0);
            $filename = $this->uniqueFilename($dir, $safeName);
            \Illuminate\Support\Facades\Storage::disk('public')->putFileAs($dir, $file, $filename);
            $path = $dir.'/'.$filename;
            $publicUrl = '/storage/'.$path;
            return response()->json([
                'url' => $publicUrl,
                'name' => $originalName,
                'size' => $sizeBytes,
            ]);
        }
        $parsed = parse_url($urlInput);
        $basename = isset($parsed['path']) ? basename($parsed['path']) : '';
        if (!$basename || strpos($basename, '.') === false) {
            $basename = 'image_'.time().'.jpg';
        }
        $filename = $this->uniqueFilename($dir, $this->sanitizeFilename($basename));
        try {
            $img = file_get_contents($urlInput);
        } catch (\Throwable $e) {
            return response()->json(['status' => 'error', 'message' => 'Tải URL thất bại'], 422);
        }
        \Illuminate\Support\Facades\Storage::disk('public')->put($dir.'/'.$filename, $img);
        $sizeBytes = strlen($img);
        $publicUrl = '/storage/'.$dir.'/'.$filename;
        return response()->json([
            'url' => $publicUrl,
            'name' => $basename,
            'size' => $sizeBytes,
        ]);
    }

    private function applyOrderFilters($query, Request $request)
    {
        // Filter by Status
        $status = trim((string)$request->query('status', ''));
        $allowed = ['new','processing','shipped','completed','cancelled','failed'];
        if ($status !== '' && in_array($status, $allowed, true)) {
            $query->where('status', $status);
        }

        // Filter by Search (ID, Customer Name, Email, Shipping Name)
        $search = trim((string)$request->query('search', ''));
        if ($search !== '') {
            $query->where(function($q) use ($search) {
                $q->where('id', $search)
                  ->orWhere('shipping_name', 'like', "%{$search}%")
                  ->orWhere('shipping_email', 'like', "%{$search}%")
                  ->orWhere('shipping_phone', 'like', "%{$search}%")
                  ->orWhereHas('user', function($u) use ($search) {
                      $u->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by Date Range
        $dateFrom = trim((string)$request->query('date_from', ''));
        $dateTo = trim((string)$request->query('date_to', ''));
        
        if ($dateFrom !== '') {
            $query->whereDate('created_at', '>=', $dateFrom);
        }
        if ($dateTo !== '') {
            $query->whereDate('created_at', '<=', $dateTo);
        }
        
        return $query;
    }

    public function orders(Request $request)
    {
        $query = Order::with('user')->latest();
        
        $this->applyOrderFilters($query, $request);

        $orders = $query->paginate(10)->appends($request->query());
        $uiOrders = $orders->map(function($o){
            $name = optional($o->user)->name ?? ('User #'.$o->user_id);
            $parts = preg_split('/\s+/', trim($name));
            $initials = '';
            foreach ($parts as $i => $p) {
                if ($i >= 2) break;
                $initials .= strtoupper(substr($p, 0, 1));
            }
            $payment = ($o->status === 'completed') ? 'Paid' : (($o->status === 'failed') ? 'Failed' : (($o->status === 'cancelled') ? 'Cancelled' : 'Pending'));
            $delivery = (string)$o->status;
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
            'pending' => Order::where('status', 'new')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'shipped' => Order::where('status', 'shipped')->count(),
            'completed' => Order::where('status', 'completed')->count(),
            'refunded' => Order::where('status', 'cancelled')->count(),
            'failed' => Order::where('status', 'failed')->count(),
        ];
        return view('admin.orders.index', compact('orders','uiOrders','stats'));
    }

    public function exportOrders(Request $request)
    {
        $query = Order::with(['user', 'items.productVariant.product'])->latest();
        $this->applyOrderFilters($query, $request);
        $orders = $query->get();

        $filename = "orders_export_" . date('Y-m-d_H-i-s') . ".csv";
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        return response()->stream(function () use ($orders) {
            $handle = fopen('php://output', 'w');
            // Add BOM for Excel
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Header
            fputcsv($handle, [
                'Order ID', 'Date', 'Customer Name', 'Email', 'Phone', 
                'Status', 'Payment Status', 'Payment Method', 
                'Subtotal', 'Shipping', 'Discount', 'Total', 
                'Address', 'City', 'Items (Count)'
            ]);

            foreach ($orders as $order) {
                $customerName = $order->shipping_name ?? optional($order->user)->name ?? 'Guest';
                $customerEmail = $order->shipping_email ?? optional($order->user)->email ?? '';
                $customerPhone = $order->shipping_phone ?? optional($order->user)->phone ?? '';
                
                fputcsv($handle, [
                    $order->id,
                    $order->created_at->format('Y-m-d H:i:s'),
                    $customerName,
                    $customerEmail,
                    $customerPhone,
                    $order->status,
                    $order->payment_status,
                    $order->payment_method,
                    number_format($order->subtotal, 0, '.', ''),
                    number_format($order->shipping_cost, 0, '.', ''),
                    number_format($order->discount_amount, 0, '.', ''),
                    number_format($order->total, 0, '.', ''),
                    $order->shipping_address,
                    $order->shipping_city,
                    $order->items->count()
                ]);
            }
            fclose($handle);
        }, 200, $headers);
    }

    public function orderInvoice($id)
    {
        $order = Order::with(['items.productVariant.product', 'user'])->findOrFail($id);
        return view('admin.orders.invoice', compact('order'));
    }

    public function orderShow($id)
    {
        $order = Order::with(['items.productVariant.product.images', 'items.productVariant.options.attribute', 'items.productVariant.options.attributeValue', 'user'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    public function getOrderDetailHtml(int $orderId)
    {
        $order = Order::with(['items.productVariant.product.images', 'items.productVariant.options.attribute', 'items.productVariant.options.attributeValue', 'user'])->findOrFail($orderId);
        return view('admin.orders._show_modal', compact('order'))->render();
    }

    public function orderUpdate(Request $request, $id)
    {
        $order = Order::with('items')->findOrFail($id);
        $oldStatus = (string)($order->status ?? '');
        $oldPayment = (string)($order->payment_status ?? '');
        
        // Basic Validation
        $data = $request->validate([
            'status' => 'sometimes|string',
            'payment_status' => 'sometimes|string',
            'payment_method' => 'sometimes|string',
            'shipping_name' => 'sometimes|string',
            'shipping_phone' => 'sometimes|string',
            'shipping_email' => 'sometimes|email',
            'shipping_address' => 'sometimes|string',
            'shipping_province' => 'nullable|string',
            'shipping_district' => 'nullable|string',
            'shipping_ward' => 'nullable|string',
            'items' => 'sometimes|array',
            'items.*.id' => 'required|integer',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'removed_items' => 'sometimes|array',
        ]);

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            // Update basic info
            $order->fill($request->only([
                'status', 'payment_status', 'payment_method',
                'shipping_name', 'shipping_phone', 'shipping_email',
                'shipping_address', 'shipping_province', 'shipping_ward'
            ]));

            $warehouse = $this->defaultWarehouse();
            $isReservedState = in_array($oldStatus, ['new', 'processing'], true);
            $isShippedState = in_array($oldStatus, ['completed', 'shipped', 'delivered'], true);

            // Handle removed items
            if ($request->has('removed_items') && is_array($request->removed_items)) {
                $removedItems = \App\Models\OrderItem::whereIn('id', $request->removed_items)->where('order_id', $order->id)->get();
                foreach ($removedItems as $item) {
                    if ($isReservedState) {
                        $this->adjustVariantInWarehouse((int)$item->product_variant_id, $warehouse->id, (int)$item->quantity, 'release', 'order_update_remove', $order->id, 'Item removed from order');
                    } elseif ($isShippedState) {
                        $this->adjustVariantInWarehouse((int)$item->product_variant_id, $warehouse->id, (int)$item->quantity, 'receipt', 'order_update_remove', $order->id, 'Item removed from order');
                    }
                }
                \App\Models\OrderItem::whereIn('id', $request->removed_items)->where('order_id', $order->id)->delete();
            }

            // Handle updated items
            if ($request->has('items') && is_array($request->items)) {
                foreach ($request->items as $itemData) {
                    $item = $order->items->where('id', $itemData['id'])->first();
                    if ($item) {
                        $oldQty = (int)$item->quantity;
                        $newQty = (int)$itemData['quantity'];
                        $delta = $newQty - $oldQty;

                        if ($delta != 0) {
                            if ($isReservedState) {
                                if ($delta > 0) {
                                    $this->adjustVariantInWarehouse((int)$item->product_variant_id, $warehouse->id, $delta, 'reservation', 'order_update_qty', $order->id, 'Quantity increased');
                                } else {
                                    $this->adjustVariantInWarehouse((int)$item->product_variant_id, $warehouse->id, abs($delta), 'release', 'order_update_qty', $order->id, 'Quantity decreased');
                                }
                            } elseif ($isShippedState) {
                                if ($delta > 0) {
                                    $this->adjustVariantInWarehouse((int)$item->product_variant_id, $warehouse->id, $delta, 'shipment', 'order_update_qty', $order->id, 'Quantity increased');
                                } else {
                                    $this->adjustVariantInWarehouse((int)$item->product_variant_id, $warehouse->id, abs($delta), 'receipt', 'order_update_qty', $order->id, 'Quantity decreased');
                                }
                            }
                        }

                        $item->quantity = $itemData['quantity'];
                        $item->unit_price = $itemData['unit_price'];
                        $item->save();
                    }
                }
            }

            // Recalculate Totals
            $order->load('items'); // Reload items to get updated quantities/prices and exclude deleted ones
            $subtotal = 0;
            foreach($order->items as $item) {
                $subtotal += $item->quantity * $item->unit_price;
            }

            $order->subtotal = $subtotal;
            // Assuming tax/shipping are fixed or manually adjustable? 
            // For now, keep tax/shipping as is, but recalculate total.
            // If user wanted to edit shipping cost, we should have added it to the form.
            // Let's assume shipping cost is fixed for now unless user asked.
            // User asked: "chi tiết đơn hàng : từ số lượng đến giá , xóa item,..."
            // "Địa chỉ giao hàng", "phương thức thanh toán", "trạng thái"
            
            $discount = $order->discount_amount ?? 0;
            $shipping = $order->shipping_cost ?? 0;
            $tax = $order->tax ?? 0;
            
            $order->total = $subtotal + $shipping + $tax - $discount;
            $order->save();

            $this->syncOrderInventoryState($order, $oldStatus, $oldPayment);

            \Illuminate\Support\Facades\DB::commit();
            return response()->json(['success' => true, 'message' => 'Order updated successfully']);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function orderDestroy($id)
    {
        $order = Order::with('items')->findOrFail($id);
        
        // Restore stock if applicable
        $warehouse = $this->defaultWarehouse();
        $status = (string)$order->status;
        $isReserved = in_array($status, ['new', 'processing'], true);
        $isShipped = in_array($status, ['completed', 'shipped', 'delivered'], true);

        if ($isReserved || $isShipped) {
            foreach ($order->items as $item) {
                if ($isReserved) {
                    // Release reservation
                    $this->adjustVariantInWarehouse(
                        (int)$item->product_variant_id, 
                        $warehouse->id, 
                        (int)$item->quantity, 
                        'release', 
                        'order_deletion', 
                        (int)$order->id, 
                        'Release stock on order deletion'
                    );
                } elseif ($isShipped) {
                    // Return to stock (Receipt)
                    $this->adjustVariantInWarehouse(
                        (int)$item->product_variant_id, 
                        $warehouse->id, 
                        (int)$item->quantity, 
                        'receipt', 
                        'order_deletion', 
                        (int)$order->id, 
                        'Return stock on order deletion'
                    );
                }
            }
        }

        $order->items()->delete(); 
        $order->delete();
        return response()->json(['success' => true, 'message' => 'Order deleted successfully']);
    }


    public function users()
    {
        $users = User::latest()->paginate(12);
        $stats = [
            'total' => \App\Models\User::count(),
            'verified' => \App\Models\User::whereNotNull('email_verified_at')->count(),
            'unverified' => \App\Models\User::whereNull('email_verified_at')->count(),
            'admins' => \App\Models\User::whereIn('role', ['admin','owner'])->count(),
        ];
        return view('admin.users.index', compact('users','stats'));
    }
    public function customers(Request $request)
    {
        $q = trim((string)$request->input('q'));
        $query = User::query()->where('role', 'customer');
        if ($q !== '') {
            $query->where(function($x) use ($q){
                $x->where('name','like','%'.$q.'%')
                  ->orWhere('email','like','%'.$q.'%')
                  ->orWhere('id', (int)$q);
            });
        }
        $limit = $request->input('limit', 10);
        $customers = $query->latest()->paginate($limit)->appends($request->query());
        $ui = $customers->map(function($u){
            $orders = \App\Models\Order::where('user_id', $u->id)->count();
            $spent = (float)\App\Models\Order::where('user_id', $u->id)->sum('total');
            $addr = \App\Models\Address::where('user_id',$u->id)->where('is_default',true)->first();
            $city = $addr->city ?? '';
            $name = (string)($u->name ?? '');
            $parts = preg_split('/\s+/', trim($name));
            $initials = '';
            foreach ($parts as $i => $p) { if ($i >= 2) break; $initials .= strtoupper(mb_substr($p,0,1)); }
            return [
                'id' => '#'.str_pad((string)$u->id, 6, '0', STR_PAD_LEFT),
                'user_id' => (int)$u->id,
                'name' => $name !== '' ? $name : ('User #'.$u->id),
                'email' => (string)($u->email ?? ''),
                'city' => $city,
                'orders' => (int)$orders,
                'spent' => $spent,
                'initials' => $initials !== '' ? $initials : 'U',
            ];
        })->values()->all();
        $cities = \App\Models\Address::whereNotNull('city')->pluck('city')->unique()->values()->all();
        return view('admin.customers.index', [
            'customers' => $customers,
            'uiCustomers' => $ui,
            'cities' => $cities,
        ]);
    }
    public function userShow($id)
    {
        $user = User::with(['orders' => function($q) {
            $q->latest();
        }, 'addresses'])->findOrFail((int)$id);

        $stats = [
            'orders_count' => $user->orders->count(),
            'total_spent' => $user->orders->sum('total'),
        ];

        $address = $user->addresses->where('is_default', true)->first() ?? $user->addresses->first();

        return view('admin.customers.show', compact('user', 'stats', 'address'));
    }




    public function userStore(Request $request)
    {
        $actorRole = strtolower((string)(auth()->user()->role ?? ''));
        if (!in_array($actorRole, ['admin','manager'], true)) {
            abort(403);
        }
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'email' => ['required','email','max:255','unique:users,email'],
            'role' => ['nullable','string','in:owner,admin,manager,editor,warehouse,support,customer'],
        ]);
        $password = \Illuminate\Support\Str::random(12);
        $user = \App\Models\User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($password),
            'role' => $data['role'] ?? 'customer',
        ]);
        return redirect()->route('admin.users')->with('status','Tạo người dùng thành công');
    }
    public function userUpdate(Request $request, $id)
    {
        $u = \App\Models\User::findOrFail((int)$id);
        $actorRole = strtolower((string)(auth()->user()->role ?? ''));
        $canChangeRole = in_array($actorRole, ['admin','manager'], true);
        $rules = [
            'name' => ['required','string','max:255'],
            'email' => ['required','email','max:255','unique:users,email,'.$u->id],
        ];
        if ($canChangeRole) {
            $rules['role'] = ['nullable','string','in:owner,admin,manager,editor,warehouse,support,customer'];
        }
        $rules['password'] = ['nullable','string','min:6','confirmed'];
        $data = $request->validate($rules);
        $update = [
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $canChangeRole ? ($data['role'] ?? ($u->role ?? 'customer')) : ($u->role ?? 'customer'),
        ];
        if (!empty($data['password'] ?? null)) {
            $update['password'] = (string)$data['password'];
        }
        $u->update($update);
        if ((string)($u->role ?? 'customer') === 'customer') {
            return redirect()->route('admin.customers.show', $u->id)->with('status','Đã cập nhật khách hàng');
        }
        return redirect()->route('admin.users.show', $u->id)->with('status','Cập nhật người dùng thành công');
    }
    public function userDestroy($id)
    {
        $u = \App\Models\User::findOrFail((int)$id);
        if (auth()->check() && auth()->id() === $u->id) {
            return redirect()->route('admin.users')->withErrors(['user' => 'Không thể xóa chính bạn']);
        }
        $isCustomer = ($u->role ?? 'customer') === 'customer';
        $u->delete();
        if ($isCustomer) {
            return redirect()->route('admin.customers')->with('status','Đã xóa khách hàng');
        }
        return redirect()->route('admin.users')->with('status','Đã xóa người dùng');
    }

    public function customerAddressStore(Request $request, $id)
    {
        $user = \App\Models\User::findOrFail((int)$id);
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'phone' => ['nullable','string','max:50'],
            'address_line' => ['required','string','max:255'],
            'ward' => ['nullable','string','max:255'],
            'city' => ['nullable','string','max:255'],
            'is_default' => ['nullable','boolean'],
        ]);
        $addr = new \App\Models\Address();
        $addr->user_id = $user->id;
        $addr->name = $data['name'];
        $addr->phone = $data['phone'] ?? null;
        $addr->address_line = $data['address_line'];
        $addr->ward = $data['ward'] ?? null;
        $addr->city = $data['city'] ?? null;
        $addr->is_default = (bool)($data['is_default'] ?? false);
        if ($addr->is_default) {
            \App\Models\Address::where('user_id', $user->id)->update(['is_default' => false]);
        }
        $addr->save();
        return redirect()->route('admin.customers.show', $user->id)->with('status','Đã thêm địa chỉ');
    }

    public function customerAddressUpdate(Request $request, $id, $addressId)
    {
        $user = \App\Models\User::findOrFail((int)$id);
        $addr = \App\Models\Address::where('user_id', $user->id)->findOrFail((int)$addressId);
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'phone' => ['nullable','string','max:50'],
            'address_line' => ['required','string','max:255'],
            'ward' => ['nullable','string','max:255'],
            'district' => ['nullable','string','max:255'],
            'city' => ['nullable','string','max:255'],
            'is_default' => ['nullable','boolean'],
        ]);
        $update = [
            'name' => $data['name'],
            'phone' => $data['phone'] ?? null,
            'address_line' => $data['address_line'],
            'ward' => $data['ward'] ?? null,
            'district' => $data['district'] ?? null,
            'city' => $data['city'] ?? null,
        ];
        $isDefault = (bool)($data['is_default'] ?? false);
        if ($isDefault) {
            \App\Models\Address::where('user_id', $user->id)->update(['is_default' => false]);
            $update['is_default'] = true;
        }
        $addr->update($update);
        return redirect()->route('admin.customers.show', $user->id)->with('status','Đã cập nhật địa chỉ');
    }

    public function customerAddressDefault($id, $addressId)
    {
        $user = \App\Models\User::findOrFail((int)$id);
        $addr = \App\Models\Address::where('user_id', $user->id)->findOrFail((int)$addressId);
        \App\Models\Address::where('user_id', $user->id)->update(['is_default' => false]);
        $addr->is_default = true;
        $addr->save();
        return redirect()->route('admin.customers.show', $user->id)->with('status','Đã đặt địa chỉ mặc định');
    }

    public function customerAddressDestroy($id, $addressId)
    {
        $user = \App\Models\User::findOrFail((int)$id);
        $addr = \App\Models\Address::where('user_id', $user->id)->findOrFail((int)$addressId);
        $addr->delete();
        return redirect()->route('admin.customers.show', $user->id)->with('status','Đã xóa địa chỉ');
    }
    public function userVerifyEmail($id)
    {
        $u = \App\Models\User::findOrFail((int)$id);
        $u->update(['email_verified_at' => now()]);
        return redirect()->route('admin.users')->with('status','Đã xác thực email người dùng');
    }
    public function userToggleRole($id)
    {
        $actorRole = strtolower((string)(auth()->user()->role ?? ''));
        if (!in_array($actorRole, ['admin','manager'], true)) {
            abort(403);
        }
        $u = \App\Models\User::findOrFail((int)$id);
        if (auth()->check() && auth()->id() === $u->id) {
            return redirect()->route('admin.users')->withErrors(['user' => 'Không thể đổi vai trò chính bạn']);
        }
        $current = strtolower((string)($u->role ?? 'customer'));
        $next = $current === 'admin' ? 'customer' : 'admin';
        $u->update(['role' => $next]);
        return redirect()->route('admin.users')->with('status','Đã đổi vai trò người dùng');
    }

    private function defaultWarehouse(): ?Warehouse
    {
        $w = Warehouse::where('code', 'MAIN')->first();
        if ($w) return $w;
        $w = Warehouse::create(['name' => 'Main Warehouse', 'code' => 'MAIN', 'address' => '', 'is_active' => true]);
        return $w;
    }
    private function adjustVariantInWarehouse(int $variantId, int $warehouseId, int $delta, string $type, ?string $refType = null, ?int $refId = null, ?string $notes = null): void
    {
        $inv = WarehouseInventory::firstOrCreate(['warehouse_id' => $warehouseId, 'product_variant_id' => $variantId], ['on_hand' => 0, 'reserved' => 0, 'incoming' => 0]);
        if ($type === 'receipt' || $type === 'adjustment') {
            $inv->on_hand = max(0, (int)$inv->on_hand + (int)$delta);
        } elseif ($type === 'shipment') {
            $inv->on_hand = max(0, (int)$inv->on_hand - (int)$delta);
            $inv->reserved = max(0, (int)$inv->reserved - (int)$delta);
        } elseif ($type === 'reservation') {
            $inv->reserved = max(0, (int)$inv->reserved + (int)$delta);
        } elseif ($type === 'release') {
            $inv->reserved = max(0, (int)$inv->reserved - (int)$delta);
        }
        $inv->save();
        StockMovement::create([
            'movement_type' => $type,
            'warehouse_id' => $warehouseId,
            'product_variant_id' => $variantId,
            'quantity' => $delta,
            'ref_type' => $refType,
            'ref_id' => $refId,
            'notes' => $notes,
        ]);
        $sumAvailable = max(0, (int)$inv->on_hand - (int)$inv->reserved);
        ProductVariant::where('id', $variantId)->update(['inventory_quantity' => $sumAvailable]);
    }
    private function syncOrderInventoryState(\App\Models\Order $order, ?string $oldStatus, ?string $oldPayment): void
    {
        $warehouse = $this->defaultWarehouse();
        $items = $order->items()->get();
        $newStatus = (string)$order->status;
        $fulfilledStatuses = ['completed','shipped','delivered'];
        $isFulfilled = in_array($newStatus, $fulfilledStatuses, true);
        $wasFulfilled = in_array((string)$oldStatus, $fulfilledStatuses, true);
        $alreadyShipped = \App\Models\StockMovement::where('ref_type', 'order')->where('ref_id', $order->id)->where('movement_type', 'shipment')->exists();
        $alreadyReserved = \App\Models\StockMovement::where('ref_type', 'order')->where('ref_id', $order->id)->where('movement_type', 'reservation')->exists();
        if ($isFulfilled && !$alreadyShipped) {
            foreach ($items as $item) {
                $this->adjustVariantInWarehouse((int)$item->product_variant_id, $warehouse->id, (int)$item->quantity, 'shipment', 'order', (int)$order->id, null);
            }
        }
        $isCancelled = in_array($newStatus, ['cancelled','failed'], true);
        if ($isCancelled || ($wasFulfilled && !$isFulfilled)) {
            if (\App\Models\StockMovement::where('ref_type', 'order')->where('ref_id', $order->id)->where('movement_type', 'shipment')->exists()) {
                foreach ($items as $item) {
                    $this->adjustVariantInWarehouse((int)$item->product_variant_id, $warehouse->id, (int)$item->quantity, 'receipt', 'order', (int)$order->id, null);
                }
            } elseif ($alreadyReserved) {
                foreach ($items as $item) {
                    $this->adjustVariantInWarehouse((int)$item->product_variant_id, $warehouse->id, (int)$item->quantity, 'release', 'order', (int)$order->id, null);
                }
            }
        }
        if (in_array($newStatus, ['new','processing'], true) && !$alreadyReserved) {
            foreach ($items as $item) {
                $this->adjustVariantInWarehouse((int)$item->product_variant_id, $warehouse->id, (int)$item->quantity, 'reservation', 'order', (int)$order->id, null);
            }
        }
    }
    public function warehouses()
    {
        $warehouses = Warehouse::orderBy('name')->paginate(10);
        return view('admin.warehouses.index', compact('warehouses'));
    }
    public function warehouseStore(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'code' => ['required','string','max:50','alpha_dash','unique:warehouses,code'],
            'address' => ['nullable','string','max:1000'],
            'is_active' => ['nullable','boolean'],
        ]);
        Warehouse::create([
            'name' => $data['name'],
            'code' => strtoupper($data['code']),
            'address' => $data['address'] ?? null,
            'is_active' => (bool)($data['is_active'] ?? true),
        ]);
        return redirect()->route('admin.warehouses')->with('status', 'Tạo kho thành công');
    }
    public function warehouseUpdate(Request $request, $id)
    {
        $w = Warehouse::findOrFail((int)$id);
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'code' => ['required','string','max:50','alpha_dash','unique:warehouses,code,'.$w->id],
            'address' => ['nullable','string','max:1000'],
            'is_active' => ['nullable','boolean'],
        ]);
        $w->update([
            'name' => $data['name'],
            'code' => strtoupper($data['code']),
            'address' => $data['address'] ?? null,
            'is_active' => (bool)($data['is_active'] ?? true),
        ]);
        return redirect()->route('admin.warehouses')->with('status', 'Cập nhật kho thành công');
    }
    public function warehouseDestroy($id)
    {
        $w = Warehouse::findOrFail((int)$id);
        if (strtoupper((string)$w->code) === 'MAIN') {
            return redirect()->route('admin.warehouses')->withErrors(['warehouse' => 'Không thể xóa kho MAIN. Vui lòng ngưng hoạt động nếu cần.']);
        }
        $hasInv = WarehouseInventory::where('warehouse_id', $w->id)->exists();
        $hasMv = StockMovement::where('warehouse_id', $w->id)->exists();
        if ($hasInv || $hasMv) {
            return redirect()->route('admin.warehouses')->withErrors(['warehouse' => 'Kho còn dữ liệu tồn kho hoặc biến động. Vui lòng chuyển/xóa dữ liệu trước khi xóa kho.']);
        }
        $w->delete();
        return redirect()->route('admin.warehouses')->with('status', 'Đã xóa kho hàng');
    }
    public function inventory(Request $request)
    {
        $warehouseId = (int)($request->query('warehouse_id') ?? 0);
        $warehouse = $warehouseId ? Warehouse::find($warehouseId) : $this->defaultWarehouse();
        $q = trim((string)$request->query('q', ''));
        $invQuery = WarehouseInventory::with(['variant.product'])->where('warehouse_id', $warehouse->id)->orderBy('id','desc');
        if ($q !== '') {
            $invQuery->whereHas('variant', function($sq) use ($q) {
                $sq->where('sku', 'like', '%'.$q.'%')->orWhereHas('product', function($p) use ($q) { $p->where('name', 'like', '%'.$q.'%'); });
            });
        }
        $inventories = $invQuery->paginate(15)->appends($request->query());
        $warehouses = Warehouse::orderBy('name')->get();
        $inventoriesAll = WarehouseInventory::with(['variant.product','warehouse'])->get();
        $movements = StockMovement::with(['variant.product','warehouse'])->where('warehouse_id', $warehouse->id)->latest()->limit(9)->get();
        return view('admin.inventory.inventory', compact('warehouse','inventories','warehouses','inventoriesAll','movements'));
    }

    public function stockMovementsJson(Request $request)
    {
        $warehouseId = (int)($request->query('warehouse_id') ?? 0);
        $warehouse = $warehouseId ? Warehouse::find($warehouseId) : $this->defaultWarehouse();
        $offset = (int)($request->query('offset') ?? 0);
        $limit = (int)($request->query('limit') ?? 9);
        
        $movements = StockMovement::with(['variant.product','warehouse'])
            ->where('warehouse_id', $warehouse->id)
            ->latest()
            ->skip($offset)
            ->take($limit)
            ->get();
            
        $data = $movements->map(function($m){
           $typeLabels = [
               'receipt' => 'Nhập hàng',
               'shipment' => 'Xuất kho', 
               'adjustment' => 'Điều chỉnh',
               'reservation' => 'Giữ hàng',
               'release' => 'Hủy giữ'
           ];
           $type = $typeLabels[$m->movement_type] ?? $m->movement_type;
           $product = optional($m->variant)->product;
           $reason = $m->notes;
           if (!$reason && $m->ref_type) {
               $reason = ucfirst($m->ref_type) . ($m->ref_id ? ' #' . $m->ref_id : '');
           }
           
           $qty = (int)$m->quantity;
           if ($m->movement_type === 'shipment') {
               $qty = -abs($qty);
           }
    
           return [
             'id' => $m->id,
             'type' => $type,
             'raw_type' => $m->movement_type,
             'quantity' => $qty,
             'time' => optional($m->created_at)->format('H:i:s d/m/Y'),
             'note' => $m->notes,
             'product' => [
                 'name' => optional($product)->name ?? 'Sản phẩm đã xóa',
                 'sku' => optional($m->variant)->sku ?? 'N/A',
                 'variant' => optional($m->variant)->sku, 
                 'user' => 'System',
                 'warehouse' => optional($m->warehouse)->name,
                 'reason' => $reason,
                 'change' => $qty,
             ]
           ];
        })->values()->all();
        
        return response()->json($data);
    }
    public function stockMovements(Request $request)
    {
        $warehouseId = (int)($request->query('warehouse_id') ?? 0);
        $warehouse = $warehouseId ? Warehouse::find($warehouseId) : $this->defaultWarehouse();
        $type = trim((string)$request->query('type', ''));
        $q = trim((string)$request->query('q', ''));
        $mv = StockMovement::with(['variant.product'])->where('warehouse_id', $warehouse->id)->latest();
        if ($type !== '') { $mv->where('movement_type', $type); }
        if ($q !== '') {
            $mv->whereHas('variant', function($sq) use ($q) {
                $sq->where('sku','like','%'.$q.'%')->orWhereHas('product', function($p) use ($q){ $p->where('name','like','%'.$q.'%'); });
            });
        }
        $movements = $mv->paginate(20)->appends($request->query());
        return view('admin.inventory.movements', compact('warehouse','movements'));
    }
    public function inventoryReceipt(Request $request)
    {
        $data = $request->validate([
            'variant_id' => ['required','integer','exists:product_variants,id'],
            'warehouse_id' => ['nullable','integer','exists:warehouses,id'],
            'quantity' => ['required','integer','min:1'],
            'notes' => ['nullable','string','max:1000'],
        ]);
        $warehouse = isset($data['warehouse_id']) ? Warehouse::find($data['warehouse_id']) : $this->defaultWarehouse();
        \DB::transaction(function() use ($data, $warehouse) {
            $this->adjustVariantInWarehouse((int)$data['variant_id'], $warehouse->id, (int)$data['quantity'], 'receipt', 'manual', null, $data['notes'] ?? null);
        });
        return redirect()->route('admin.inventory', ['warehouse_id' => $warehouse->id])->with('status', 'Nhập hàng thành công');
    }
    public function inventoryAdjustment(Request $request)
    {
        $data = $request->validate([
            'variant_id' => ['required','integer','exists:product_variants,id'],
            'warehouse_id' => ['nullable','integer','exists:warehouses,id'],
            'quantity' => ['required','integer'],
            'notes' => ['nullable','string','max:1000'],
        ]);
        $warehouse = isset($data['warehouse_id']) ? Warehouse::find($data['warehouse_id']) : $this->defaultWarehouse();
        \DB::transaction(function() use ($data, $warehouse) {
            $this->adjustVariantInWarehouse((int)$data['variant_id'], $warehouse->id, (int)$data['quantity'], 'adjustment', 'manual', null, $data['notes'] ?? null);
        });
        return redirect()->route('admin.inventory', ['warehouse_id' => $warehouse->id])->with('status', 'Điều chỉnh tồn kho thành công');
    }
    public function inventoryTransfer(Request $request)
    {
        $data = $request->validate([
            'variant_id' => ['required','integer','exists:product_variants,id'],
            'from_warehouse_id' => ['required','integer','exists:warehouses,id'],
            'to_warehouse_id' => ['required','integer','exists:warehouses,id'],
            'quantity' => ['required','integer','min:1'],
            'notes' => ['nullable','string','max:1000'],
        ]);
        \DB::transaction(function() use ($data) {
            $this->adjustVariantInWarehouse((int)$data['variant_id'], (int)$data['from_warehouse_id'], (int)$data['quantity'], 'shipment', 'transfer', null, $data['notes'] ?? null);
            $this->adjustVariantInWarehouse((int)$data['variant_id'], (int)$data['to_warehouse_id'], (int)$data['quantity'], 'receipt', 'transfer', null, $data['notes'] ?? null);
        });
        return redirect()->route('admin.inventory', ['warehouse_id' => (int)$data['to_warehouse_id']])->with('status', 'Chuyển kho thành công');
    }
    public function inventoryVariantsSearch(Request $request)
    {
        $q = trim((string)$request->query('q', ''));
        if ($q === '') {
            return response()->json([]);
        }
        $variants = \App\Models\ProductVariant::with('product')
            ->where('sku', 'like', '%'.$q.'%')
            ->orWhereHas('product', function($p) use ($q){ $p->where('name', 'like', '%'.$q.'%'); })
            ->limit(20)->get();
        $data = $variants->map(function($v){
            return [
                'id' => $v->id,
                'sku' => $v->sku,
                'product_name' => optional($v->product)->name,
                'price' => $v->price,
                'stock' => (int)($v->inventory_quantity ?? 0),
            ];
        })->values()->all();
        return response()->json($data);
    }

    // Proxy Address API (Local Data)
    public function proxyProvinces()
    {
        $path = storage_path('app/json_address/provinces.json');
        if (!file_exists($path)) {
            return response()->json(['error' => 'Data not found'], 404);
        }
        $content = file_get_contents($path);
        return response($content, 200, ['Content-Type' => 'application/json']);
    }

    public function proxyCommunes($code)
    {
        $path = storage_path("app/json_address/communes/{$code}.json");
        if (!file_exists($path)) {
            return response()->json(['error' => 'Data not found'], 404);
        }
        $content = file_get_contents($path);
        return response($content, 200, ['Content-Type' => 'application/json']);
    }

    public function settingsProfile()
    {
        $settings = $this->readSettings();
        $adminLanguage = $settings['admin']['language'] ?? 'vi';
        return view('admin.settings.profile', ['adminLanguage' => $adminLanguage]);
    }

    public function uploadAvatar(Request $request)
    {
        $request->validate([
            'avatar' => ['required', 'image', 'max:5120'], // 5MB
        ]);

        $user = auth()->user();

        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            
            // Store new avatar
            $path = $request->file('avatar')->store('avatars', 'public');
            
            // Update user record
            $user->avatar = $path;
            $user->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Cập nhật ảnh đại diện thành công',
                'url' => Storage::url($path)
            ]);
        }

        return response()->json(['status' => 'error', 'message' => 'Không tìm thấy file'], 400);
    }

    public function uploadSocialImage(Request $request)
    {
        $request->validate([
            'image' => ['required', 'image', 'max:5120'],
        ]);
        $settings = $this->readSettings();
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $original = $file->getClientOriginalName();
            $name = pathinfo($original, PATHINFO_FILENAME);
            $ext = $file->getClientOriginalExtension();
            $filename = \Illuminate\Support\Str::slug($name).'.'.$ext;
            if (\Illuminate\Support\Facades\Storage::disk('public')->exists('social/'.$filename)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete('social/'.$filename);
            }
            $path = $file->storeAs('social', $filename, 'public');
            $url = '/storage/'.$path;
            $settings['store'] = array_merge($settings['store'] ?? [], [
                'social_image' => $url,
            ]);
            $this->writeSettings($settings);
            return response()->json([
                'status' => 'success',
                'message' => 'Cập nhật hình ảnh website thành công',
                'url' => $url,
            ]);
        }
        return response()->json(['status' => 'error', 'message' => 'Không tìm thấy file'], 400);
    }

    public function uploadFavicon(Request $request)
    {
        $request->validate([
            'favicon' => ['required', 'image', 'max:1024'], // 1MB max
        ]);
        $settings = $this->readSettings();
        if ($request->hasFile('favicon')) {
            $file = $request->file('favicon');
            $original = $file->getClientOriginalName();
            $name = pathinfo($original, PATHINFO_FILENAME);
            $ext = $file->getClientOriginalExtension();
            $filename = \Illuminate\Support\Str::slug($name).'.'.$ext;
            if (\Illuminate\Support\Facades\Storage::disk('public')->exists('favicons/'.$filename)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete('favicons/'.$filename);
            }
            $path = $file->storeAs('favicons', $filename, 'public');
            $url = '/storage/'.$path;
            $settings['store'] = array_merge($settings['store'] ?? [], [
                'favicon' => $url,
            ]);
            $this->writeSettings($settings);
            return response()->json([
                'status' => 'success',
                'message' => 'Cập nhật favicon thành công',
                'url' => $url,
            ]);
        }
        return response()->json(['status' => 'error', 'message' => 'Không tìm thấy file'], 400);
    }

    public function settingsProfileSave(Request $request)
    {
        $user = auth()->user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', \Illuminate\Validation\Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'avatar' => ['nullable', 'image', 'max:5120'], // 5MB
            'current_password' => ['nullable', 'required_with:new_password', 'current_password'],
            'new_password' => ['nullable', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
            'two_factor_enabled' => ['nullable', 'boolean'],
            'language' => ['nullable', 'in:vi,en,ja'],
        ]);

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->phone = $data['phone'] ?? null;
        $user->bio = $data['bio'] ?? null;
        $user->two_factor_enabled = $request->has('two_factor_enabled');

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::delete($user->avatar);
            }
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        if ($request->filled('current_password') && $request->filled('new_password')) {
            $user->password = \Illuminate\Support\Facades\Hash::make($data['new_password']);
        }

        $user->save();

        if ($request->filled('language')) {
            $settings = $this->readSettings();
            $settings['admin'] = array_merge($settings['admin'] ?? [], [
                'language' => $request->input('language'),
            ]);
            $this->writeSettings($settings);
            session(['locale' => $request->input('language')]);
        }

        return back()->with('success', 'Cập nhật hồ sơ thành công');
    }

    public function postCreate()
    {
        $categories = \App\Models\PostCategory::with('children.children')
            ->whereNull('parent_id')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
        $tagSuggestions = \App\Models\PostTag::withCount('posts')->orderByDesc('posts_count')->orderBy('name')->pluck('name')->take(50)->values()->all();
        return view('admin.posts.create', compact('categories','tagSuggestions'));
    }

    public function postEdit($id)
    {
        $post = Post::with(['tags', 'category'])->findOrFail($id);
        $categories = \App\Models\PostCategory::with('children.children')
            ->whereNull('parent_id')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
        $tagSuggestions = \App\Models\PostTag::withCount('posts')->orderByDesc('posts_count')->orderBy('name')->pluck('name')->take(50)->values()->all();
        return view('admin.posts.edit', compact('post', 'categories','tagSuggestions'));
    }

    public function postStore(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:posts,slug',
            'content' => 'required|string',
            'thumbnail' => 'nullable|image|max:5120',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:post_categories,id',
            'tags' => 'nullable|string',
            'meta_description' => 'nullable|string|max:255',
            'keywords' => 'nullable|string|max:255',
        ]);

        $postData = [
            'title' => $data['title'],
            'slug' => $data['slug'] ?? Str::slug($data['title']),
            'content' => $data['content'],
            'excerpt' => $data['meta_description'] ?? Str::limit(strip_tags($data['content']), 150),
            'author_id' => auth()->id(),
            'status' => $request->input('action') === 'draft' ? 'draft' : 'published',
            'published_at' => $request->input('action') === 'draft' ? null : now(),
        ];

        if (!empty($data['categories'])) {
            $postData['category_id'] = $data['categories'][0];
        }

        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            $originalName = $file->getClientOriginalName();
            $filename = pathinfo($originalName, PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $safeFilename = Str::slug($filename) . '.' . $extension;
            
            // Organize by date Y/m/d to avoid folder clutter and collisions
            $datePath = date('Y/m/d');
            $path = $file->storeAs("posts/{$datePath}", $safeFilename, 'public');
            $postData['thumbnail'] = $path;
        }

        $post = Post::create($postData);

        if (!empty($data['tags'])) {
            $tagNames = explode(',', $data['tags']);
            $tagIds = [];
            foreach ($tagNames as $tagName) {
                $tagName = trim($tagName);
                if ($tagName) {
                    $tag = \App\Models\PostTag::firstOrCreate(['name' => $tagName], ['slug' => Str::slug($tagName)]);
                    $tagIds[] = $tag->id;
                }
            }
            $post->tags()->sync($tagIds);
        }

        return redirect()->route('admin.posts.index')->with('success', 'Bài viết đã được tạo thành công.');
    }

    public function postUpdate(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:posts,slug,' . $post->id,
            'content' => 'required|string',
            'thumbnail' => 'nullable|image|max:5120',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:post_categories,id',
            'tags' => 'nullable|string',
            'meta_description' => 'nullable|string|max:255',
            'keywords' => 'nullable|string|max:255',
        ]);

        $postData = [
            'title' => $data['title'],
            'slug' => $data['slug'] ?? Str::slug($data['title']),
            'content' => $data['content'],
            'excerpt' => $data['meta_description'] ?? Str::limit(strip_tags($data['content']), 150),
            'status' => $request->input('action') === 'draft' ? 'draft' : 'published',
        ];
        
        if ($request->input('action') !== 'draft' && !$post->published_at) {
             $postData['published_at'] = now();
        }

        if (!empty($data['categories'])) {
            $postData['category_id'] = $data['categories'][0];
        }

        if ($request->hasFile('thumbnail')) {
            if ($post->thumbnail) {
                Storage::delete($post->thumbnail);
            }
            $file = $request->file('thumbnail');
            $originalName = $file->getClientOriginalName();
            $filename = pathinfo($originalName, PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $safeFilename = Str::slug($filename) . '.' . $extension;
            
            // Organize by date Y/m/d to avoid folder clutter and collisions
            $datePath = date('Y/m/d');
            $path = $file->storeAs("posts/{$datePath}", $safeFilename, 'public');
            $postData['thumbnail'] = $path;
        }

        $post->update($postData);

        if (isset($data['tags'])) {
            $tagNames = explode(',', $data['tags']);
            $tagIds = [];
            foreach ($tagNames as $tagName) {
                $tagName = trim($tagName);
                if ($tagName) {
                    $tag = \App\Models\PostTag::firstOrCreate(['name' => $tagName], ['slug' => Str::slug($tagName)]);
                    $tagIds[] = $tag->id;
                }
            }
            $post->tags()->sync($tagIds);
        }

        return redirect()->route('admin.posts.index')->with('success', 'Bài viết đã được cập nhật thành công.');
    }

    public function postDestroy($id)
    {
        $post = Post::findOrFail($id);
        if ($post->thumbnail) {
            Storage::delete($post->thumbnail);
        }
        $post->delete();
        return redirect()->route('admin.posts.index')->with('success', 'Bài viết đã được xóa thành công.');
    }

    public function postComments(Request $request)
    {
        $query = \App\Models\PostComment::with(['post', 'user']);
        
        $status = $request->query('status');
        if ($status) {
            if ($status === 'pending') $query->where('status', 'pending');
            elseif ($status === 'approved') $query->where('status', 'approved');
            elseif ($status === 'spam') $query->where('status', 'spam');
        }

        $search = trim((string)$request->query('q', ''));
        if ($search !== '') {
            $query->where(function($q) use ($search) {
                $q->where('content', 'like', '%'.$search.'%')
                  ->orWhere('name', 'like', '%'.$search.'%')
                  ->orWhere('email', 'like', '%'.$search.'%');
            });
        }

        $comments = $query->latest()->paginate(10)->appends($request->query());

        $total = \App\Models\PostComment::count();
        $approvedCount = \App\Models\PostComment::where('status', 'approved')->count();

        $stats = [
            'total' => $total,
            'pending' => \App\Models\PostComment::where('status', 'pending')->count(),
            'approved_percent' => $total > 0 ? round(($approvedCount / $total) * 100) : 0,
        ];

        return view('admin.posts.comments', compact('comments', 'stats'));
    }

    public function postCommentUpdate(Request $request, $id)
    {
        $comment = \App\Models\PostComment::findOrFail($id);
        
        if ($request->has('status')) {
            $comment->status = $request->input('status');
            $comment->save();
            return redirect()->back()->with('success', 'Trạng thái bình luận đã được cập nhật.');
        }
        
        return redirect()->back();
    }
    
    public function postCommentReply(Request $request, $id)
    {
        $comment = \App\Models\PostComment::findOrFail($id);
        
        $request->validate([
            'content' => 'required|string',
        ]);
        
        \App\Models\PostComment::create([
            'post_id' => $comment->post_id,
            'parent_id' => $comment->id,
            'user_id' => auth()->id(),
            'name' => auth()->user()->name,
            'email' => auth()->user()->email,
            'content' => $request->input('content'),
            'status' => 'approved',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
        
        // Approve the parent comment if it was pending
        if ($comment->status === 'pending') {
            $comment->status = 'approved';
            $comment->save();
        }
        
        return redirect()->back()->with('success', 'Đã trả lời bình luận thành công.');
    }

    public function postCommentDestroy($id)
    {
        $comment = \App\Models\PostComment::findOrFail($id);
        $comment->delete();
        return redirect()->back()->with('success', 'Bình luận đã được xóa.');
    }

    // --- Combo System Methods ---

    // 1. Volume Pricing
    public function volumePricing(Request $request)
    {
        $hasVariantColumn = Schema::hasColumn('volume_pricings', 'product_variant_id');
        $query = $hasVariantColumn
            ? VolumePricing::with(['variant.product', 'variant.images'])->orderBy('product_variant_id')->orderBy('min_qty')
            : VolumePricing::with(['product.images'])->orderBy('product_id')->orderBy('min_qty');
        $tiers = $query->paginate(20);
        return view('admin.combos.volume_pricing', compact('tiers'));
    }

    public function volumePricingStore(Request $request)
    {
        $data = $request->validate([
            'product_variant_id' => ['required', 'integer', 'exists:product_variants,id'],
            'min_qty' => ['required', 'integer', 'min:1'],
            'price' => ['required', 'numeric', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'free_shipping' => ['nullable', 'boolean'],
        ]);
        
        $data['is_active'] = $request->has('is_active');
        $data['free_shipping'] = $request->has('free_shipping');
        
        $hasVariantColumn = Schema::hasColumn('volume_pricings', 'product_variant_id');
        if ($hasVariantColumn) {
            VolumePricing::create($data);
        } else {
            $variant = ProductVariant::find((int)$data['product_variant_id']);
            VolumePricing::create([
                'product_id' => optional($variant)->product_id,
                'min_qty' => $data['min_qty'],
                'price' => $data['price'],
                'is_active' => $data['is_active'],
                'free_shipping' => $data['free_shipping'],
            ]);
        }
        
        return redirect()->route('admin.volume_pricing.index')->with('status', 'Đã thêm bậc giá thành công');
    }

    public function volumePricingUpdate(Request $request, $id)
    {
        $vp = VolumePricing::findOrFail($id);
        $data = $request->validate([
            'min_qty' => ['required', 'integer', 'min:1'],
            'price' => ['required', 'numeric', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'free_shipping' => ['nullable', 'boolean'],
        ]);
        
        $vp->update([
            'min_qty' => $data['min_qty'],
            'price' => $data['price'],
            'is_active' => $request->has('is_active'),
            'free_shipping' => $request->has('free_shipping'),
        ]);
        
        return redirect()->route('admin.volume_pricing.index')->with('status', 'Đã cập nhật bậc giá');
    }

    public function volumePricingDestroy($id)
    {
        $vp = VolumePricing::findOrFail($id);
        $vp->delete();
        return redirect()->route('admin.volume_pricing.index')->with('status', 'Đã xóa bậc giá');
    }

    // 2. Promotion Rules (Mix & Match)
    public function promotionRules(Request $request)
    {
        $rules = PromotionRule::orderBy('name')->paginate(20);
        return view('admin.combos.promotion_rules', compact('rules'));
    }

    public function promotionRuleStore(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'min_total_qty' => ['required', 'integer', 'min:1'],
            'discount_type' => ['required', 'string', 'in:amount,percent'],
            'discount_value' => ['required', 'numeric', 'min:0'],
            'condition_json' => ['nullable', 'string'],
            'requires_code' => ['nullable', 'boolean'],
            'promo_code' => ['nullable', 'string', 'max:50'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'is_active' => ['nullable', 'boolean'],
            'free_shipping' => ['nullable', 'boolean'],
        ]);
        
        $data['is_active'] = $request->has('is_active');
        $data['requires_code'] = $request->has('requires_code');
        $data['free_shipping'] = $request->has('free_shipping');
        $data['type'] = 'mix_match'; // Force type
        
        PromotionRule::create($data);
        
        return redirect()->route('admin.promotion_rules.index')->with('status', 'Đã tạo chương trình khuyến mãi');
    }
    
    public function promotionRuleUpdate(Request $request, $id)
    {
        $rule = PromotionRule::findOrFail($id);
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'min_total_qty' => ['required', 'integer', 'min:1'],
            'discount_type' => ['required', 'string', 'in:amount,percent'],
            'discount_value' => ['required', 'numeric', 'min:0'],
            'condition_json' => ['nullable', 'string'],
            'requires_code' => ['nullable', 'boolean'],
            'promo_code' => ['nullable', 'string', 'max:50'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'is_active' => ['nullable', 'boolean'],
            'free_shipping' => ['nullable', 'boolean'],
        ]);
        
        $data['is_active'] = $request->has('is_active');
        $data['requires_code'] = $request->has('requires_code');
        $data['free_shipping'] = $request->has('free_shipping');
        
        $rule->update($data);
        
        return redirect()->route('admin.promotion_rules.index')->with('status', 'Đã cập nhật chương trình');
    }

    public function promotionRuleDestroy($id)
    {
        PromotionRule::findOrFail($id)->delete();
        return redirect()->route('admin.promotion_rules.index')->with('status', 'Đã xóa chương trình');
    }

    // 3. Combo Bundles
    public function combos(Request $request)
    {
        $combos = Combo::withCount('lines')->orderBy('name')->paginate(20);
        return view('admin.combos.index', compact('combos'));
    }

    public function comboStore(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['required', 'string', 'max:50', 'unique:combos,sku'],
            'price' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
            'free_shipping' => ['nullable', 'boolean'],
        ]);
        
        $data['is_active'] = $request->has('is_active');
        $data['free_shipping'] = $request->has('free_shipping');
        
        $combo = Combo::create($data);
        return redirect()->route('admin.combos.edit', $combo->id)->with('status', 'Đã tạo combo, hãy thêm sản phẩm');
    }

    public function comboEdit($id)
    {
        $combo = Combo::with(['lines.variant.product','lines.product'])->findOrFail($id);
        return view('admin.combos.edit', compact('combo'));
    }

    public function comboUpdate(Request $request, $id)
    {
        $combo = Combo::findOrFail($id);
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['required', 'string', 'max:50', 'unique:combos,sku,'.$id],
            'price' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
            'free_shipping' => ['nullable', 'boolean'],
        ]);
        
        $data['is_active'] = $request->has('is_active');
        $data['free_shipping'] = $request->has('free_shipping');
        $combo->update($data);
        
        return redirect()->back()->with('status', 'Đã cập nhật thông tin combo');
    }

    public function comboDestroy($id)
    {
        Combo::findOrFail($id)->delete();
        return redirect()->route('admin.combos.index')->with('status', 'Đã xóa combo');
    }

    public function comboLineStore(Request $request, $id)
    {
        $combo = Combo::findOrFail($id);
        $data = $request->validate([
            'product_variant_id' => ['required', 'integer', 'exists:product_variants,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);
        
        $hasVariantColumn = \Illuminate\Support\Facades\Schema::hasColumn('combo_lines', 'product_variant_id');
        if ($hasVariantColumn) {
            $variant = ProductVariant::find((int)$data['product_variant_id']);
            $existing = ComboLine::where('combo_id', $combo->id)
                ->where('product_variant_id', $data['product_variant_id'])
                ->first();
            if ($existing) {
                $existing->update(['quantity' => $existing->quantity + $data['quantity']]);
            } else {
                $payload = [
                    'combo_id' => $combo->id,
                    'product_variant_id' => $data['product_variant_id'],
                    'quantity' => $data['quantity'],
                ];
                if (\Illuminate\Support\Facades\Schema::hasColumn('combo_lines', 'product_id')) {
                    $payload['product_id'] = optional($variant)->product_id;
                }
                ComboLine::create($payload);
            }
        } else {
            $variant = ProductVariant::find((int)$data['product_variant_id']);
            $pid = optional($variant)->product_id;
            $existing = ComboLine::where('combo_id', $combo->id)->where('product_id', $pid)->first();
            if ($existing) {
                $existing->update(['quantity' => $existing->quantity + $data['quantity']]);
            } else {
                ComboLine::create([
                    'combo_id' => $combo->id,
                    'product_id' => $pid,
                    'quantity' => $data['quantity'],
                ]);
            }
        }
        
        return redirect()->back()->with('status', 'Đã thêm/cập nhật sản phẩm trong combo');
    }

    public function comboLineUpdate(Request $request, $id, $lineId)
    {
        $line = ComboLine::where('combo_id', $id)->where('id', $lineId)->firstOrFail();
        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);
        
        $line->update(['quantity' => $data['quantity']]);
        return redirect()->back()->with('status', 'Đã cập nhật số lượng');
    }

    public function comboLineDestroy($id, $lineId)
    {
        ComboLine::where('combo_id', $id)->where('id', $lineId)->delete();
        return redirect()->back()->with('status', 'Đã xóa sản phẩm khỏi combo');
    }
    
    public function combosProductsSearch(Request $request)
    {
        $q = $request->query('q');
        $ids = $request->query('ids'); // comma separated ids

        if (!$q && !$ids) return response()->json(['items'=>[]]);
        
        $query = ProductVariant::with(['product.images', 'images', 'options.attribute', 'options.attributeValue']);

        if ($ids) {
            $idArray = array_filter(explode(',', $ids), fn($i) => is_numeric($i));
            if (empty($idArray)) return response()->json(['items'=>[]]);
            $query->whereIn('id', $idArray);
        } else {
            $query->where(function($qq) use ($q) {
                $qq->where('sku', 'like', "%{$q}%")
                   ->orWhereHas('product', function($query) use ($q) {
                       $query->where('name', 'like', "%{$q}%");
                   });
            })->limit(20);
        }

        $variants = $query->get();
            
        $items = $variants->map(function($v) {
            $productName = optional($v->product)->name ?? '';
            
            // Construct variant name from options
            $opts = [];
            foreach (($v->options ?? collect()) as $opt) {
                $opts[] = (string)($opt->attributeValue->value ?? '');
            }
            $variantName = implode(' / ', array_filter($opts));
            
            $fullName = $productName . ($variantName ? ' (' . $variantName . ')' : '');

            // Get image: variant image > primary product image > first product image
            $image = optional($v->images->first())->url;
            if (!$image && $v->product) {
                $image = optional($v->product->images->where('is_primary', true)->first())->url 
                      ?? optional($v->product->images->first())->url;
            }

            return [
                'id' => $v->id,
                'name' => $fullName,
                'sku' => $v->sku,
                'price' => $v->price,
                'image' => $image ? asset($image) : null,
            ];
        });
        
        return response()->json(['items' => $items]);
    }

    public function promotionsOverview()
    {
        $period = request()->query('period', '30d');
        $start = now()->subDays(30);
        if ($period === '7d') { $start = now()->subDays(7); }
        if ($period === 'month') { $start = now()->startOfMonth(); }
        $orders = Order::with('items')->where('placed_at', '>=', $start)->get();
        $revenue = (float)$orders->sum('total');
        $discountSpent = (float)$orders->sum('discount_amount');
        $totalOrders = (int)$orders->count();
        $completedOrders = (int)Order::where('placed_at', '>=', $start)->where('status', 'completed')->count();
        $conversionRate = $totalOrders > 0 ? round(($completedOrders / $totalOrders) * 100, 1) : 0.0;
        $activeCombos = (int)Combo::where('is_active', true)->count();
        $activeRules = (int)PromotionRule::where('is_active', true)->count();
        $stats = [
            'revenue' => $revenue,
            'discount_spent' => $discountSpent,
            'active_programs' => [
                'combos' => $activeCombos,
                'rules' => $activeRules,
            ],
            'conversion_rate' => $conversionRate,
            'period' => $period,
        ];
        $counts = [
            'combos' => (int)Combo::count(),
            'rules' => (int)PromotionRule::count(),
            'volume_pricing' => (int)\App\Models\VolumePricing::count(),
        ];
        $comboAgg = [];
        $combos = Combo::with('lines')->get();
        foreach ($combos as $combo) {
            $setsTotal = 0;
            $revTotal = 0.0;
            foreach ($orders as $order) {
                $available = [];
                $priceMap = [];
                foreach ($order->items as $it) {
                    $vid = (int)$it->product_variant_id;
                    $available[$vid] = ($available[$vid] ?? 0) + (int)$it->quantity;
                    $priceMap[$vid] = (float)$it->unit_price;
                }
                $sets = PHP_INT_MAX;
                $lineVariants = [];
                foreach ($combo->lines as $ln) {
                    $vid = (int)$ln->product_variant_id;
                    $perSet = (int)$ln->quantity;
                    $have = (int)($available[$vid] ?? 0);
                    if ($perSet <= 0) { $sets = 0; break; }
                    $setsForLine = intdiv($have, $perSet);
                    $sets = min($sets, $setsForLine);
                    $lineVariants[] = ['vid' => $vid, 'per' => $perSet];
                }
                if ($sets > 0 && $sets !== PHP_INT_MAX) {
                    $raw = 0.0;
                    foreach ($lineVariants as $lv) {
                        $vid = $lv['vid']; $per = $lv['per'];
                        $raw += ($priceMap[$vid] ?? 0.0) * ($per * $sets);
                    }
                    $target = (float)($combo->price ?? 0.0) * $sets;
                    $setsTotal += $sets;
                    $revTotal += $target;
                }
            }
            $comboAgg[] = [
                'id' => $combo->id,
                'name' => $combo->name,
                'type' => 'combo',
                'sold' => $setsTotal,
                'revenue' => $revTotal,
            ];
        }
        $ruleAgg = [];
        $rules = PromotionRule::where('is_active', true)->where('type', 'mix_match')->get();
        foreach ($rules as $rule) {
            $cond = [];
            if (!empty($rule->condition_json)) {
                try { $cond = json_decode($rule->condition_json, true) ?: []; } catch (\Throwable $e) { $cond = []; }
            }
            $variantIds = collect($cond)->filter(fn($v)=>is_numeric($v))->map(fn($v)=>(int)$v)->values();
            if ($variantIds->isEmpty()) { 
                $ruleAgg[] = ['id'=>$rule->id,'name'=>$rule->name,'type'=>'mix_match','sold'=>0,'revenue'=>null,'affected_orders'=>0];
                continue; 
            }
            $affectedOrders = 0;
            $units = 0;
            foreach ($orders as $order) {
                $eligibleCount = 0;
                foreach ($order->items as $it) {
                    $vid = (int)$it->product_variant_id;
                    if ($variantIds->contains($vid)) {
                        $eligibleCount += (int)$it->quantity;
                    }
                }
                if ($eligibleCount >= (int)$rule->min_total_qty) {
                    $affectedOrders++;
                    $units += $eligibleCount;
                }
            }
            $ruleAgg[] = [
                'id' => $rule->id,
                'name' => $rule->name,
                'type' => 'mix_match',
                'sold' => $units,
                'revenue' => null,
                'affected_orders' => $affectedOrders,
            ];
        }
        $topPrograms = collect(array_merge($comboAgg, $ruleAgg))
            ->sortByDesc(function($p){
                $rv = is_numeric($p['revenue'] ?? null) ? (float)$p['revenue'] : 0.0;
                $sd = (int)($p['sold'] ?? 0);
                return ($rv * 1000000) + $sd;
            })
            ->take(5)
            ->values()
            ->all();
        $soonRules = PromotionRule::where('is_active', true)->whereNotNull('starts_at')->whereBetween('starts_at', [now(), now()->addDays(7)])->orderBy('starts_at')->limit(5)->get();
        return view('admin.combos.promotions_overview', compact('stats','counts','topPrograms','soonRules'));
    }
}
