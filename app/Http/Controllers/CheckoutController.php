<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductVariant;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Address;
use App\Models\Warehouse;
use App\Models\WarehouseInventory;
use App\Models\StockMovement;
use App\Models\Combo;
use App\Models\PromotionRule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    // Handle "Buy Now" - Prepare data and redirect to checkout
    public function buyNow(Request $request)
    {
        $data = $request->validate([
            'variant_id' => ['required', 'integer', 'exists:product_variants,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $variant = ProductVariant::lockForUpdate()->with(['product', 'options.attribute', 'options.attributeValue'])->findOrFail((int)$data['variant_id']);
        
        // Check stock
        if ($variant->inventory_quantity < $data['quantity']) {
            return back()->withErrors(['stock' => 'Số lượng không đủ trong kho']);
        }

        // Build item structure
        $opts = [];
        foreach (($variant->options ?? collect()) as $opt) {
            $opts[] = [
                'name' => (string)($opt->attribute->name ?? ''),
                'value' => (string)($opt->attributeValue->value ?? ''),
            ];
        }

        $item = [
            'variant_id' => $variant->id,
            'sku' => $variant->sku,
            'name' => optional($variant->product)->name ?? ('SKU '.$variant->sku),
            'price' => (float)($variant->price ?? 0),
            'quantity' => (int)$data['quantity'],
            'options' => $opts,
            'image' => optional(optional($variant->images)->first())->url ?? optional(optional($variant->product)->images->firstWhere('is_primary', true))->url,
            'max' => $variant->inventory_quantity,
        ];

        // CHECK USER INFO FOR INSTANT ORDER
        if (Auth::check()) {
            $user = Auth::user();
            $address = $user->addresses()->where('is_default', true)->first() ?? $user->addresses()->first();

            if ($address) {
                // User has address, proceed to create order immediately
                return DB::transaction(function () use ($user, $address, $item, $variant, $data) {
                    // Re-check stock inside transaction
                    $variant->refresh();
                    if ($variant->inventory_quantity < $data['quantity']) {
                        return back()->withErrors(['stock' => 'Số lượng không đủ trong kho']);
                    }

                    // Create Order
                    $subtotal = $item['price'] * $item['quantity'];
                    
                    $order = Order::create([
                        'user_id' => $user->id,
                        'status' => 'new',
                        'subtotal' => $subtotal,
                        'discount_amount' => 0,
                        'total' => $subtotal,
                        'placed_at' => now(),
                        'shipping_name' => $address->name ?? $user->name,
                        'shipping_phone' => $address->phone,
                        'shipping_email' => $user->email,
                        'shipping_province' => $address->city,
                        'shipping_district' => $address->district,
                        'shipping_ward' => $address->ward,
                        'shipping_address' => $address->address_line,
                    ]);

                    // Create Order Item
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_variant_id' => $variant->id,
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['price'],
                        'snapshot_name' => $item['name'],
                        'snapshot_sku' => $item['sku'],
                        'snapshot_color' => collect($item['options'])->firstWhere('name','Color')['value'] ?? 'N/A',
                        'snapshot_size' => collect($item['options'])->firstWhere('name','Size')['value'] ?? 'N/A',
                    ]);

                    $this->reserveForOrder($variant->id, $order->id, $item['quantity']);

                    return redirect()->route('orders.show', $order->id)->with('status', 'Đặt hàng thành công!');
                });
            }
        }

        // Fallback: Redirect to Checkout Page if Guest or Missing Address
        session(['checkout_items' => [$item]]);
        session(['checkout_source' => 'buy_now']);

        return redirect()->route('checkout.index');
    }

    // Prepare checkout from Cart
    public function fromCart(Request $request)
    {
        $cartItems = session('cart.items', []);
        if (empty($cartItems)) {
            return redirect()->route('cart.index')->withErrors(['cart' => 'Giỏ hàng trống']);
        }

        session(['checkout_items' => $cartItems]);
        session(['checkout_source' => 'cart']);

        return redirect()->route('checkout.index');
    }

    // Show Checkout Page
    public function index(Request $request)
    {
        $items = session('checkout_items', []);

        // If no checkout items, try to load from cart
        if (empty($items)) {
            $cartItems = session('cart.items', []);
            if (!empty($cartItems)) {
                $items = $cartItems;
                session(['checkout_items' => $items]);
                session(['checkout_source' => 'cart']);
            } else {
                return redirect()->route('cart.index');
            }
        }

        $items = $this->applyMixMatchPromotions($items);

        $subtotal = 0;
        foreach ($items as $it) {
            $subtotal += ((float)($it['price'] ?? 0)) * ((int)($it['quantity'] ?? 0));
        }
        $discount = 0.0;
        $freeShip = false;
        if (!empty($items)) {
            $available = [];
            $priceMap = [];
            foreach ($items as $it) {
                $vid = (int)($it['variant_id'] ?? 0);
                if ($vid) {
                    $available[$vid] = ($available[$vid] ?? 0) + (int)($it['quantity'] ?? 0);
                    $priceMap[$vid] = (float)($it['price'] ?? 0);
                }
            }
            $combos = Combo::with('lines')->where('is_active', true)->get();
            foreach ($combos as $combo) {
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
                        $raw += ($priceMap[$vid] ?? 0) * ($per * $sets);
                    }
                    $target = (float)($combo->price ?? 0) * $sets;
                    if ($raw > $target) { $discount += ($raw - $target); }
                    if ($combo->free_shipping ?? false) { $freeShip = true; }
                    foreach ($lineVariants as $lv) {
                        $vid = $lv['vid']; $per = $lv['per'];
                        $available[$vid] = max(0, (int)$available[$vid] - ($per * $sets));
                    }
                }
            }
        }
        $total = max(0, $subtotal - $discount);

        // --- PAYMENT METHODS CALCULATION ---
        // 1. Define all supported system payment methods
        $systemMethods = [
            'cod' => [
                'label' => 'Thanh toán khi nhận hàng (COD)',
                'description' => 'Bạn sẽ thanh toán tiền mặt khi nhận được hàng.',
                'icon' => 'flaticon-money', 
            ],
            'bank_transfer' => [
                'label' => 'Chuyển khoản ngân hàng',
                'description' => 'Thực hiện chuyển khoản vào tài khoản ngân hàng của chúng tôi.',
                'icon' => 'flaticon-credit-card',
            ],
            'credit' => [
                'label' => 'Thẻ tín dụng / Ghi nợ',
                'description' => 'Thanh toán trực tuyến an toàn qua thẻ Visa, MasterCard, JCB.',
                'icon' => 'flaticon-credit-card-1',
            ],
             'momo' => [
                'label' => 'Ví MoMo',
                'description' => 'Quét mã QR để thanh toán qua ví MoMo.',
                'icon' => '',
            ],
            'vnpay' => [
                'label' => 'VNPay QR',
                'description' => 'Thanh toán qua ứng dụng ngân hàng hoặc ví điện tử VNPAY.',
                'icon' => '',
            ]
        ];

        // 2. Start with all system methods as candidates
        $candidateCodes = array_keys($systemMethods);

        // 3. Filter based on products in cart
        foreach ($items as $it) {
            $variantId = $it['variant_id'] ?? null;
            if (!$variantId) continue;

            $variant = ProductVariant::with('product')->find($variantId);
            if (!$variant || !$variant->product) continue;

            $pMethodStr = $variant->product->payment_method; // e.g. "cod,bank_transfer" or "all" or null

            // If product has specific methods defined (and not "all")
            if (!empty($pMethodStr) && strtolower($pMethodStr) !== 'all') {
                $pMethods = array_map('trim', explode(',', $pMethodStr));
                // Intersect with current candidates
                $candidateCodes = array_intersect($candidateCodes, $pMethods);
            }
        }

        // 4. Build final available methods array
        $availablePaymentMethods = [];
        foreach ($candidateCodes as $code) {
            if (isset($systemMethods[$code])) {
                $availablePaymentMethods[$code] = $systemMethods[$code];
            }
        }
        
        // If intersection results in empty (no common method), fallback to COD or show error? 
        // For now, let's ensure at least COD is available if list is empty to avoid blocking checkout, 
        // unless strictly required. User said "intersection", so strictly it should be empty.
        // But for UX, let's keep it empty and let the view handle "No payment method available".
        
        // --- END PAYMENT METHODS CALCULATION ---

        $user = Auth::user();
        $addresses = collect([]);
        $defaultAddress = null;

        if ($user) {
            $addresses = $user->addresses;
            $defaultAddress = $addresses->where('is_default', true)->first() ?? $addresses->first();
        }

        return view('client.checkout.index', [
            'items' => $items,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'total' => $total,
            'freeShip' => $freeShip,
            'user' => $user,
            'addresses' => $addresses,
            'defaultAddress' => $defaultAddress,
            'availablePaymentMethods' => $availablePaymentMethods,
        ]);
    }

    // Process Checkout
    public function process(Request $request)
    {
        $items = session('checkout_items', []);
        if (empty($items)) {
            return redirect()->route('cart.index')->withErrors(['cart' => 'Không có sản phẩm để thanh toán']);
        }

        // Validate basic info
        $rules = [
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_email' => 'required|email|max:255',
            'shipping_province' => 'required|string',
            'shipping_district' => 'nullable|string', // Nullable as per new API structure
            'shipping_ward' => 'required|string',
            'shipping_address' => 'required|string',
            'payment_method' => 'required|string',
            'create_account' => 'nullable|boolean',
            'password' => 'nullable|string|min:6|required_if:create_account,1',
        ];

        $data = $request->validate($rules);
        $note = $request->input('note');
        $addressId = $request->input('address_id');

        try {
            $order = DB::transaction(function () use ($data, $items, $note, $addressId) {
                $user = Auth::user();

            // 1. Handle User Logic
            if (!$user) {
                // ... (User creation logic remains the same)
                $existingUser = User::where('email', $data['customer_email'])->first();

                if ($existingUser) {
                    $user = $existingUser;
                    if (!empty($data['create_account']) && $data['create_account']) {
                        throw \Illuminate\Validation\ValidationException::withMessages([
                            'customer_email' => ['Email đã tồn tại. Vui lòng đăng nhập.'],
                        ]);
                    }
                } else {
                    $isGuest = empty($data['create_account']);
                    $password = $isGuest ? Str::random(16) : $data['password'];
                    
                    $user = User::create([
                        'name' => $data['customer_name'],
                        'email' => $data['customer_email'],
                        'password' => Hash::make($password),
                        'role' => 'customer',
                        'is_guest' => $isGuest,
                    ]);
                }
            }

            // 2. Handle Address Logic
            // Check if we should create a new address or use existing
            $shouldCreateAddress = true;

            // Normalize input data for comparison
            $inputAddress = [
                'name' => $data['customer_name'],
                'phone' => $data['customer_phone'],
                'city' => $data['shipping_province'],
                'ward' => $data['shipping_ward'],
                'address_line' => $data['shipping_address'],
            ];

            // If user selected an existing address ID
            if ($addressId && $user->addresses()->where('id', $addressId)->exists()) {
                $existingAddress = $user->addresses()->find($addressId);
                
                // Compare critical fields
                $isSame = 
                    $existingAddress->name === $inputAddress['name'] &&
                    $existingAddress->phone === $inputAddress['phone'] &&
                    $existingAddress->city === $inputAddress['city'] &&
                    $existingAddress->ward === $inputAddress['ward'] &&
                    $existingAddress->address_line === $inputAddress['address_line'];

                if ($isSame) {
                    $shouldCreateAddress = false;
                }
            } else {
                // Even if no ID provided, check if EXACT duplicate exists to avoid spamming DB
                $duplicate = $user->addresses()
                    ->where('name', $inputAddress['name'])
                    ->where('phone', $inputAddress['phone'])
                    ->where('city', $inputAddress['city'])
                    ->where('ward', $inputAddress['ward'])
                    ->where('address_line', $inputAddress['address_line'])
                    ->first();
                
                if ($duplicate) {
                    $shouldCreateAddress = false;
                }
            }

            if ($shouldCreateAddress) {
                Address::create([
                    'user_id' => $user->id,
                    'name' => $data['customer_name'],
                    'phone' => $data['customer_phone'],
                    'city' => $data['shipping_province'],
                    'ward' => $data['shipping_ward'],
                    'address_line' => $data['shipping_address'],
                    'is_default' => !$user->addresses()->exists(),
                ]);
            }

            // 3. Create Order
            $items = $this->applyMixMatchPromotions($items);
            // Calculate total
            $subtotal = 0;
            foreach ($items as $it) {
                $subtotal += $it['price'] * $it['quantity'];
            }

            $discount = 0.0;
            $freeShip = false;
            $available = [];
            $priceMap = [];
            foreach ($items as $it) {
                $vid = (int)$it['variant_id'];
                $available[$vid] = ($available[$vid] ?? 0) + (int)$it['quantity'];
                $priceMap[$vid] = (float)$it['price'];
            }
            $combos = Combo::with('lines')->where('is_active', true)->get();
            foreach ($combos as $combo) {
                $sets = PHP_INT_MAX;
                $lineVariants = [];
                foreach ($combo->lines as $ln) {
                    $vid = (int)$ln->product_variant_id;
                    $perSet = (int)$ln->quantity;
                    $have = (int)($available[$vid] ?? 0);
                    if ($perSet <= 0) { $sets = 0; break; }
                    $setsForLine = intdiv($have, $perSet);
                    $sets = min($sets, $setsForLine);
                    $lineVariants[] = ['vid'=>$vid,'per'=>$perSet];
                }
                if ($sets > 0 && $sets !== PHP_INT_MAX) {
                    $raw = 0.0;
                    foreach ($lineVariants as $lv) {
                        $vid = $lv['vid']; $per = $lv['per'];
                        $raw += ($priceMap[$vid] ?? 0) * ($per * $sets);
                    }
                    $target = (float)($combo->price ?? 0) * $sets;
                    if ($raw > $target) { $discount += ($raw - $target); }
                    if ($combo->free_shipping ?? false) { $freeShip = true; }
                    foreach ($lineVariants as $lv) {
                        $vid = $lv['vid']; $per = $lv['per'];
                        $available[$vid] = max(0, (int)$available[$vid] - ($per * $sets));
                    }
                }
            }

            $order = Order::create([
                'user_id' => $user->id,
                'status' => 'new',
                'subtotal' => $subtotal,
                'discount_amount' => $discount,
                'total' => max(0, $subtotal - $discount),
                'placed_at' => now(),
                'shipping_name' => $data['customer_name'],
                'shipping_phone' => $data['customer_phone'],
                'shipping_email' => $data['customer_email'],
                'shipping_province' => $data['shipping_province'],
                'shipping_ward' => $data['shipping_ward'],
                'shipping_address' => $data['shipping_address'],
                'payment_method' => $data['payment_method'],
                'note' => $note,
            ]);
            
            // 4. Create Order Items and Update Stock
            foreach ($items as $it) {
                $variant = ProductVariant::lockForUpdate()->find($it['variant_id']);
                if (!$variant || $variant->inventory_quantity < $it['quantity']) {
                    $max = $variant ? $variant->inventory_quantity : 0;
                    throw new \Exception("Sản phẩm {$it['name']} đã hết hàng hoặc không đủ số lượng (Chỉ còn {$max}).");
                }

                // Create OrderItem
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_variant_id' => $variant->id,
                    'quantity' => $it['quantity'],
                    'unit_price' => $it['price'],
                    'snapshot_name' => $it['name'],
                    'snapshot_sku' => $it['sku'],
                    'snapshot_color' => collect($it['options'])->firstWhere('name','Color')['value'] ?? 'N/A', // Simplified
                    'snapshot_size' => collect($it['options'])->firstWhere('name','Size')['value'] ?? 'N/A',
                ]);

                $this->reserveForOrder($variant->id, $order->id, $it['quantity']);
            }

            // Clear cart if source was cart
            if (session('checkout_source') === 'cart') {
                session()->forget('cart.items');
            }
            session()->forget('checkout_items');
            session()->forget('checkout_source');
            
            // Login the user if they just created an account
            if (!Auth::check() && !empty($data['create_account']) && $data['create_account']) {
                Auth::login($user);
            }

            // Update Cache for Polling Optimization
            \Illuminate\Support\Facades\Cache::forever('latest_order_id', $order->id);
            
            // SUPER FAST POLLING: Update activity log
            $this->updateActivityLog('order', $order->id);

            return $order;
        });
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            return redirect()->route('cart.index')->withErrors(['cart' => $e->getMessage()]);
        }

        return redirect()->route('orders.show', $order->id)->with('status', 'Đặt hàng thành công!');
    }
    
    private function reserveForOrder(int $variantId, int $orderId, int $quantity): void
    {
        $w = Warehouse::where('code', 'MAIN')->first();
        if (!$w) {
            $w = Warehouse::create(['name' => 'Main Warehouse', 'code' => 'MAIN', 'address' => '', 'is_active' => true]);
        }
        $inv = WarehouseInventory::firstOrCreate(['warehouse_id' => $w->id, 'product_variant_id' => $variantId], ['on_hand' => 0, 'reserved' => 0, 'incoming' => 0]);
        $inv->reserved = max(0, (int)$inv->reserved + (int)$quantity);
        $inv->save();
        StockMovement::create([
            'movement_type' => 'reservation',
            'warehouse_id' => $w->id,
            'product_variant_id' => $variantId,
            'quantity' => $quantity,
            'ref_type' => 'order',
            'ref_id' => $orderId,
        ]);
        $sumAvailable = max(0, (int)$inv->on_hand - (int)$inv->reserved);
        ProductVariant::where('id', $variantId)->update(['inventory_quantity' => $sumAvailable]);
    }

    private function applyMixMatchPromotions(array $items): array
    {
        $now = now();
        $rules = PromotionRule::where('is_active', true)
            ->where(function ($q) use ($now) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', $now);
            })
            ->where(function ($q) use ($now) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>=', $now);
            })
            ->where('type', 'mix_match')
            ->get();
        if ($rules->isEmpty()) return $items;

        $appliedCodes = collect((array)session()->get('promotion_codes', []))
            ->map(fn($v)=>strtolower(trim((string)$v)))->filter()->values();

        foreach ($rules as $rule) {
            $cond = [];
            if (!empty($rule->condition_json)) {
                try { $cond = json_decode($rule->condition_json, true) ?: []; } catch (\Throwable $e) { $cond = []; }
            }
            $variantIds = collect($cond)->filter(fn($v)=>is_numeric($v))->map(fn($v)=>(int)$v)->values();
            if ($variantIds->isEmpty()) continue;

            if ((bool)$rule->requires_code) {
                $code = strtolower(trim((string)$rule->promo_code));
                if (empty($code) || !$appliedCodes->contains($code)) {
                    continue;
                }
            }

            $eligibleCount = 0;
            foreach ($items as $it) {
                $vid = (int)($it['variant_id'] ?? 0);
                if ($vid && $variantIds->contains($vid)) {
                    $eligibleCount += (int)($it['quantity'] ?? 0);
                }
            }
            if ($eligibleCount >= (int)$rule->min_total_qty) {
                foreach ($items as &$it) {
                    $vid = (int)($it['variant_id'] ?? 0);
                    if ($vid && $variantIds->contains($vid)) {
                        if ($rule->discount_type === 'percent') {
                            $it['price'] = round(((float)$it['price']) * max(0, (100 - (float)$rule->discount_value)) / 100, 2);
                        } elseif ($rule->discount_type === 'amount') {
                            $unitDiscount = (float)$rule->discount_value;
                            $it['price'] = max(0, ((float)$it['price']) - $unitDiscount);
                        }
                    }
                }
                unset($it);
            }
        }
        return $items;
    }
}
