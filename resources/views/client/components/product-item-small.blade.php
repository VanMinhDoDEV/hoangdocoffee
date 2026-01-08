<div class="product-small">
    @php
        $variants = $product->variants;
        $hasSingleVariant = $variants->count() === 1;
        $singleVariant = $hasSingleVariant ? $variants->first() : null;
        $isAdded = in_array($product->id, $wishlistProductIds ?? []);
    @endphp
    <div class="product-small-thumb">
        <a href="{{ route('products.show', $product->slug) }}" class="product-small-image">
            @php
                $img = $product->images->first();
                $imgUrl = $img ? (Str::startsWith($img->url, ['http://', 'https://']) ? $img->url : asset($img->url)) : asset('assets/images/products/small/product-1.jpg');
            @endphp
            <img loading="lazy" src="{{ $imgUrl }}"
                alt="{{ $product->name }}" width="110" height="126">
        </a>
    </div>
    <div class="product-small-content">
        <h5 class="product-small-title"><a href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a></h5>
        <div class="product-small-price">
            @if($product->compare_at_price > $product->price)
                {{ number_format($product->price, 0, ',', '.') }}đ <del>{{ number_format($product->compare_at_price, 0, ',', '.') }}đ</del>
            @else
                {{ number_format($product->price, 0, ',', '.') }}đ
            @endif
        </div>
        <div class="product-small-action">
            <button class="product-small-action-btn" data-tooltip-text="Quick View"
                data-bs-toggle="modal" data-bs-target="#exampleProductModal" data-slug="{{ $product->slug }}"><i
                    class="sli-magnifier"></i></button>
            <button class="product-small-action-btn btn-add-to-wishlist {{ $isAdded ? 'added' : '' }}" data-id="{{ $product->id }}"
                data-tooltip-text="Add to wishlist"><i class="sli-heart"></i></button>
            <!-- <button class="product-small-action-btn" data-tooltip-text="Compare"><i
                    class="sli-refresh"></i></button> -->
            @if($hasSingleVariant && $singleVariant && $singleVariant->inventory_quantity > 0)
                <button class="product-small-action-btn btn-add-to-cart-simple" data-variant-id="{{ $singleVariant->id }}" data-tooltip-text="Add to cart"><i
                        class="sli-bag"></i></button>
            @endif
        </div>
    </div>
</div>
