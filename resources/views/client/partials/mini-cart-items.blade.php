@if(empty($items))
    <div class="text-center py-5">
        <p>Giỏ hàng trống.</p>
    </div>
@else
    @foreach($items as $it)
    <div class="header-cart-product">
        <div class="header-cart-product-thumb">
            <a href="{{ route('products.index') }}" class="header-cart-product-image">
                @if(!empty($it['image']))
                    <img src="{{ $it['image'] }}" alt="{{ $it['name'] }}" width="90" height="103">
                @else
                    <img src="{{ asset('assets/images/products/cart/product-1.jpg') }}" alt="{{ $it['name'] }}" width="90" height="103">
                @endif
            </a>
            <button type="button" class="header-cart-product-remove" onclick="removeMiniCartItem({{ (int)$it['variant_id'] }})"><i class="sli-close"></i></button>
        </div>
        <div class="header-cart-product-content">
            <h5 class="header-cart-product-title"><a href="{{ route('products.index') }}">{{ $it['name'] }}</a></h5>
            
            @if(!empty($it['options']))
                <div class="header-cart-product-variant small text-muted mb-2" style="font-size: 13px;">
                    @foreach($it['options'] as $opt)
                        <div>{{ $opt['name'] }}: {{ $opt['value'] }}</div>
                    @endforeach
                </div>
            @endif

            <div class="header-cart-product-quantity d-flex align-items-center justify-content-between">
                <span class="text-primary fw-bold">{{ number_format((float)($it['price'] ?? 0), 0, ',', '.') }}đ</span>
                
                <div class="product-quantity-count small" style="height: 25px; display: inline-flex; border: 1px solid #ddd;">
                    <button type="button" class="dec qty-btn" style="width: 20px; line-height: 23px; height: 23px; border: none; background: none; padding: 0;" onclick="updateMiniCartItem({{ $it['variant_id'] }}, {{ $it['quantity'] - 1 }})">-</button>
                    <input class="product-quantity-box" type="text" value="{{ (int)$it['quantity'] }}" readonly style="width: 30px; height: 23px; line-height: 23px; font-size: 12px; border: none; text-align: center; padding: 0;">
                    <button type="button" class="inc qty-btn" style="width: 20px; line-height: 23px; height: 23px; border: none; background: none; padding: 0;" onclick="updateMiniCartItem({{ $it['variant_id'] }}, {{ $it['quantity'] + 1 }})">+</button>
                </div>
            </div>
        </div>
    </div>
    @endforeach
@endif
