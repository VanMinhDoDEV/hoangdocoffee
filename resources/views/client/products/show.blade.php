@extends('client.layouts.master')

@section('title', $product->name)

@section('content')

    <!-- Product Details Section Start -->
    <div class="product-details-section section section-padding">
        <div class="container">
            <div class="row">

                <!-- Product Image Start -->
                <div class="col-lg-6 col-12 mb-6">
                    <div class="product-details-images">

                        <!-- Product Image Slider Start -->
                        @php
                            $videoUrl = $product->video_url;
                            $videoId = null;
                            $isYoutube = false;
                            if ($videoUrl) {
                                // Simple YouTube ID extraction
                                if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $videoUrl, $matches)) {
                                    $videoId = $matches[1];
                                    $isYoutube = true;
                                }
                            }
                        @endphp

                        <div class="product-image-slider swiper">
                            <div class="swiper-wrapper">
                                @if($videoId)
                                    <div class="swiper-slide">
                                        <div class="ratio ratio-1x1" style="height: 100%; min-height: 400px; display: flex; align-items: center; justify-content: center; background: #000;">
                                            <iframe src="https://www.youtube.com/embed/{{ $videoId }}?enablejsapi=1&rel=0" title="Product Video" allowfullscreen style="width: 100%; height: 100%; border: 0;"></iframe>
                                        </div>
                                    </div>
                                @endif

                                @if($product->images->isNotEmpty())
                                    @foreach($product->images as $image)
                                        <div class="swiper-slide image-zoom">
                                            <img src="{{ asset($image->url) }}" alt="{{ $product->name }}">
                                        </div>
                                    @endforeach
                                @else
                                    <div class="swiper-slide image-zoom">
                                        <img src="{{ asset('assets/images/products/single/single-product-1.png') }}" alt="{{ $product->name }}">
                                    </div>
                                @endif
                            </div>
                            <div class="swiper-pagination d-none"></div>
                            <div class="swiper-button-prev d-none"></div>
                            <div class="swiper-button-next d-none"></div>
                        </div>
                        <!-- Product Image Slider End -->

                        <!-- Product Thumbnail Carousel Start -->
                        <div class="product-thumb-carousel swiper">
                            <div class="swiper-wrapper">
                                @if($videoId)
                                    <div class="swiper-slide video-thumb-slide">
                                        <div class="video-thumb-wrapper" style="position: relative; width: 100%; height: 100%;">
                                            <img src="https://img.youtube.com/vi/{{ $videoId }}/hqdefault.jpg" alt="Product Video Thumb" style="width: 100%; height: 100%; object-fit: cover;">
                                            <div class="video-play-overlay" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 30px; height: 30px; background: rgba(0,0,0,0.6); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                                <i class="sli-control-play" style="color: #fff; font-size: 12px; margin-left: 2px;"></i>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if($product->images->isNotEmpty())
                                    @foreach($product->images as $image)
                                        <div class="swiper-slide">
                                            <img src="{{ asset($image->url) }}" alt="{{ $product->name }}">
                                        </div>
                                    @endforeach
                                @else
                                    <div class="swiper-slide">
                                        <img src="{{ asset('assets/images/products/single/single-product-thumb-1.jpg') }}" alt="{{ $product->name }}">
                                    </div>
                                @endif
                            </div>
                            <div class="swiper-pagination d-none"></div>
                            <div class="swiper-button-prev"></div>
                            <div class="swiper-button-next"></div>
                        </div>
                        <!-- Product Thumbnail Carousel End -->

                    </div>
                </div>
                <!-- Product Image End -->

                <!-- Product Content Start -->
                <div class="col-lg-6 col-12 mb-6">
                    <div class="single-product-content">
                        <div class="simple-breadcrumb mb-2" style="font-size: 13px;">
                            <a href="{{ route('home') }}" class="text-muted text-decoration-none">Trang chủ</a>
                            <span class="mx-1 text-muted">/</span>
                            <a href="{{ route('sanpham.index') }}" class="text-muted text-decoration-none">Sản phẩm</a>
                            @if($product->category)
                                <span class="mx-1 text-muted">/</span>
                                <a href="{{ route('danhmuc.show', $product->category->slug) }}" class="text-muted text-decoration-none">{{ $product->category->name }}</a>
                            @endif
                        </div>
                        
                        <div class="d-flex align-items-center mb-2">
                            @php
                                $avgRating = $reviews->avg('rating') ?? 0;
                            @endphp
                            <div class="product-rating" style="margin-right: 10px; margin-bottom: 0;">
                                <span class="product-rating-bg"><span class="product-rating-active" style="width: {{ ($avgRating / 5) * 100 }}%;"></span></span>
                            </div>
                            <span class="text-muted small" style="font-size: 13px; line-height: 1;">
                                {{ number_format($avgRating, 1) }} <span class="mx-1">/</span> {{ $reviewsCount }} đánh giá
                            </span>
                        </div>

                        <h1 class="single-product-title">{{ $product->name }}</h1>
                        <div class="single-product-price">
                            @if($product->discounted_price)
                                {{ number_format($product->discounted_price) }}đ <del>{{ number_format($product->price) }}đ</del>
                            @else
                                {{ number_format($product->price) }}đ
                            @endif
                        </div>
                        <ul class="single-product-meta">
                            <li><span class="label">SKU :</span> <span class="value sku-value">{{ $product->product_sku ?? 'N/A' }}</span></li>
                            <li><span class="label">Availability :</span> <span class="value stock-status">
                                @if($product->in_stock)
                                    In Stock
                                @else
                                    Out of Stock
                                @endif
                            </span></li>
                        </ul>
                        <div class="single-product-text position-relative">
                            <div id="pd-desc-content" style="overflow: hidden; display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; transition: all 0.3s ease;">
                                <p>{!! strip_tags($product->description) !!}</p>
                            </div>
                            @if(strlen(strip_tags($product->description)) > 150)
                            <div class="text-center mt-2">
                                <button type="button" id="pd-desc-toggle" class="btn btn-sm btn-link text-decoration-none p-0" style="color: #333;">
                                    <i class="sli-arrow-down font-weight-bold" style="font-size: 16px;"></i>
                                </button>
                            </div>
                            @endif
                        </div>

                        <form id="addToCartForm" action="{{ route('cart.add', $product->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="variant_id" id="selectedVariantId" value="">

                            @php
                                // Group options by attribute name
                                $attributes = [];
                                foreach($product->variants as $variant) {
                                    if(!$variant->is_active || $variant->inventory_quantity <= 0) continue;
                                    foreach($variant->options as $option) {
                                        $attrName = $option->attribute->name;
                                        $attrValue = $option->attributeValue->value;
                                        $attrId = $option->attribute->id;
                                        $valId = $option->attributeValue->id;
                                        
                                        if(!isset($attributes[$attrName])) {
                                            $attributes[$attrName] = [
                                                'id' => $attrId,
                                                'values' => []
                                            ];
                                        }
                                        $attributes[$attrName]['values'][$valId] = $attrValue;
                                    }
                                }
                            @endphp

                            @if(count($attributes) > 0)
                                <ul class="single-product-variations">
                                    @foreach($attributes as $name => $data)
                                        <li><span class="label">{{ $name }} :</span>
                                            <div class="value">
                                                <div class="single-product-variation-size-wrap"> <!-- Using size-wrap class for generic radio style -->
                                                    @foreach($data['values'] as $valId => $valText)
                                                        <div class="single-product-variation-size-item">
                                                            <input type="radio" 
                                                                   name="attribute_{{ $data['id'] }}" 
                                                                   id="attr_{{ $data['id'] }}_{{ $valId }}" 
                                                                   value="{{ $valId }}" 
                                                                   class="variant-option"
                                                                   {{ $loop->first ? 'checked' : '' }}>
                                                            <label for="attr_{{ $data['id'] }}_{{ $valId }}">{{ $valText }}</label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif

                            <div class="single-product-actions">
                                <div class="single-product-actions-item">
                                    <div class="product-quantity-count">
                                        <button type="button" class="dec qty-btn">-</button>
                                        <input class="product-quantity-box" type="text" name="quantity" value="1">
                                        <button type="button" class="inc qty-btn">+</button>
                                    </div>
                                </div>
                                <div class="single-product-actions-item">
                                    <button type="button" id="addToCartBtn" class="btn btn-dark btn-primary-hover rounded-0">ADD TO CART</button>
                                </div>
                                <div class="single-product-actions-item">
                                    <button type="button" class="btn btn-icon btn-light btn-primary-hover rounded-0 wishlist-btn" data-product-id="{{ $product->id }}">
                                        <i class="{{ $inWishlist ? 'sli-heart text-danger' : 'sli-heart' }}"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="single-product-buy-now">
                                <button type="button" id="buyBtn" class="btn btn-dark btn-primary-hover rounded-0">Buy it Now</button>
                            </div>
                        </form>
                        
                        <form id="buyForm" action="{{ route('checkout.buy_now') }}" method="POST" style="display:none;">
                            @csrf
                            <input type="hidden" name="variant_id" id="buyVariantId">
                            <input type="hidden" name="quantity" id="buyQuantity">
                        </form>

                        <ul class="single-product-meta">
                            <li><span class="label">Categories :</span> <span class="value links">
                                @if($product->category)
                                    <a href="{{ route('danhmuc.show', $product->category->slug) }}">{{ $product->category->name }}</a>
                                @endif
                            </span></li>
                            <li><span class="label">Share :</span> <span class="value social">
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" target="_blank" rel="noopener noreferrer"><i class="sli-social-facebook" style="font-size: 18px;"></i></a>
                                <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($product->name) }}" target="_blank" rel="noopener noreferrer"><i class="sli-social-twitter" style="font-size: 18px;"></i></a>
                                <a href="https://pinterest.com/pin/create/button/?url={{ urlencode(request()->url()) }}&media={{ $product->images->first() ? asset($product->images->first()->url) : asset('assets/images/products/single/single-product-1.png') }}&description={{ urlencode($product->name) }}" target="_blank" rel="noopener noreferrer"><i class="sli-social-pinterest" style="font-size: 18px;"></i></a>
                                <a href="{{ !empty($storeSettings['instagram_url']) ? $storeSettings['instagram_url'] : 'https://www.instagram.com/' }}" target="_blank" rel="noopener noreferrer" aria-label="Follow us on Instagram"><i class="sli-social-instagram" style="font-size: 18px;"></i></a>
                            </span></li>
                        </ul>
                        <div class="single-product-safe-payment">
                            <p>Phương thức thanh toán</p>
                            @if(!empty($product->payment_method))
                                @php
                                    $paymentMethodLabels = [
                                        'cod' => 'Thanh toán khi nhận hàng (COD)',
                                        'bank_transfer' => 'Chuyển khoản ngân hàng',
                                        'credit' => 'Thẻ tín dụng / Ghi nợ',
                                        'momo' => 'Ví MoMo',
                                        'vnpay' => 'VNPay QR',
                                    ];
                                @endphp
                                <div class="payment-methods-list mt-3 d-flex flex-wrap gap-2">
                                    @foreach(explode(',', $product->payment_method) as $method)
                                        @php $mCode = trim($method); @endphp
                                        <div class="d-flex align-items-center border rounded px-3 py-2 bg-white" style="border-color: #eee !important; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                                            <i class="sli-check text-success me-2" style="font-size: 12px;"></i>
                                            <span class="text-dark" style="font-size: 13px; font-weight: 500;">{{ $paymentMethodLabels[$mCode] ?? $mCode }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <img src="{{ asset('assets/images/footer/footer-payment.png') }}" alt="payment">
                            @endif
                        </div>
                    </div>
                </div>
                <!-- Product Content End -->

            </div>
            <!-- Single Product Top Area End -->

            <!-- Single Product Bottom (Description) Area Start -->
            <div class="single-product-description-area">
                <div class="nav single-product-description-area-nav">
                    <button class="active" data-bs-toggle="tab" data-bs-target="#product-description">Description</button>
                    <button data-bs-toggle="tab" data-bs-target="#product-reviews">Reviews ({{ $reviewsCount }})</button>
                    <button data-bs-toggle="tab" data-bs-target="#product-shipping-policy-tab">Shipping Policy</button>
                </div>
                <div class="tab-content">
                    <!-- Description Start -->
                    <div class="tab-pane fade show active" id="product-description">
                        <div class="single-product-description position-relative">
                            <div id="full-description-content" style="max-height: 500px; overflow: hidden; transition: max-height 0.5s ease; position: relative;">
                                @php
                                    $content = $product->article ?? $product->description;
                                    // Remove specific inline styles that might conflict with dark mode or theme
                                    $content = preg_replace('/background-color:\s*rgb\(255,\s*255,\s*255\);?/i', '', $content);
                                    $content = preg_replace('/color:\s*rgb\(0,\s*0,\s*0\);?/i', '', $content);
                                @endphp
                                {!! $content !!}
                                <div id="description-fade-overlay" style="position: absolute; bottom: 0; left: 0; width: 100%; height: 100px; background: linear-gradient(to bottom, rgba(255,255,255,0), rgba(255,255,255,1)); pointer-events: none;"></div>
                            </div>
                            <div class="text-center mt-4">
                                <button type="button" id="toggle-description-btn" class="btn btn-outline-dark rounded-0 px-5">Xem thêm</button>
                            </div>
                        </div>
                    </div>
                    <!-- Description End -->

                    <!-- Reviews Start -->
                    <div class="tab-pane fade" id="product-reviews">
                        <div class="block-title-2">
                            <h4 class="title">Customer Reviews</h4>
                        </div>

                        <!-- Review List Start -->
                        <div class="review-list">
                            @forelse($reviews as $review)
                                <div class="review-item">
                                    <div class="review-thumb">
                                        <img src="{{ !empty($review->user->avatar) ? asset('storage/' . $review->user->avatar) : asset('assets/images/testimonial/testimonial-1.png') }}" alt="{{ $review->reviewer_name }}">
                                    </div>
                                    <div class="review-content">
                                        <div class="review-rating">
                                            <span class="review-rating-bg"><span class="review-rating-active" style="width: {{ ($review->rating / 5) * 100 }}%"></span></span>
                                        </div>
                                        <div class="review-meta">
                                            <h5 class="review-name">{{ $review->reviewer_name }}</h5>
                                            <span class="review-date">{{ $review->created_at->format('M d, Y') }}</span>
                                        </div>
                                        <p>{{ $review->content }}</p>
                                    </div>
                                </div>
                            @empty
                                <p>No reviews yet.</p>
                            @endforelse
                        </div>
                        <!-- Review List End -->

                        <div class="block-title-2">
                            <h4 class="title">Write a review</h4>
                        </div>

                        <!-- Review Form Start -->
                        <div class="review-form">
                            <form action="{{ route('products.reviews.store', $product->slug) }}" method="POST">
                                @csrf
                                <div class="row g-4">
                                    <div class="col-12">
                                        <label for="review-rating">Rating</label>
                                        <select class="form-field" name="rating" id="review-rating">
                                            <option value="5">Five Stars</option>
                                            <option value="4">Four Stars</option>
                                            <option value="3">Three Stars</option>
                                            <option value="2">Two Stars</option>
                                            <option value="1">One Star</option>
                                        </select>
                                    </div>
                                    @unless(auth()->check())
                                        <div class="col-sm-6">
                                            <label for="review-name">Name</label>
                                            <input class="form-field" id="review-name" name="reviewer_name" type="text" placeholder="Enter your name" required>
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="review-email">Email</label>
                                            <input class="form-field" id="review-email" name="reviewer_email" type="email" placeholder="john.smith@example.com" required>
                                        </div>
                                    @endunless
                                    <div class="col-12">
                                        <label for="review-comment">Body of Review</label>
                                        <textarea class="form-field" id="review-comment" name="content" placeholder="Write your comments here" required></textarea>
                                    </div>
                                    <div class="col-12">
                                        <input type="submit" class="btn btn-dark btn-primary-hover rounded-0" value="Submit Review">
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- Review Form End -->

                    </div>
                    <!-- Reviews End -->

                    <!-- Shipping Policy Start -->
                    <div class="tab-pane fade" id="product-shipping-policy-tab">
                        <div class="block-title-2">
                            <h4 class="title">Shipping policy of our store</h4>
                        </div>
                        <p>Standard shipping: 1-2 business days.</p>
                        <ul>
                            <li>30 days money back guaranty</li>
                            <li>24/7 live support</li>
                        </ul>
                    </div>
                    <!-- Shipping Policy End -->

                </div>
            </div>
            <!-- Single Product Bottom (Description) Area End -->

        </div>
    </div>
    <!-- Product Details Section End -->

    <!-- Related Product Section Start -->
    @if($relatedProducts->count() > 0)
    <div class="h1-product-section section section-padding pt-0">
        <div class="container">
            <div class="section-title section-title-center">
                <p class="title">POPULAR ITEM</p>
                <h2 class="sub-title">Related Products</h2>
            </div>

            <div class="product-carousel swiper">
                <div class="swiper-wrapper">
                    @foreach($relatedProducts as $rProduct)
                        <div class="swiper-slide">
                            <div class="product">
                                <div class="product-thumb">
                                    <a href="{{ route('sanpham.show', $rProduct->slug) }}" class="product-image">
                                        <img loading="lazy" src="{{ $rProduct->images->first() ? asset($rProduct->images->first()->url) : asset('assets/images/products/product-1.png') }}" alt="{{ $rProduct->name }}" width="268" height="306">
                                    </a>
                                    
                                    @if($rProduct->discounted_price)
                                        <div class="product-badge-right">
                                            <span class="product-badge-sale">sale</span>
                                            @php
                                                $discount = 0;
                                                if($rProduct->price > 0) {
                                                    $discount = round((($rProduct->price - $rProduct->discounted_price) / $rProduct->price) * 100);
                                                }
                                            @endphp
                                            <span class="product-badge-sale">-{{ $discount }}%</span>
                                        </div>
                                    @endif

                                    <div class="product-action">
                                        <button class="product-action-btn" data-tooltip-text="Quick View"><i class="sli-magnifier"></i></button>
                                        <button class="product-action-btn" data-tooltip-text="Add to wishlist"><i class="sli-heart"></i></button>
                                        <button class="product-action-btn" data-tooltip-text="Add to cart"><i class="sli-bag"></i></button>
                                    </div>
                                </div>
                                <div class="product-content">
                                    <h5 class="product-title"><a href="{{ route('sanpham.show', $rProduct->slug) }}">{{ $rProduct->name }}</a></h5>
                                    <div class="product-price">
                                        @if($rProduct->discounted_price)
                                            <del>{{ number_format($rProduct->price) }}đ</del> {{ number_format($rProduct->discounted_price) }}đ
                                        @else
                                            {{ number_format($rProduct->price) }}đ
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="swiper-pagination d-none"></div>
                <div class="swiper-button-prev d-none"></div>
                <div class="swiper-button-next d-none"></div>
            </div>
        </div>
    </div>
    @endif
    <!-- Related Product Section End -->

    <style>
        .coffee-product-bar{position:fixed;bottom:0;left:0;right:0;background:#ffffff;box-shadow:0 -4px 20px rgba(0,0,0,.15);z-index:1040;transition:all .3s ease}
        .coffee-product-bar.expanded{max-height:80%;overflow-y:auto}
        .compact-view{display:flex;align-items:center;justify-content:space-between;gap:1rem;cursor:pointer;padding:.5rem 0}
        .compact-left{display:flex;align-items:center;gap:1rem;flex:1}
        .compact-right{display:flex;align-items:center;gap:.75rem}
        .expand-icon{font-size:1.2rem;color:#8B4513;transition:transform .3s ease}
        .expand-icon.rotated{transform:rotate(180deg)}
        .variants-container{max-height:0;overflow:hidden;transition:max-height .3s ease}
        .variants-container.show{max-height:1000px}
        .product-name{font-weight:700;font-size:1.1rem;margin:0;color:#2d3748}
        .current-price.compact{color:#e53e3e;font-size:1.2rem;font-weight:700;margin:0}
        .add-to-cart-btn{background:#D2691E;color:#fff;border:none;padding:.625rem 1.25rem;border-radius:12px;font-weight:600;font-size:.9rem;width:80px;transition:all .3s ease;white-space:nowrap;box-shadow:0 4px 12px rgba(210,105,30,.3)}
        .add-to-cart-btn:hover{background:#B8591A;transform:translateY(-2px);box-shadow:0 8px 16px rgba(210,105,30,.4)}
        .add-to-cart-btn:active{transform:translateY(0)}
        .quantity-selector{display:flex;align-items:center;gap:.5rem}
        .quantity-btn{width:28px;height:28px;border:none;background:#e2e8f0;border-radius:6px;cursor:pointer;font-weight:600;color:#4a5568;transition:all .2s ease}
        .quantity-btn:hover{background:#cbd5e0}
        .quantity-value{font-weight:600;color:#2d3748;min-width:30px;text-align:center}
        .variant-section{margin-bottom:1rem}
        .variant-label{font-size:.9rem;font-weight:600;color:#4a5568;margin-bottom:.5rem;text-align:left}
        .variant-options{display:flex;gap:.5rem;flex-wrap:wrap}
        .variant-btn{padding:.5rem 1rem;border:2px solid #e2e8f0;background:#fff;border-radius:8px;cursor:pointer;transition:all .3s ease;font-size:.9rem;font-weight:500;color:#4a5568}
        .variant-btn:hover{border-color:#8B4513;background:#fef5e7}
        .variant-btn.active{border-color:#8B4513;background:#8B4513;color:#fff}
        .variant-btn.disabled{opacity:.5;pointer-events:none;background:#e9ecef;border-color:#e9ecef;color:#6c757d}
        @media(min-width:992px){.coffee-product-bar{display:none}}
    </style>
    <div class="coffee-product-bar d-lg-none" id="productBar">
        <div class="container py-3">
            <div class="compact-view" onclick="toggleVariantsBar()">
                <div class="compact-left">
                    <div>
                        <h3 class="product-name">{{ $product->name }}</h3>
                        <p class="current-price compact" id="currentPriceCompact">
                            @php
                                $basePrice = $product->discounted_price ?: $product->price;
                            @endphp
                            {{ number_format($basePrice) }}đ
                        </p>
                    </div>
                </div>
                <div class="compact-right">
                    <div class="quantity-selector">
                        <button class="quantity-btn" onclick="changeBottomBarQuantity(-1,event)">−</button>
                        <span class="quantity-value" id="quantityCompact">1</span>
                        <button class="quantity-btn" onclick="changeBottomBarQuantity(1,event)">+</button>
                    </div>
                    <button class="add-to-cart-btn" id="addToCartBtnCompact">Thêm</button>
                    <span class="expand-icon" id="expandIcon">▼</span>
                </div>
            </div>
            <div class="variants-container" id="variantsContainer">
                @php
                    if (!isset($attributes)) {
                        $attributes = [];
                        foreach($product->variants as $variant) {
                            if(!$variant->is_active || $variant->inventory_quantity <= 0) continue;
                            foreach($variant->options as $option) {
                                $attrName = $option->attribute->name;
                                $attrValue = $option->attributeValue->value;
                                $attrId = $option->attribute->id;
                                $valId = $option->attributeValue->id;
                                if(!isset($attributes[$attrName])) {
                                    $attributes[$attrName] = [
                                        'id' => $attrId,
                                        'values' => []
                                    ];
                                }
                                $attributes[$attrName]['values'][$valId] = $attrValue;
                            }
                        }
                    }
                @endphp
                @if(count($attributes) > 0)
                @foreach($attributes as $name => $data)
                <div class="variant-section">
                    <div class="variant-label">{{ $name }}</div>
                    <div class="variant-options">
                        @php $first = true; @endphp
                        @foreach($data['values'] as $valId => $valText)
                        <button class="variant-btn {{ $first ? 'active' : '' }}"
                                data-attr-id="{{ $data['id'] }}"
                                data-val-id="{{ $valId }}"
                                onclick="selectBottomBarAttr(this)">{{ $valText }}</button>
                        @php $first = false; @endphp
                        @endforeach
                    </div>
                </div>
                @endforeach
                @endif
            </div>
        </div>
    </div>
    <div class="d-lg-none" style="height:80px;"></div>

@endsection

@push('scripts')
<script>
    @php
        $jsVariants = $product->variants->map(function($v) {
            return [
                'id' => $v->id,
                'price' => $v->price,
                'compare_at_price' => $v->compare_at_price,
                'sku' => $v->sku, // Use sku from ProductVariant model
                'inventory_quantity' => $v->inventory_quantity,
                'is_active' => $v->is_active,
                'options' => $v->options->map(function($o) {
                    return [
                        'attribute_id' => $o->attribute_id,
                        'attribute_value_id' => $o->attribute_value_id,
                    ];
                }),
                'images_data' => $v->images->map(function($img) {
                    return [
                        'url' => asset($img->url),
                        'product_variant_id' => $img->product_variant_id
                    ];
                })
            ];
        });

        $jsProductImages = $product->images->map(function($img) {
            return [
                'url' => asset($img->url),
                'product_variant_id' => $img->product_variant_id
            ];
        });
    @endphp

    document.addEventListener('DOMContentLoaded', function() {
        const productBarEl = document.getElementById('productBar');
        if (productBarEl && productBarEl.parentNode !== document.body) {
            document.body.appendChild(productBarEl);
        }
        const hasVariantAttributes = !!document.querySelector('#variantsContainer .variant-btn');
        const addToCartBtnCompact = document.getElementById('addToCartBtnCompact');
        const variantsContainerEl = document.getElementById('variantsContainer');
        if (addToCartBtnCompact) {
            if (hasVariantAttributes && (!variantsContainerEl || !variantsContainerEl.classList.contains('show'))) {
                addToCartBtnCompact.textContent = 'Chọn';
            } else {
                addToCartBtnCompact.textContent = 'Thêm';
            }
        }
        
        // Initialize Product Image Slider
        var productThumbCarousel = new Swiper('.product-thumb-carousel', {
            spaceBetween: 10,
            slidesPerView: 4,
            freeMode: true,
            watchSlidesProgress: true,
            navigation: {
                nextEl: '.product-thumb-carousel .swiper-button-next',
                prevEl: '.product-thumb-carousel .swiper-button-prev',
            },
        });

        var productImageSlider = new Swiper('.product-image-slider', {
            spaceBetween: 10,
            navigation: {
                nextEl: '.product-image-slider .swiper-button-next',
                prevEl: '.product-image-slider .swiper-button-prev',
            },
            thumbs: {
                swiper: productThumbCarousel
            }
        });

        // Variant Selection Logic
        const variants = @json($jsVariants);
        const productImages = @json($jsProductImages);
        const videoId = "{{ $videoId ?? '' }}";
        
        const priceEl = document.querySelector('.single-product-price');
        const skuEl = document.querySelector('.sku-value');
        const stockStatusEl = document.querySelector('.stock-status');
        const variantInput = document.getElementById('selectedVariantId');
        
        function updateGallery(images) {
            // Clear existing slides
            productImageSlider.removeAllSlides();
            productThumbCarousel.removeAllSlides();

            let mainSlides = [];
            let thumbSlides = [];

            // Add Image Slides
            if (images.length > 0) {
                images.forEach(img => {
                    mainSlides.push(`<div class="swiper-slide image-zoom"><img src="${img.url}" alt="Product Image"></div>`);
                    thumbSlides.push(`<div class="swiper-slide"><img src="${img.url}" alt="Product Image"></div>`);
                });
            } else {
                 // Fallback to default product image if absolutely no images found
                 mainSlides.push(`<div class="swiper-slide image-zoom"><img src="{{ asset('assets/images/products/single/single-product-1.png') }}" alt="Product Image"></div>`);
                 thumbSlides.push(`<div class="swiper-slide"><img src="{{ asset('assets/images/products/single/single-product-thumb-1.jpg') }}" alt="Product Image"></div>`);
            }

            // Add Video Slide if exists
            if (videoId) {
                const videoSlideHtml = `
                    <div class="swiper-slide">
                        <div class="ratio ratio-1x1" style="height: 100%; min-height: 400px; display: flex; align-items: center; justify-content: center; background: #000;">
                            <iframe src="https://www.youtube.com/embed/${videoId}?enablejsapi=1&rel=0" title="Product Video" allowfullscreen style="width: 100%; height: 100%; border: 0;"></iframe>
                        </div>
                    </div>
                `;
                const videoThumbHtml = `
                    <div class="swiper-slide video-thumb-slide">
                        <div class="video-thumb-wrapper" style="position: relative; width: 100%; height: 100%;">
                            <img src="https://img.youtube.com/vi/${videoId}/hqdefault.jpg" alt="Product Video Thumb" style="width: 100%; height: 100%; object-fit: cover;">
                            <div class="video-play-overlay" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 30px; height: 30px; background: rgba(0,0,0,0.6); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="sli-control-play" style="color: #fff; font-size: 12px; margin-left: 2px;"></i>
                            </div>
                        </div>
                    </div>
                `;
                mainSlides.push(videoSlideHtml);
                thumbSlides.push(videoThumbHtml);
            }

            productImageSlider.appendSlide(mainSlides);
            productThumbCarousel.appendSlide(thumbSlides);
            
            productImageSlider.update();
            productThumbCarousel.update();
            
            // Reset to first slide
            productImageSlider.slideTo(0);
            productThumbCarousel.slideTo(0);
        }

        function checkAvailability() {
            // Check availability for each attribute option
            document.querySelectorAll('.variant-option').forEach(option => {
                const currentAttrId = option.name.replace('attribute_', '');
                const currentValId = parseInt(option.value);

                // Construct a hypothetical selection combining this option with currently selected others
                const hypotheticalSelection = {};
                
                // Get currently selected options for OTHER attributes
                document.querySelectorAll('.variant-option:checked').forEach(radio => {
                    const attrId = radio.name.replace('attribute_', '');
                    if (attrId !== currentAttrId) {
                        hypotheticalSelection[attrId] = parseInt(radio.value);
                    }
                });

                // Add THIS option to the selection
                hypotheticalSelection[currentAttrId] = currentValId;

                // Check if any valid variant matches this combination
                const isValid = variants.some(variant => {
                    // Check if variant matches ALL attributes in the hypothetical selection
                    const matchesSelection = Object.keys(hypotheticalSelection).every(attrId => {
                        const targetValue = hypotheticalSelection[attrId];
                        // Find option in variant with this attribute ID
                        const variantOption = variant.options.find(opt => opt.attribute_id == attrId);
                        // Variant must have this attribute AND the value must match
                        return variantOption && variantOption.attribute_value_id === targetValue;
                    });
                    
                    // Check inventory
                    return matchesSelection && variant.inventory_quantity > 0;
                });

                // Disable/Enable
                const parentDiv = option.closest('.single-product-variation-size-item');
                const label = parentDiv.querySelector('label');

                if (!isValid) {
                    option.disabled = true;
                    parentDiv.classList.add('disabled');
                    parentDiv.style.opacity = '0.5';
                    if (label) label.style.backgroundColor = '#e9ecef'; // Gray background
                    parentDiv.style.backgroundColor = ''; // Remove bg from parent
                    parentDiv.style.pointerEvents = 'none'; // Prevent clicking
                } else {
                    option.disabled = false;
                    parentDiv.classList.remove('disabled');
                    parentDiv.style.opacity = '1';
                    if (label) label.style.backgroundColor = ''; // Reset background
                    parentDiv.style.backgroundColor = ''; // Remove bg from parent
                    parentDiv.style.pointerEvents = 'auto';
                }
            });
        }

        function bottomBarCheckAvailability() {
            const buttons = document.querySelectorAll('#variantsContainer .variant-btn');
            buttons.forEach(btn => {
                const currentAttrId = btn.dataset.attrId;
                const currentValId = parseInt(btn.dataset.valId);
                const hypotheticalSelection = {};
                document.querySelectorAll('#variantsContainer .variant-btn.active').forEach(activeBtn => {
                    const aId = activeBtn.dataset.attrId;
                    const vId = parseInt(activeBtn.dataset.valId);
                    if (aId !== currentAttrId) {
                        hypotheticalSelection[aId] = vId;
                    }
                });
                hypotheticalSelection[currentAttrId] = currentValId;
                const isValid = variants.some(variant => {
                    const matches = Object.keys(hypotheticalSelection).every(attrId => {
                        const vOpt = variant.options.find(opt => opt.attribute_id == attrId);
                        return vOpt && vOpt.attribute_value_id === hypotheticalSelection[attrId];
                    });
                    return matches && variant.inventory_quantity > 0;
                });
                if (!isValid) {
                    btn.classList.add('disabled');
                } else {
                    btn.classList.remove('disabled');
                }
            });
        }

        function updateVariant() {
            // Get selected option values
            const selectedOptions = {};
            document.querySelectorAll('.variant-option:checked').forEach(radio => {
                const attrId = radio.name.replace('attribute_', '');
                selectedOptions[attrId] = parseInt(radio.value);
            });

            // Find matching variant
            const matchingVariant = variants.find(variant => {
                // Ensure option count matches (avoids partial matches or empty matches)
                if (variant.options.length !== Object.keys(selectedOptions).length) {
                    return false;
                }
                return variant.options.every(option => {
                    return selectedOptions[option.attribute_id] === option.attribute_value_id;
                });
            });
            
            const addToCartBtn = document.getElementById('addToCartBtn');
            const buyBtn = document.getElementById('buyBtn');

            if (matchingVariant && matchingVariant.inventory_quantity > 0) {
                // Update UI
                if (matchingVariant.compare_at_price) {
                    priceEl.innerHTML = new Intl.NumberFormat('vi-VN').format(matchingVariant.price) + 'đ <del>' + new Intl.NumberFormat('vi-VN').format(matchingVariant.compare_at_price) + 'đ</del>';
                } else {
                    priceEl.innerHTML = new Intl.NumberFormat('vi-VN').format(matchingVariant.price) + 'đ';
                }
                updateBottomBarPrice(matchingVariant.price, matchingVariant.compare_at_price);
                
                skuEl.textContent = matchingVariant.sku || 'N/A';
                stockStatusEl.textContent = 'In Stock';
                variantInput.value = matchingVariant.id;

                // Enable Buttons
                if(addToCartBtn) {
                    addToCartBtn.disabled = false;
                    addToCartBtn.textContent = 'Add to Cart';
                    addToCartBtn.classList.remove('disabled');
                }
                if(buyBtn) {
                    buyBtn.disabled = false;
                    buyBtn.classList.remove('disabled');
                }

                // Update Images
                let imagesToShow = matchingVariant.images_data;
                if (!imagesToShow || imagesToShow.length === 0) {
                     // Fallback to main product images (null variant_id)
                     imagesToShow = productImages.filter(img => img.product_variant_id === null);
                     // If still empty, use all
                     if (imagesToShow.length === 0) imagesToShow = productImages;
                }
                updateGallery(imagesToShow);

            } else {
                priceEl.innerHTML = 'Unavailable';
                skuEl.textContent = 'N/A';
                stockStatusEl.textContent = 'Unavailable';
                variantInput.value = '';
                updateBottomBarPrice(0, null);
                
                // Disable Buttons
                if(addToCartBtn) {
                    addToCartBtn.disabled = true;
                    addToCartBtn.textContent = 'Unavailable';
                    addToCartBtn.classList.add('disabled');
                }
                if(buyBtn) {
                    buyBtn.disabled = true;
                    buyBtn.classList.add('disabled');
                }
            }
            
            // Re-check availability of other options based on new selection
            checkAvailability();
            bottomBarCheckAvailability();
        }

        // Add event listeners to radio buttons
        document.querySelectorAll('.variant-option').forEach(radio => {
            radio.addEventListener('change', updateVariant);
        });

        // Initial update
        updateVariant();
        bottomBarCheckAvailability();

        // Add to Cart Logic
        const addToCartBtn = document.getElementById('addToCartBtn');
        if(addToCartBtn) {
            addToCartBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const variantId = document.getElementById('selectedVariantId').value;
                if (!variantId) {
                    alert('Please select a valid variant');
                    return;
                }
                const form = document.getElementById('addToCartForm');
                const formData = new FormData(form);

                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => { throw new Error(err.error || 'Lỗi thêm vào giỏ hàng'); });
                    }
                    return response.json();
                })
                .then(data => {
                    // Update mini cart
                    if(data.html) {
                        const cartProducts = document.querySelector('.header-cart-products');
                        if(cartProducts) cartProducts.innerHTML = data.html;
                    }
                    if(data.count !== undefined) {
                        const countEl = document.querySelector('.header-action-toggle .count');
                        if(countEl) countEl.textContent = data.count;
                    }
                    if (typeof updateMiniCartFooter === 'function') {
                        updateMiniCartFooter(data);
                    }
                    
                    // Open mini cart
                    var offcanvasElement = document.getElementById('offcanvas-cart');
                    if(offcanvasElement) {
                        var offcanvas = bootstrap.Offcanvas.getInstance(offcanvasElement);
                        if (!offcanvas) {
                            offcanvas = new bootstrap.Offcanvas(offcanvasElement);
                        }
                        offcanvas.show();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert(error.message);
                });
            });
        }

        // Buy Now Logic
        const buyBtn = document.getElementById('buyBtn');
        if(buyBtn) {
            buyBtn.addEventListener('click', function() {
                const variantId = document.getElementById('selectedVariantId').value;
                const quantity = document.querySelector('input[name="quantity"]').value;
                
                if (!variantId) {
                    alert('Please select a valid variant');
                    return;
                }

                document.getElementById('buyVariantId').value = variantId;
                document.getElementById('buyQuantity').value = quantity;
                document.getElementById('buyForm').submit();
            });
        }

        function updateBottomBarPrice(price, compareAt) {
            const el = document.getElementById('currentPriceCompact');
            if (!el) return;
            if (compareAt) {
                el.textContent = new Intl.NumberFormat('vi-VN').format(price) + 'đ';
            } else {
                el.textContent = new Intl.NumberFormat('vi-VN').format(price) + 'đ';
            }
        }

        window.toggleVariantsBar = function() {
            const container = document.getElementById('variantsContainer');
            const icon = document.getElementById('expandIcon');
            const bar = document.getElementById('productBar');
            if (!container || !icon || !bar) return;
            const showing = container.classList.contains('show');
            if (showing) {
                container.classList.remove('show');
                icon.classList.remove('rotated');
                bar.classList.remove('expanded');
                if (hasVariantAttributes && addToCartBtnCompact) addToCartBtnCompact.textContent = 'Chọn';
            } else {
                container.classList.add('show');
                icon.classList.add('rotated');
                bar.classList.add('expanded');
                if (addToCartBtnCompact) addToCartBtnCompact.textContent = 'Thêm';
            }
        };

        window.selectBottomBarAttr = function(btn) {
            const attrId = btn.dataset.attrId;
            const valId = btn.dataset.valId;
            document.querySelectorAll('.variant-btn[data-attr-id="'+attrId+'"]').forEach(b=>b.classList.remove('active'));
            btn.classList.add('active');
            const radio = document.getElementById('attr_'+attrId+'_'+valId);
            if (radio) {
                radio.checked = true;
                radio.dispatchEvent(new Event('change'));
            }
        };

        window.changeBottomBarQuantity = function(delta, e) {
            if (e) e.stopPropagation();
            const input = document.querySelector('input[name="quantity"]');
            const display = document.getElementById('quantityCompact');
            if (!input || !display) return;
            let current = parseInt(input.value || '1', 10);
            const next = Math.min(99, Math.max(1, current + delta));
            input.value = next;
            display.textContent = next;
        };

        if (addToCartBtnCompact) {
            addToCartBtnCompact.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const container = document.getElementById('variantsContainer');
                if (hasVariantAttributes && container && !container.classList.contains('show')) {
                    window.toggleVariantsBar();
                    return;
                }
                const variantId = document.getElementById('selectedVariantId').value;
                if (!variantId) {
                    alert('Please select a valid variant');
                    return;
                }
                const form = document.getElementById('addToCartForm');
                const formData = new FormData(form);
                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                }).then(response => {
                    if (!response.ok) {
                        return response.json().then(err => { throw new Error(err.error || 'Lỗi thêm vào giỏ hàng'); });
                    }
                    return response.json();
                }).then(data => {
                    if(data.html) {
                        const cartProducts = document.querySelector('.header-cart-products');
                        if(cartProducts) cartProducts.innerHTML = data.html;
                    }
                    if(data.count !== undefined) {
                        const countEl = document.querySelector('.header-action-toggle .count');
                        if(countEl) countEl.textContent = data.count;
                    }
                    if(data.subtotal_format) {
                        const totalEl = document.querySelector('.header-cart-total span');
                        if(totalEl) totalEl.textContent = data.subtotal_format;
                    }
                    var offcanvasElement = document.getElementById('offcanvas-cart');
                    if(offcanvasElement) {
                        var offcanvas = bootstrap.Offcanvas.getInstance(offcanvasElement);
                        if (!offcanvas) {
                            offcanvas = new bootstrap.Offcanvas(offcanvasElement);
                        }
                        offcanvas.show();
                    }
                }).catch(error => {
                    alert(error.message);
                });
            });
        }

        // Wishlist Logic
        const wishlistBtn = document.querySelector('.wishlist-btn');
        if (wishlistBtn) {
            wishlistBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const btn = this;
                const icon = btn.querySelector('i');
                const productId = btn.dataset.productId;
                
                // Get selected variant if any
                const variantIdInput = document.getElementById('selectedVariantId');
                const variantId = variantIdInput ? variantIdInput.value : null;

                if (typeof ShopLoading !== 'undefined') ShopLoading.start(btn);

                const formData = new FormData();
                formData.append('product_id', productId);
                if(variantId) formData.append('product_variant_id', variantId);
                formData.append('_token', "{{ csrf_token() }}");

                fetch("{{ route('client.wishlist.add') }}", {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(response => {
                    if (response.status === 401) {
                        window.location.href = "{{ route('login') }}";
                        throw new Error('Unauthenticated');
                    }
                    if (!response.ok) throw new Error('Failed');
                    return response.json();
                })
                .then(data => {
                    if (typeof ShopLoading !== 'undefined') ShopLoading.stop(btn);
                    if (data.status === 'ok') {
                        icon.className = 'sli-heart text-danger';
                        if (typeof showToast === 'function') showToast('success', 'Thành công', 'Đã thêm vào yêu thích');
                    } else if (data.status === 'removed') {
                        icon.className = 'sli-heart';
                        if (typeof showToast === 'function') showToast('success', 'Thành công', 'Đã xóa khỏi yêu thích');
                    }
                })
                .catch(error => {
                    if (typeof ShopLoading !== 'undefined') ShopLoading.stop(btn);
                    if (error.message !== 'Unauthenticated') {
                        if (typeof showToast === 'function') showToast('error', 'Lỗi', 'Có lỗi xảy ra');
                    }
                });
            });
        }

        // Description Toggle Logic
        const pdDescToggle = document.getElementById('pd-desc-toggle');
        const pdDescContent = document.getElementById('pd-desc-content');
        if(pdDescToggle && pdDescContent) {
            pdDescToggle.addEventListener('click', () => {
                if(pdDescContent.style.webkitLineClamp === '1') {
                    pdDescContent.style.webkitLineClamp = 'unset';
                    pdDescToggle.innerHTML = '<i class="sli-arrow-up font-weight-bold" style="font-size: 16px;"></i>';
                } else {
                    pdDescContent.style.webkitLineClamp = '1';
                    pdDescToggle.innerHTML = '<i class="sli-arrow-down font-weight-bold" style="font-size: 16px;"></i>';
                }
            });
        }

        // Full Description Toggle (Article)
        const fullDescContent = document.getElementById('full-description-content');
        const toggleDescBtn = document.getElementById('toggle-description-btn');
        const fadeOverlay = document.getElementById('description-fade-overlay');
        
        if (fullDescContent && toggleDescBtn) {
            // Check if content is actually taller than max-height
            if (fullDescContent.scrollHeight <= 500) {
                toggleDescBtn.style.display = 'none';
                if(fadeOverlay) fadeOverlay.style.display = 'none';
                fullDescContent.style.maxHeight = 'none';
            }

            toggleDescBtn.addEventListener('click', function() {
                if (fullDescContent.style.maxHeight !== 'none') {
                    fullDescContent.style.maxHeight = 'none';
                    this.textContent = 'Thu gọn';
                    if(fadeOverlay) fadeOverlay.style.display = 'none';
                } else {
                    fullDescContent.style.maxHeight = '500px';
                    this.textContent = 'Xem thêm';
                    if(fadeOverlay) fadeOverlay.style.display = 'block';
                    // Scroll back to top of description area
                    document.querySelector('.single-product-description-area').scrollIntoView({ behavior: 'smooth' });
                }
            });
        }
    });
</script>
@endpush
