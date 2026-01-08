@php
$variants = $product->variants->loadMissing(['options.attribute','options.attributeValue','images']);
$images = $product->images ?? collect();
$primaryImage = ($images->firstWhere('is_primary', true) ?? $images->first());
$hasSingleVariant = $variants->count() === 1;
$singleVariant = $hasSingleVariant ? $variants->first() : null;
$minPrice = $variants->min('price') ?? ($product->price ?? 0);

$maxDiscount = 0;
foreach ($variants as $v) {
    $p = (float)($v->price ?? 0);
    $c = (float)($v->compare_at_price ?? 0);
    if ($c > $p && $c > 0) {
        $d = round((($c - $p) / $c) * 100);
        if ($d > $maxDiscount) { $maxDiscount = $d; }
    }
}
$prodP = (float)($product->price ?? 0);
$prodD = (float)($product->discounted_price ?? 0);
if ($prodD > 0 && $prodD < $prodP) {
    $d = round((($prodP - $prodD) / $prodP) * 100);
    if ($d > $maxDiscount) { $maxDiscount = $d; }
}
$isAdded = in_array($product->id, $wishlistProductIds ?? []);

// Logic for Variants Grouping
$variantGroups = collect();
$firstAttribute = null;
if ($variants->isNotEmpty()) {
     $firstVariant = $variants->first();
     if ($firstVariant->options->isNotEmpty()) {
         $firstOption = $firstVariant->options->first();
         $firstAttribute = $firstOption ? optional($firstOption)->attribute : null;
         // Group by the first attribute value ID
         $variantGroups = $variants->groupBy(function($v) {
             $opt = $v->options->first();
             return $opt ? ($opt->attribute_value_id ?? 0) : 0;
         });
     }
}
@endphp

<div class="product product-list">
    <div class="product-thumb">
        <a href="{{ route('sanpham.show', $product->slug) }}" class="product-image">
            @if($primaryImage)
                <img loading="lazy" src="{{ $primaryImage->url }}" alt="{{ $product->name }}" width="268" height="306">
            @else
                <img loading="lazy" src="{{ asset('assets/images/products/product-1.png') }}" alt="{{ $product->name }}" width="268" height="306">
            @endif
        </a>

        <div class="product-badge-left">
            @if($product->created_at->diffInDays(now()) < 30)
                <span class="product-badge-new">new</span>
            @endif
        </div>

        <div class="product-badge-right">
            @if($maxDiscount > 0)
                <span class="product-badge-sale">sale</span>
                <span class="product-badge-sale">-{{ $maxDiscount }}%</span>
            @endif
        </div>

        <div class="product-action">
            <button class="product-action-btn" data-tooltip-text="Quick View" data-bs-toggle="modal" data-bs-target="#exampleProductModal" data-slug="{{ $product->slug }}"><i class="sli-magnifier"></i></button>
        </div>

        @if($variantGroups->isNotEmpty())
        <div class="product-variation">
            <div class="product-variation-type">
                @foreach($variantGroups as $attrValueId => $groupVariants)
                    @php
                        $mainVariant = $groupVariants->first();
                        $firstOption = $mainVariant->options->first();
                        
                        if (!$firstOption || !$firstOption->attributeValue) continue;

                        $attrValue = $firstOption->attributeValue;
                        $attrName = $attrValue->value ?? '';
                        
                        // Image: Priority Variant Image -> Product Primary -> Product First
                        $varImage = $mainVariant->images->first();
                        $imgUrl = $varImage ? $varImage->url : ($primaryImage ? $primaryImage->url : asset('assets/images/products/product-1.png'));
                        
                        // Prepare Next Attributes
                        $nextAttributes = $groupVariants->map(function($v) {
                            $opt = $v->options->get(1); // 2nd option
                            return [
                                'name' => ($opt && $opt->attributeValue) ? $opt->attributeValue->value : null,
                                'variant_id' => $v->id,
                                'in_stock' => $v->inventory_quantity > 0
                            ];
                        })->values();
                    @endphp
                    <button class="product-variation-type-btn" 
                            data-tooltip-text="{{ $attrName }}" 
                            data-image="{{ $imgUrl }}"
                            data-next-attrs="{{ json_encode($nextAttributes) }}">
                        <img loading="lazy" src="{{ $imgUrl }}" alt="{{ $attrName }}" width="23" height="23">
                    </button>
                @endforeach
            </div>
        </div>
        @endif
    </div>
    <div class="product-content">
        <h5 class="product-title"><a href="{{ route('sanpham.show', $product->slug) }}">{{ $product->name }}</a></h5>
        <p class="product-excerpt">{{ Str::limit(strip_tags($product->description), 150) }}</p>
        <div class="product-price">
            @if($maxDiscount > 0)
                <del>{{ number_format($prodP, 0, ',', '.') }}₫</del>
            @endif
            {{ number_format($minPrice, 0, ',', '.') }}₫
        </div>

        <div class="product-variation-next" style="display:none; margin-top: 8px;"></div>

        @if($variantGroups->isEmpty())
            <div class="product-rating">
                <span class="product-rating-bg"><span class="product-rating-active" style="width: {{ ($product->avg_rating / 5) * 100 }}%;"></span></span>
                @if($product->review_count > 0)
                    <span class="review-count">({{ $product->review_count }})</span>
                @endif
            </div>
        @endif

        <div class="product-action position-static">
            <button class="product-action-btn btn-add-to-wishlist {{ $isAdded ? 'added' : '' }}" data-id="{{ $product->id }}" data-tooltip-text="Add to wishlist"><i class="sli-heart"></i></button>
            @if($hasSingleVariant && $singleVariant && $singleVariant->inventory_quantity > 0)
                <button class="product-action-btn btn-add-to-cart-simple" data-variant-id="{{ $singleVariant->id }}"><i class="sli-basket-loaded"></i> Add to Cart</button>
            @endif
            <!-- <button class="product-action-btn" data-tooltip-text="Compare"><i class="sli-refresh"></i></button> -->
        </div>
    </div>
</div>
