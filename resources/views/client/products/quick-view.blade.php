@php
    $imagesData = ($product->images ?? collect())->map(function($i){
        return ['url' => $i->url, 'is_primary' => $i->is_primary];
    })->values()->all();

    $variantsData = ($product->variants ?? collect())->map(function($v){
        $options = [];
        foreach (($v->options ?? collect()) as $opt) {
            $key = strtolower((string)(optional($opt->attribute)->name ?? ''));
            $val = (string)(optional($opt->attributeValue)->value ?? '');
            if ($key && $val) { $options[$key] = $val; }
        }
        return [
            'id' => $v->id,
            'sku' => $v->sku,
            'price' => (float)($v->price ?? 0),
            'compare_at_price' => (float)($v->compare_at_price ?? 0),
            'stock' => (int)($v->inventory_quantity ?? 0),
            'options' => $options,
            'images' => ($v->images ?? collect())->pluck('url')->all(),
        ];
    })->values()->all();
@endphp

<div class="row row-cols-md-2 row-cols-1 mb-n6">
    <!-- Product Image Start -->
    <div class="col mb-6">
        <div class="single-product-image">
            <!-- Product Badge Start -->
            <div class="single-product-badge-left">
                @if($product->is_new)
                    <span class="single-product-badge-new">new</span>
                @endif
            </div>
            <div class="single-product-badge-right">
                @if($product->compare_at_price > $product->price)
                    <span class="single-product-badge-sale">sale</span>
                    <span class="single-product-badge-sale">-{{ round((($product->compare_at_price - $product->price) / $product->compare_at_price) * 100) }}%</span>
                @endif
            </div>
            <!-- Product Badge End -->

            <!-- Product Image Slider Start -->
            <div class="quickview-product-image-slider swiper" id="qv-main-slider">
                <div class="swiper-wrapper" id="qv-main-wrapper">
                    <!-- Images will be injected here -->
                </div>
                <div class="swiper-pagination d-none"></div>
                <div class="swiper-button-prev d-none"></div>
                <div class="swiper-button-next d-none"></div>
            </div>
            <!-- Product Image Slider End -->

            <!-- Product Thumbnail Carousel Start -->
            <div class="quickview-product-thumb-carousel swiper" id="qv-thumb-slider">
                <div class="swiper-wrapper" id="qv-thumb-wrapper">
                    <!-- Thumbnails will be injected here -->
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
    <div class="col mb-6">
        <div class="single-product-content">
            <h1 class="single-product-title"><a href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a></h1>
            <div class="single-product-price" id="qv-price-display">
                {{ number_format($product->price, 0, ',', '.') }}đ
            </div>
            <ul class="single-product-meta">
                <li><span class="label">Tình trạng:</span> <span class="value" id="qv-stock-status">Checking...</span></li>
                <li><span class="label">SKU:</span> <span class="value" id="qv-sku-display"></span></li>
            </ul>
            <div class="single-product-text position-relative">
                <div id="qv-desc-content" style="overflow: hidden; display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; transition: all 0.3s ease;">
                    <p>{{ $product->description }}</p>
                </div>
                @if(strlen(strip_tags($product->description)) > 50) 
                <div class="text-center mt-2">
                    <button type="button" id="qv-desc-toggle" class="btn btn-sm btn-link text-decoration-none p-0" style="color: #333;">
                        <i class="sli-arrow-down font-weight-bold" style="font-size: 16px;"></i>
                    </button>
                </div>
                @endif
            </div>
            
            <ul class="single-product-variations" id="qv-variations-list">
                <!-- Variations will be injected here -->
            </ul>

            <form id="qv-cart-form" action="{{ route('cart.add') }}" method="POST">
                @csrf
                <input type="hidden" name="variant_id" id="qv-variant-id">
                
                <div class="single-product-actions">
                    <div class="single-product-actions-item">
                        <div class="product-quantity-count">
                            <button type="button" class="dec qty-btn">-</button>
                            <input class="product-quantity-box" type="number" name="quantity" value="1" min="1" id="qv-quantity">
                            <button type="button" class="inc qty-btn">+</button>
                        </div>
                    </div>
                    <div class="single-product-actions-item">
                        <button type="submit" id="qv-add-to-cart-btn" class="btn btn-dark btn-primary-hover rounded-0">THÊM VÀO GIỎ</button>
                    </div>
                    <div class="single-product-actions-item">
                        <button type="button" class="btn btn-icon btn-light btn-primary-hover rounded-0 qv-wishlist-btn" data-product-id="{{ $product->id }}">
                            <i class="{{ $inWishlist ? 'sli-heart text-danger' : 'sli-heart' }}"></i>
                        </button>
                    </div>
                    <div class="single-product-actions-item d-block">
                        <button type="button" id="qv-buy-now-btn" class="btn btn-primary btn-primary-hover rounded-0 ms-2">MUA NGAY</button>
                    </div>
                </div>
            </form>

            <ul class="single-product-meta">
                <li><span class="label">Danh mục:</span> 
                    <span class="value links">
                        @if($product->category)
                            <a href="#">{{ $product->category->name }}</a>
                        @endif
                    </span>
                </li>
                <li><span class="label">Share:</span> 
                    <span class="value social">
                        <a href="#"><img src="{{ asset('assets/images/icons/social/facebook.png') }}" alt="facebook"></a>
                        <a href="#"><img src="{{ asset('assets/images/icons/social/twitter.png') }}" alt="twitter"></a>
                        <a href="#"><img src="{{ asset('assets/images/icons/social/pinterest.png') }}" alt="pinterest"></a>
                    </span>
                </li>
            </ul>
            <div class="single-product-safe-payment">
                <p>Guaranteed safe checkout</p>
                <img src="{{ asset('assets/images/footer/footer-payment.png') }}" alt="payment">
            </div>
        </div>
    </div>
    <!-- Product Content End -->
</div>

<script>
(function() {
    const productImages = @json($imagesData);
    const variants = @json($variantsData);
    
    // Helper: Normalize string for ID
    function slugify(text) {
        return text.toString().toLowerCase()
            .replace(/\s+/g, '-')           // Replace spaces with -
            .replace(/[^\w\-]+/g, '')       // Remove all non-word chars
            .replace(/\-\-+/g, '-')         // Replace multiple - with single -
            .replace(/^-+/, '')             // Trim - from start of text
            .replace(/-+$/, '');            // Trim - from end of text
    }

    // --- Slider Logic ---
    function renderImages(urls) {
        const mainWrapper = document.getElementById('qv-main-wrapper');
        const thumbWrapper = document.getElementById('qv-thumb-wrapper');
        
        if(!mainWrapper || !thumbWrapper) return;
        
        const mainHTML = urls.map(url => `
            <div class="swiper-slide">
                <img src="${url}" alt="Product Image" style="width:100%; height:auto;">
            </div>
        `).join('');
        
        const thumbHTML = urls.map(url => `
            <div class="swiper-slide">
                <img src="${url}" alt="Thumbnail" style="width:100%; height:auto;">
            </div>
        `).join('');
        
        mainWrapper.innerHTML = mainHTML;
        thumbWrapper.innerHTML = thumbHTML;

        // Init Swiper
        if (typeof Swiper !== 'undefined') {
            try {
                var thumbSwiper = new Swiper("#qv-thumb-slider", {
                    spaceBetween: 10,
                    slidesPerView: 4,
                    freeMode: true,
                    watchSlidesProgress: true,
                    navigation: {
                        nextEl: ".swiper-button-next",
                        prevEl: ".swiper-button-prev",
                    },
                });
                var mainSwiper = new Swiper("#qv-main-slider", {
                    spaceBetween: 10,
                    navigation: {
                        nextEl: ".swiper-button-next",
                        prevEl: ".swiper-button-prev",
                    },
                    thumbs: {
                        swiper: thumbSwiper,
                    },
                });
            } catch(e) { console.error('Swiper init error', e); }
        }
    }

    // --- Variant Logic ---
    let attributes = {};
    variants.forEach(v => {
        Object.entries(v.options).forEach(([k, val]) => {
            if (!attributes[k]) attributes[k] = new Set();
            attributes[k].add(val);
        });
    });
    
    // Convert Sets to Arrays
    Object.keys(attributes).forEach(k => attributes[k] = Array.from(attributes[k]));

    let selected = {};
    // Default selection
    if (variants.length > 0) {
        Object.entries(variants[0].options).forEach(([k, v]) => selected[k] = v);
    }

    function renderVariations() {
        const container = document.getElementById('qv-variations-list');
        if (!container) return;
        container.innerHTML = '';

        Object.entries(attributes).forEach(([attrName, values]) => {
            const li = document.createElement('li');
            
            const labelSpan = document.createElement('span');
            labelSpan.className = 'label';
            labelSpan.textContent = attrName + ' :';
            li.appendChild(labelSpan);

            const valueDiv = document.createElement('div');
            valueDiv.className = 'value';
            
            const wrapDiv = document.createElement('div');
            wrapDiv.className = `single-product-variation-size-wrap single-product-variation-${slugify(attrName)}-wrap d-flex flex-wrap gap-2`; 
            
            values.forEach(val => {
                const itemDiv = document.createElement('div');
                itemDiv.className = `single-product-variation-${slugify(attrName)}-item me-2`; 
                
                const uniqueId = `qv-attr-${slugify(attrName)}-${slugify(val)}`;
                
                const input = document.createElement('input');
                input.type = 'radio';
                input.name = `qv-${slugify(attrName)}`;
                input.id = uniqueId;
                input.value = val;
                input.className = 'qv-variant-option'; // Added class for easier selection
                input.dataset.attribute = attrName; // Store attribute name
                
                if (selected[attrName] === val) input.checked = true;
                
                input.addEventListener('change', () => {
                    selected[attrName] = val;
                    updateSelectedVariant();
                    checkAvailability(); // Check availability on change
                });

                const label = document.createElement('label');
                label.htmlFor = uniqueId;
                label.textContent = val;
                
                itemDiv.appendChild(input);
                itemDiv.appendChild(label);
                wrapDiv.appendChild(itemDiv);
            });

            valueDiv.appendChild(wrapDiv);
            li.appendChild(valueDiv);
            container.appendChild(li);
        });
        checkAvailability(); // Initial check
    }

    function checkAvailability() {
        document.querySelectorAll('.qv-variant-option').forEach(option => {
            const currentAttr = option.dataset.attribute;
            const currentVal = option.value;

            // Construct hypothetical selection
            const hypothetical = { ...selected };
            hypothetical[currentAttr] = currentVal;

            // Check if any variant matches this hypothetical selection
            // We need to check if there exists a variant that matches ALL attributes in 'hypothetical'
            // BUT, for attributes NOT in 'hypothetical' (if any), they can be anything.
            // Actually, 'selected' contains ALL attributes (since we init from variants[0]), 
            // so 'hypothetical' also contains ALL attributes.
            
            const isValid = variants.some(v => {
                return Object.entries(hypothetical).every(([k, val]) => v.options[k] === val) && v.stock > 0;
            });

            // However, the standard logic often checks if this option is valid given the OTHER selected options.
            // Let's refine: valid if there is a variant matching (other_selected + this_option).
            
            const parentDiv = option.closest(`[class*="single-product-variation-"]`);
            const label = parentDiv.querySelector('label');

            if (!isValid) {
                option.disabled = true;
                parentDiv.classList.add('disabled');
                parentDiv.style.opacity = '0.5';
                parentDiv.style.pointerEvents = 'none';
                if(label) label.style.backgroundColor = '#f1f1f1';
            } else {
                option.disabled = false;
                parentDiv.classList.remove('disabled');
                parentDiv.style.opacity = '1';
                parentDiv.style.pointerEvents = 'auto';
                if(label) label.style.backgroundColor = '';
            }
        });
    }

    function findVariant() {
        return variants.find(v => {
            return Object.entries(selected).every(([k, val]) => v.options[k] === val);
        }) || null;
    }

    function updateSelectedVariant() {
        const v = findVariant();
        const priceDisplay = document.getElementById('qv-price-display');
        const skuDisplay = document.getElementById('qv-sku-display');
        const stockStatus = document.getElementById('qv-stock-status');
        const variantInput = document.getElementById('qv-variant-id');
        const addBtn = document.getElementById('qv-add-to-cart-btn');

        if (v) {
            // Update Price
            let priceHTML = v.price.toLocaleString('vi-VN') + 'đ';
            if (v.compare_at_price > v.price) {
                priceHTML += ` <del>${v.compare_at_price.toLocaleString('vi-VN')}đ</del>`;
            }
            priceDisplay.innerHTML = priceHTML;
            
            // Update SKU & Stock
            skuDisplay.textContent = v.sku || 'N/A';
            stockStatus.textContent = (v.stock > 0) ? 'Còn hàng' : 'Hết hàng';
            
            // Update Hidden Input
            variantInput.value = v.id;
            
            // Update Images if variant has specific ones
            if (v.images && v.images.length > 0) {
                renderImages(v.images);
            } else {
                // If no specific variant images, fallback to product images
                 // But wait, if we are switching variants, we might want to keep current if no new ones?
                 // No, usually reset to main.
                 // Ideally check if image list changed.
                 renderImages(productImages.map(i => i.url));
            }

            // Button State
            if (v.stock > 0) {
                addBtn.disabled = false;
                addBtn.textContent = 'THÊM VÀO GIỎ';
            } else {
                addBtn.disabled = true;
                addBtn.textContent = 'HẾT HÀNG';
            }
        } else {
            priceDisplay.textContent = '---';
            skuDisplay.textContent = '---';
            stockStatus.textContent = 'Hết hàng';
            addBtn.disabled = true;
            addBtn.textContent = 'HẾT HÀNG';
        }
    }

    // --- Quantity Logic ---
    const qInput = document.getElementById('qv-quantity');
    const incBtn = document.querySelector('.inc.qty-btn');
    const decBtn = document.querySelector('.dec.qty-btn');

    if (qInput && incBtn && decBtn) {
        incBtn.addEventListener('click', () => {
            qInput.value = parseInt(qInput.value || 0) + 1;
        });
        decBtn.addEventListener('click', () => {
            const val = parseInt(qInput.value || 0);
            if (val > 1) qInput.value = val - 1;
        });
    }

    // --- Description Toggle ---
    const descToggle = document.getElementById('qv-desc-toggle');
    const descContent = document.getElementById('qv-desc-content');
    if(descToggle && descContent) {
        descToggle.addEventListener('click', () => {
            if(descContent.style.webkitLineClamp === '1') {
                descContent.style.webkitLineClamp = 'unset';
                descToggle.innerHTML = '<i class="sli-arrow-up font-weight-bold" style="font-size: 16px;"></i>';
            } else {
                descContent.style.webkitLineClamp = '1';
                descToggle.innerHTML = '<i class="sli-arrow-down font-weight-bold" style="font-size: 16px;"></i>';
            }
        });
    }

    // --- Cart & Buy Now Logic ---
    const cartForm = document.getElementById('qv-cart-form');
    const addToCartBtn = document.getElementById('qv-add-to-cart-btn');
    const buyNowBtn = document.getElementById('qv-buy-now-btn');

    if (cartForm) {
        // Add to Cart (AJAX)
        if (addToCartBtn) {
            addToCartBtn.addEventListener('click', function(e) {
                e.preventDefault();
                
                if (!document.getElementById('qv-variant-id').value) {
                    if (typeof showToast === 'function') showToast('error', 'Lỗi', 'Vui lòng chọn phân loại hàng');
                    else alert('Vui lòng chọn phân loại hàng');
                    return;
                }

                const formData = new FormData(cartForm);
                
                if (typeof ShopLoading !== 'undefined') {
                    ShopLoading.start(addToCartBtn);
                } else {
                    addToCartBtn.disabled = true;
                    addToCartBtn.textContent = 'Đang thêm...';
                }

                fetch("{{ route('cart.add') }}", {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => { throw err; });
                    }
                    return response.json();
                })
                .then(data => {
                    if (typeof showToast === 'function') showToast('success', 'Thành công', 'Đã thêm vào giỏ hàng');
                    else alert('Đã thêm vào giỏ hàng');
                    
                    // Update header cart count
                    if(data.count !== undefined) {
                         document.querySelectorAll('.header-cart-count, .cart-count, .header-action-toggle .count').forEach(el => el.textContent = data.count);
                    }

                    // Update Mini Cart HTML
                    if(data.html) {
                        const cartProducts = document.querySelector('.header-cart-products');
                        if(cartProducts) cartProducts.innerHTML = data.html;
                    }
                    if(data.subtotal_format) {
                        const totalElements = document.querySelectorAll('.header-cart-total .amount, .header-cart-total span');
                        totalElements.forEach(el => el.textContent = data.subtotal_format);
                    }

                    // Open Mini Cart Offcanvas
                    // Close Quick View Modal first if needed, or just open Cart over it
                    const quickViewModalEl = document.getElementById('exampleProductModal');
                    if (quickViewModalEl) {
                        const modalInstance = bootstrap.Modal.getInstance(quickViewModalEl);
                        if (modalInstance) {
                            modalInstance.hide();
                        }
                    }

                    const cartToggle = document.querySelector('[data-bs-target="#offcanvas-cart"]');
                    if (cartToggle) {
                        // Check if offcanvas is already open
                        const offcanvasEl = document.getElementById('offcanvas-cart');
                        if (offcanvasEl && !offcanvasEl.classList.contains('show')) {
                                cartToggle.click();
                        }
                    }
                })
                .catch(error => {
                    console.error(error);
                    let msg = 'Có lỗi xảy ra';
                    if (error.errors && error.errors.stock) msg = error.errors.stock[0];
                    else if (error.error) msg = error.error;
                    
                    if (typeof showToast === 'function') showToast('error', 'Lỗi', msg);
                    else alert(msg);
                })
                .finally(() => {
                    if (typeof ShopLoading !== 'undefined') {
                        ShopLoading.stop(addToCartBtn);
                    } else {
                        addToCartBtn.disabled = false;
                        addToCartBtn.textContent = originalText;
                    }
                });
            });
        }

        // Buy Now (Direct Submit)
        if (buyNowBtn) {
            buyNowBtn.addEventListener('click', function(e) {
                e.preventDefault();
                if (!document.getElementById('qv-variant-id').value) {
                    if (typeof showToast === 'function') showToast('error', 'Lỗi', 'Vui lòng chọn phân loại hàng');
                    else alert('Vui lòng chọn phân loại hàng');
                    return;
                }
                
                cartForm.action = "{{ route('checkout.buy_now') }}";
                cartForm.submit();
            });
        }
    }

    // --- Wishlist Logic ---
    const wishlistBtn = document.querySelector('.qv-wishlist-btn');
    if (wishlistBtn) {
        wishlistBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const btn = this;
            const icon = btn.querySelector('i');
            const productId = btn.dataset.productId;
            const variantId = document.getElementById('qv-variant-id').value;

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

    // --- Initialize ---
    renderVariations();
    updateSelectedVariant();

})();
</script>
