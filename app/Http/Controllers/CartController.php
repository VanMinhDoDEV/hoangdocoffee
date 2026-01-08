<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductVariant;
use App\Models\VolumePricing;
use App\Models\PromotionRule;
use App\Models\Combo;

class CartController extends Controller
{
    private function computeTierPrice(ProductVariant $variant, int $quantity): float
    {
        // Debug logging
        // $start = microtime(true);
        // \Illuminate\Support\Facades\Log::info("computeTierPrice: Start. Variant ID: {$variant->id}, Qty: {$quantity}, Base Price: {$variant->price}");

        if (\Illuminate\Support\Facades\Schema::hasColumn('volume_pricings', 'product_variant_id')) {
            // Try to find by variant_id first
            $tier = VolumePricing::where('product_variant_id', $variant->id)
                ->where('is_active', true)
                ->where('min_qty', '<=', $quantity)
                ->orderBy('min_qty', 'desc')
                ->first();
            
            if ($tier) {
                // \Illuminate\Support\Facades\Log::info("computeTierPrice: Found tier. Tier ID: {$tier->id}, Min Qty: {$tier->min_qty}, Price: {$tier->price}");
                return (float)$tier->price;
            } else {
                // \Illuminate\Support\Facades\Log::info("computeTierPrice: No tier found for variant: {$variant->id}");
            }
        } else {
             // Legacy support if column doesn't exist (though migration should have added it)
             // This block will fail if product_id is removed but hasColumn returns false
             // We assume if hasColumn is false, we are in old schema where product_id exists.
            $productId = (int)($variant->product->id ?? 0);
            if ($productId) {
                try {
                    $tier = VolumePricing::where('product_id', $productId)
                        ->where('is_active', true)
                        ->where('min_qty', '<=', $quantity)
                        ->orderBy('min_qty', 'desc')
                        ->first();
                    if ($tier) {
                        // \Illuminate\Support\Facades\Log::info("computeTierPrice: Found legacy tier. Tier ID: {$tier->id}, Price: {$tier->price}");
                        return (float)$tier->price;
                    }
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error("Error querying legacy volume pricing: " . $e->getMessage());
                }
            }
        }
        // $end = microtime(true);
        // \Illuminate\Support\Facades\Log::info("computeTierPrice: End. Time: " . ($end - $start));
        return (float)($variant->price ?? 0);
    }

    private function getCart(Request $request): array
    {
        return (array)($request->session()->get('cart.items') ?? []);
    }

    private function saveCart(Request $request, array $items): void
    {
        $request->session()->put('cart.items', array_values($items));
    }

    public function index(Request $request)
    {
        $items = $this->getCart($request);
        // \Illuminate\Support\Facades\Log::info("Cart Index called. Items count: " . count($items));
        
        // Refresh prices to ensure volume pricing is applied
        $updated = false;
        foreach ($items as &$it) {
            $vid = (int)($it['variant_id'] ?? 0);
            if ($vid) {
                $v = ProductVariant::find($vid);
                if ($v) {
                    $newPrice = $this->computeTierPrice($v, (int)($it['quantity'] ?? 1));
                    // Force update price if it differs
                    if (abs($newPrice - (float)($it['price'] ?? 0)) > 1) {
                        $it['price'] = $newPrice;
                        $updated = true;
                    }
                }
            }
        }
        unset($it);
        if ($updated) {
            $this->saveCart($request, $items);
        }

        $subtotal = 0;
        foreach ($items as $it) {
            $subtotal += ((float)($it['price'] ?? 0)) * ((int)($it['quantity'] ?? 0));
        }
        return view('client.cart.index', [
            'items' => $items,
            'subtotal' => $subtotal,
        ]);
    }

    public function add(Request $request)
    {
        $data = $request->validate([
            'variant_id' => ['required','integer','exists:product_variants,id'],
            'quantity' => ['nullable','integer','min:1'],
        ]);
        $qty = max(1, (int)($data['quantity'] ?? 1));
        $variant = ProductVariant::with(['product','options.attribute','options.attributeValue'])->findOrFail((int)$data['variant_id']);
        $max = max(0, (int)($variant->inventory_quantity ?? 0));
        if ($max <= 0) {
            if ($request->expectsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json(['error' => 'Hết hàng'], 422);
            }
            return back()->withErrors(['stock' => 'Hết hàng']);
        }
        $qty = min($qty, $max);
        $items = $this->getCart($request);
        $index = null;
        foreach ($items as $i => $it) {
            if ((int)$it['variant_id'] === (int)$variant->id) { $index = $i; break; }
        }
        if ($index !== null) {
            $newQuantity = min($max, (int)$items[$index]['quantity'] + $qty);
            $items[$index]['quantity'] = $newQuantity;
            $items[$index]['price'] = $this->computeTierPrice($variant, $newQuantity);
            \Illuminate\Support\Facades\Log::info("Cart Add (Update): Variant {$variant->id}, Old Qty: " . ($items[$index]['quantity'] - $qty) . ", New Qty: $newQuantity, New Price: " . $items[$index]['price']);
        } else {
            $opts = [];
            foreach (($variant->options ?? collect()) as $opt) {
                $opts[] = [
                    'name' => (string)($opt->attribute->name ?? ''),
                    'value' => (string)($opt->attributeValue->value ?? ''),
                ];
            }
            $items[] = [
                'variant_id' => $variant->id,
                'sku' => $variant->sku,
                'name' => optional($variant->product)->name ?? ('SKU '.$variant->sku),
                'price' => $this->computeTierPrice($variant, $qty),
                'quantity' => $qty,
                'options' => $opts,
                'image' => optional(optional($variant->images)->first())->url ?? optional(optional($variant->product)->images->firstWhere('is_primary', true))->url,
                'max' => $max,
                'product_id' => optional($variant->product)->id,
            ];
        }
        $this->saveCart($request, $items);
        if ($request->expectsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json($this->jsonCart($request));
        }
        return redirect()->route('cart.index')->with('status', 'Đã thêm vào giỏ hàng');
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'variant_id' => ['required','integer','exists:product_variants,id'],
            'quantity' => ['required','integer','min:0'],
        ]);
        $items = $this->getCart($request);
        foreach ($items as &$it) {
            if ((int)$it['variant_id'] === (int)$data['variant_id']) {
                if ((int)$data['quantity'] <= 0) {
                    $it = null;
                } else {
                    $v = ProductVariant::find((int)$data['variant_id']);
                    $max = max(0, (int)optional($v)->inventory_quantity ?? 0);
                    $it['quantity'] = min($max, (int)$data['quantity']);
                    $it['max'] = $max;
                    if ($v) {
                        $it['price'] = $this->computeTierPrice($v, (int)$it['quantity']);
                        $it['product_id'] = optional($v->product)->id;
                    }
                }
            }
        }
        $items = array_values(array_filter($items));
        $this->saveCart($request, $items);
        if ($request->expectsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json($this->jsonCart($request));
        }
        return redirect()->route('cart.index')->with('status', 'Đã cập nhật giỏ hàng');
    }

    public function updateAll(Request $request)
    {
        $data = $request->validate([
            'quantities' => ['required', 'array'],
            'quantities.*' => ['integer', 'min:1'],
        ]);

        $items = $this->getCart($request);
        $updatedItems = [];

        foreach ($items as $it) {
            $vid = (int)$it['variant_id'];
            if (isset($data['quantities'][$vid])) {
                $newQty = (int)$data['quantities'][$vid];
                
                if ($newQty > 0) {
                    $v = ProductVariant::find($vid);
                    $max = max(0, (int)optional($v)->inventory_quantity ?? 0);
                    $it['quantity'] = min($max, $newQty);
                    $it['max'] = $max;
                    if ($v) {
                        $it['price'] = $this->computeTierPrice($v, (int)$it['quantity']);
                        $it['product_id'] = optional($v->product)->id;
                    }
                    $updatedItems[] = $it;
                }
            } else {
                $updatedItems[] = $it;
            }
        }

        $this->saveCart($request, $updatedItems);
        return redirect()->route('cart.index')->with('status', 'Đã cập nhật giỏ hàng');
    }

    public function remove(Request $request)
    {
        $data = $request->validate([
            'variant_id' => ['required','integer'],
        ]);
        $items = array_values(array_filter($this->getCart($request), function($it) use ($data) {
            return (int)$it['variant_id'] !== (int)$data['variant_id'];
        }));
        $this->saveCart($request, $items);
        if ($request->expectsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json($this->jsonCart($request));
        }
        return redirect()->route('cart.index')->with('status', 'Đã xóa sản phẩm khỏi giỏ');
    }

    public function clear(Request $request)
    {
        $request->session()->forget('cart.items');
        if ($request->expectsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json($this->jsonCart($request));
        }
        return redirect()->route('cart.index')->with('status', 'Đã làm trống giỏ hàng');
    }

    private function jsonCart(Request $request): array
    {
        $items = $this->getCart($request);
        $items = $this->applyMixMatchPromotions($items);
        $subtotal = 0;
        $count = 0;
        foreach ($items as $it) {
            $subtotal += ((float)($it['price'] ?? 0)) * ((int)($it['quantity'] ?? 0));
            $count += (int)($it['quantity'] ?? 0);
        }
        $discount = 0.0;
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
                    foreach ($lineVariants as $lv) {
                        $vid = $lv['vid']; $per = $lv['per'];
                        $available[$vid] = max(0, (int)$available[$vid] - ($per * $sets));
                    }
                }
            }
        }
        $total = max(0, $subtotal - $discount);
        return [
            'items' => $items,
            'subtotal' => $subtotal,
            'subtotal_format' => number_format($subtotal, 0, ',', '.') . 'đ',
            'discount' => $discount,
            'discount_format' => number_format($discount, 0, ',', '.') . 'đ',
            'total' => $total,
            'total_format' => number_format($total, 0, ',', '.') . 'đ',
            'count' => $count,
            'html' => view('client.partials.mini-cart-items', ['items' => $items])->render(),
            'main_html' => view('client.cart.partials.cart-main', ['items' => $items, 'subtotal' => $subtotal, 'discount' => $discount, 'total' => $total])->render(),
        ];
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

        $appliedCodes = collect((array)request()->session()->get('promotion_codes', []))
            ->map(fn($v)=>strtolower(trim((string)$v)))->filter()->values();

        foreach ($rules as $rule) {
            $cond = [];
            if (!empty($rule->condition_json)) {
                try { $cond = json_decode($rule->condition_json, true) ?: []; } catch (\Throwable $e) { $cond = []; }
            }
            // Now condition stores variant IDs
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

    public function applyPromo(Request $request)
    {
        $data = $request->validate([
            'code' => ['required','string','max:100'],
        ]);
        $code = strtolower(trim((string)$data['code']));
        $codes = collect((array)$request->session()->get('promotion_codes', []))
            ->map(fn($v)=>strtolower(trim((string)$v)))->filter()->values()->all();
        if (!in_array($code, $codes)) {
            $codes[] = $code;
            $request->session()->put('promotion_codes', $codes);
        }
        return response()->json($this->jsonCart($request));
    }
}
