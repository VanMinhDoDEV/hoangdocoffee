@extends('client.layouts.master')

@section('title', 'Wishlist')

@section('content')
    <!-- Page Banner Section Start -->
    <div class="page-banner-section section">
        <div class="container">
            <ul class="breadcrumb">
                <li><a href="{{ route('home') }}">Home</a></li>
                <li>Wishlist</li>
            </ul>
        </div>
    </div>
    <!-- Page Banner Section End -->

    <!-- Wishlist Section Start -->
    <div class="section section-padding">
        <div class="container">
            @if($items->count() > 0)
            <!-- Cart Table For Tablet & Up Devices Start -->
            <table class="cart-table table table-bordered text-center align-middle mb-6 d-none d-md-table">
                <thead>
                    <tr>
                        <th class="image">Image</th>
                        <th class="title">Product</th>
                        <th class="price">Price</th>
                        <th class="add-to-cart">Add to Cart</th>
                        <th class="remove">Remove</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    @foreach($items as $w)
                        @php 
                            $p = $w->product; 
                            $variantId = $w->product_variant_id;
                            $variant = null;
                            if ($variantId) {
                                $variant = \App\Models\ProductVariant::find($variantId);
                            } else {
                                $variant = $p->variants->first();
                                $variantId = $variant ? $variant->id : null;
                            }

                            // Image logic
                            $imgUrl = asset('assets/images/product/small-product/product-1.jpg');
                            if ($variant && $variant->images && $variant->images->count() > 0) {
                                $imgUrl = $variant->images->first()->url;
                            } elseif ($p->images && $p->images->count() > 0) {
                                $imgUrl = $p->images->first()->url;
                            }
                            // Ensure full URL if not starting with http
                            if (!Str::startsWith($imgUrl, ['http://', 'https://'])) {
                                $imgUrl = Str::startsWith($imgUrl, 'assets') ? asset($imgUrl) : asset('storage/' . $imgUrl);
                            }

                            // Price logic
                            $price = $variant ? $variant->price : $p->price;
                            
                            // Stock logic
                            $inStock = false;
                            if ($variant) {
                                $inStock = $variant->inventory_quantity > 0;
                            } else {
                                $inStock = $p->variants->sum('inventory_quantity') > 0;
                            }
                        @endphp
                        @if($p)
                        <tr class="wishlist-item-row" data-id="{{ $p->id }}" data-variant-id="{{ $w->product_variant_id ?? '' }}">
                            <th>
                                <a href="{{ route('products.show', $p->slug) }}">
                                    <img src="{{ $imgUrl }}" alt="{{ $p->name }}">
                                </a>
                            </th>
                            <td>
                                <a href="{{ route('products.show', $p->slug) }}">{{ $p->name }}</a>
                                @if($variant && $w->product_variant_id)
                                    <br><small class="text-muted">
                                        @foreach($variant->options as $opt)
                                            {{ $opt->attribute->name }}: {{ $opt->attributeValue->value }}@if(!$loop->last), @endif
                                        @endforeach
                                    </small>
                                @endif
                            </td>
                            <td>{{ number_format($price, 0, ',', '.') }}đ</td>
                            <td>
                                @if($inStock && $variantId)
                                    <button type="button" class="btn btn-sm btn-dark btn-primary-hover add-to-cart-btn" data-variant-id="{{ $variantId }}">Add to Cart</button>
                                @else
                                    <a href="{{ route('products.show', $p->slug) }}" class="btn btn-sm btn-dark btn-primary-hover">View Product</a>
                                @endif
                            </td>
                            <td><button class="remove-btn remove-wishlist-btn"><i class="sli-close"></i></button></td>
                        </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
            <!-- Cart Table For Tablet & Up Devices End -->

            <!-- Cart Table For Mobile Devices Start -->
            <div class="cart-products-mobile d-md-none">
                @foreach($items as $w)
                    @php 
                        $p = $w->product; 
                        $variantId = $w->product_variant_id;
                        $variant = null;
                        if ($variantId) {
                            $variant = \App\Models\ProductVariant::find($variantId);
                        } else {
                            $variant = $p->variants->first();
                            $variantId = $variant ? $variant->id : null;
                        }

                        $imgUrl = asset('assets/images/product/small-product/product-1.jpg');
                        if ($variant && $variant->images && $variant->images->count() > 0) {
                            $imgUrl = $variant->images->first()->url;
                        } elseif ($p->images && $p->images->count() > 0) {
                            $imgUrl = $p->images->first()->url;
                        }
                        if (!Str::startsWith($imgUrl, ['http://', 'https://'])) {
                            $imgUrl = asset($imgUrl);
                        }

                        $price = $variant ? $variant->price : $p->price;
                        
                        $inStock = false;
                        if ($variant) {
                            $inStock = $variant->inventory_quantity > 0;
                        } else {
                            $inStock = $p->variants->sum('inventory_quantity') > 0;
                        }
                    @endphp
                    @if($p)
                    <div class="cart-product-mobile wishlist-item-row" data-id="{{ $p->id }}" data-variant-id="{{ $w->product_variant_id ?? '' }}">
                        <div class="cart-product-mobile-thumb">
                            <a href="{{ route('products.show', $p->slug) }}" class="cart-product-mobile-image">
                                <img src="{{ $imgUrl }}" alt="{{ $p->name }}" width="90" height="103">
                            </a>
                            <button class="cart-product-mobile-remove remove-wishlist-btn"><i class="sli-close"></i></button>
                        </div>
                        <div class="cart-product-mobile-content">
                            <h5 class="cart-product-mobile-title"><a href="{{ route('products.show', $p->slug) }}">{{ $p->name }}</a></h5>
                            <span class="cart-product-mobile-quantity">{{ number_format($price, 0, ',', '.') }}đ</span>
                            @if($variant && $w->product_variant_id)
                                <small class="d-block text-muted">
                                    @foreach($variant->options as $opt)
                                        {{ $opt->attribute->name }}: {{ $opt->attributeValue->value }}@if(!$loop->last), @endif
                                    @endforeach
                                </small>
                            @endif
                            <div class="cart-product-mobile-add-to-cart">
                                @if($inStock && $variantId)
                                    <button type="button" class="btn btn-sm btn-dark btn-primary-hover add-to-cart-btn" data-variant-id="{{ $variantId }}">Add to Cart</button>
                                @else
                                    <a href="{{ route('products.show', $p->slug) }}" class="btn btn-sm btn-dark btn-primary-hover">View Product</a>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>
            <!-- Cart Table For Mobile Devices End -->
            @else
                <div class="empty-cart-page text-center">
                    <h4 class="empty-cart-title">Your wishlist is currently empty.</h4>
                    <div class="empty-cart-btn">
                        <a href="{{ route('products.index') }}" class="btn btn-dark btn-hover-primary rounded-0">Shop Now</a>
                    </div>
                </div>
            @endif
        </div>
    </div>
    <!-- Wishlist Section End -->
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
    
    // Handle Remove
    document.querySelectorAll('.remove-wishlist-btn').forEach(btn => {
        btn.addEventListener('click', function(e){
            e.preventDefault();
            const row = this.closest('.wishlist-item-row');
            const id = row.getAttribute('data-id');
            const variantId = row.getAttribute('data-variant-id');
            
            function doRemove(){
                const fd = new FormData();
                fd.append('_token', '{{ csrf_token() }}');
                fd.append('product_id', id);
                if (variantId) fd.append('product_variant_id', variantId);
                
                fetch('{{ route('client.wishlist.remove') }}', {
                    method: 'POST',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    body: fd
                })
                .then(res => res.json())
                .then(data => {
                    if(data.status === 'ok'){
                        // Remove row with animation if possible, or just remove
                        row.style.transition = 'all 0.3s ease';
                        row.style.opacity = '0';
                        setTimeout(() => {
                            row.remove();
                            // Check if empty
                            const tbody = document.querySelector('.cart-table tbody');
                            const mobileContainer = document.querySelector('.cart-products-mobile');
                            const isTableEmpty = tbody && tbody.children.length === 0;
                            const isMobileEmpty = mobileContainer && mobileContainer.children.length === 0;

                            if(isTableEmpty || isMobileEmpty){
                                location.reload(); // Reload to show empty state
                            }
                        }, 300);

                        if (typeof window.showToast === 'function') {
                            window.showToast('success', 'Thành công', 'Đã xóa sản phẩm khỏi Wishlist');
                        }
                        
                        // Update header count if it exists
                        // Assuming there might be a counter somewhere
                    } else {
                        if (typeof window.showToast === 'function') {
                            window.showToast('error', 'Lỗi', 'Có lỗi xảy ra');
                        }
                    }
                })
                .catch(err => {
                    console.error(err);
                    if (typeof window.showToast === 'function') {
                        window.showToast('error', 'Lỗi', 'Lỗi kết nối');
                    }
                });
            }
            
            // Use custom confirm or browser confirm
            if (typeof ShopAlert !== 'undefined') {
                 // Use ShopAlert if available (from previous context, user asked to handle alerts)
                 // But user specifically asked to "handle alert and toast". 
                 // I will use standard confirm for simplicity unless ShopAlert is strictly required, 
                 // but using Toast for result is key.
                 // Actually, let's just use the browser confirm for the action, and Toast for the result.
            }
            
            if(confirm('Bạn có chắc muốn xóa sản phẩm này khỏi Wishlist?')) doRemove();
        });
    });

    // Handle Add to Cart
    document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
        btn.addEventListener('click', function(e){
            e.preventDefault();
            const variantId = this.getAttribute('data-variant-id');
            const quantity = 1;

            const fd = new FormData();
            fd.append('_token', '{{ csrf_token() }}');
            fd.append('variant_id', variantId);
            fd.append('quantity', quantity);

            fetch('{{ route('cart.add') }}', {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                body: fd
            })
            .then(res => {
                if (!res.ok) throw res;
                return res.json();
            })
            .then(data => {
                if (typeof window.showToast === 'function') {
                    window.showToast('success', 'Thành công', 'Đã thêm vào giỏ hàng');
                }
                // Optionally update mini-cart if functions exist
                // reload page or update cart count?
                // Often sites just show toast.
            })
            .catch(async err => {
                let msg = 'Không thể thêm vào giỏ hàng';
                if (err.json) {
                    const errorData = await err.json();
                    if (errorData.error) msg = errorData.error;
                }
                if (typeof window.showToast === 'function') {
                    window.showToast('error', 'Lỗi', msg);
                }
            });
        });
    });
});
</script>
@endpush