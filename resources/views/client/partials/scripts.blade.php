    <!-- Vendors JS -->
<script src="{{ asset('assets/js/vendor/modernizr-3.11.7.min.js') }}"></script>
<script src="{{ asset('assets/js/vendor/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('assets/js/vendor/jquery-migrate-3.3.2.min.js') }}"></script>
<script src="{{ asset('assets/js/vendor/bootstrap.bundle.min.js') }}"></script>

<!-- Plugins JS -->
<script src="{{ asset('assets/js/plugins/swiper-bundle.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/jquery.countdown.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/svg-inject.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/jquery.magnific-popup.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/ion.rangeSlider.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/jquery.zoom.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/resize-sensor.js') }}"></script>
<script src="{{ asset('assets/js/plugins/jquery.sticky-sidebar.min.js') }}"></script>

<!-- Activation JS -->
<script src="{{ asset('assets/js/active.js') }}"></script>

<script>
    // Centralized Loading Handler
    // Add styles dynamically for spinner if not present
    (function() {
        if (!document.getElementById('shop-loading-styles')) {
            const style = document.createElement('style');
            style.id = 'shop-loading-styles';
            style.innerHTML = `
                @keyframes shop-spin {
                    from { transform: rotate(0deg); }
                    to { transform: rotate(360deg); }
                }
                .shop-animate-spin {
                    animation: shop-spin 1s linear infinite;
                }
                .shop-loading-icon {
                    display: inline-block;
                    vertical-align: middle;
                    width: 1.25em;
                    height: 1.25em;
                }
            `;
            document.head.appendChild(style);
        }
    })();

    window.ShopLoading = {
        originalContents: new WeakMap(),
        
        start: function(element) {
            if (!element) return;
            
            // Save original content
            if (!this.originalContents.has(element)) {
                this.originalContents.set(element, element.innerHTML);
            }
            
            element.disabled = true;
            element.classList.add('disabled', 'loading');
            
            // Use custom class to ensure animation works without Tailwind
            const spinnerHtml = `<svg class="shop-animate-spin shop-loading-icon text-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle style="opacity: 0.25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path style="opacity: 0.75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>`;
            
            // Logic: If button contains text, prepend spinner. If icon only, replace.
            const textContent = element.textContent.trim();
            if (textContent.length > 0 && !element.classList.contains('btn-icon')) {
                // Button with text
                element.innerHTML = `${spinnerHtml} <span style="opacity: 0.75">${textContent}</span>`;
            } else {
                // Icon button or empty
                element.innerHTML = spinnerHtml;
            }
        },
        
        stop: function(element) {
            if (!element) return;
            
            setTimeout(() => {
                element.disabled = false;
                element.classList.remove('disabled', 'loading');
                
                if (this.originalContents.has(element)) {
                    element.innerHTML = this.originalContents.get(element);
                    // Don't delete from WeakMap to allow reuse if needed, 
                    // or delete if we want to ensure fresh state next time.
                    // Keeping it is fine.
                }
            }, 300); // Small delay for smoothness
        }
    };

    function updateMiniCartFooter(data) {
        const container = document.getElementById('header-cart-totals');
        if (!container) return;

        let html = '';
        if (data.discount > 0) {
            html += `<h4 class="header-cart-total">Giảm giá Combo: <span>-${data.discount_format}</span></h4>`;
        }
        const total = data.total_format || data.subtotal_format;
        html += `<h4 class="header-cart-total">Total: <span>${total}</span></h4>`;
        
        container.innerHTML = html;
    }

    function updateMiniCartItem(variantId, newQty) {
        if (newQty < 1) return;

        fetch('{{ route('cart.update') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                variant_id: variantId,
                quantity: newQty
            })
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => { throw new Error(err.error || 'Lỗi cập nhật giỏ hàng'); });
            }
            return response.json();
        })
        .then(data => {
            if(data.html) {
                const cartProducts = document.querySelector('.header-cart-products');
                if(cartProducts) cartProducts.innerHTML = data.html;
            }
            if(data.count !== undefined) {
                const countElements = document.querySelectorAll('.header-cart-count, .cart-count, .header-action-toggle .count');
                countElements.forEach(el => el.textContent = data.count);
            }
            updateMiniCartFooter(data);
            
            // Also update main cart if exists
            // This is a simple reload if on cart page, or we could try to update DOM.
            // For now, simple is better.
            if(document.getElementById('update-cart-form')) {
                location.reload(); 
            }
        })
        .catch(error => {
            console.error('Error:', error);
            if (typeof showToast === 'function') {
                showToast('error', 'Lỗi', error.message || 'Có lỗi xảy ra');
            } else {
                alert(error.message || 'Có lỗi xảy ra');
            }
        });
    }

    function removeMiniCartItem(variantId) {
        ShopAlert.confirm(
            'Xóa sản phẩm',
            'Bạn có chắc muốn xóa sản phẩm này khỏi giỏ hàng?',
            function() {
                fetch('{{ route('cart.remove') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ variant_id: variantId })
                })
                .then(response => response.json())
                .then(data => {
                     if(data.html) {
                        const cartProducts = document.querySelector('.header-cart-products');
                        if(cartProducts) cartProducts.innerHTML = data.html;
                    }
                    if(data.count !== undefined) {
                        const countElements = document.querySelectorAll('.header-cart-count, .cart-count, .header-action-toggle .count');
                        countElements.forEach(el => el.textContent = data.count);
                    }
                    updateMiniCartFooter(data);
                    
                    // Update Main Cart DOM if exists
                    if (data.main_html && document.getElementById('cart-main-container')) {
                        document.getElementById('cart-main-container').innerHTML = data.main_html;
                    } else if(document.getElementById('update-cart-form')) {
                        // Fallback if main_html not provided or container missing but form exists
                        location.reload(); 
                    }
                    
                    if (typeof showToast === 'function') {
                        showToast('success', 'Thành công', 'Đã xóa sản phẩm khỏi giỏ hàng');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    if (typeof showToast === 'function') {
                        showToast('error', 'Lỗi', 'Có lỗi xảy ra khi xóa sản phẩm');
                    }
                });
            }
        );
    }

    // Global Event Delegation for Product Actions
    document.addEventListener('click', function(e) {
        // Add to Wishlist
        const wishlistBtn = e.target.closest('.btn-add-to-wishlist');
        if (wishlistBtn) {
            e.preventDefault();
            const productId = wishlistBtn.dataset.id;
            
            ShopLoading.start(wishlistBtn);

            fetch('{{ route('client.wishlist.add') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ product_id: productId })
            })
            .then(res => {
                if(res.status === 401) {
                    window.location.href = '{{ route('login') }}';
                    throw new Error('Vui lòng đăng nhập');
                }
                return res.json();
            })
            .then(data => {
                ShopLoading.stop(wishlistBtn);
                if(data.status === 'ok') {
                    if (typeof showToast === 'function') {
                        showToast('success', 'Thành công', 'Đã thêm vào danh sách yêu thích');
                    }
                    wishlistBtn.classList.add('added');
                } else if (data.status === 'removed') {
                     if (typeof showToast === 'function') {
                        showToast('success', 'Thành công', 'Đã xóa khỏi danh sách yêu thích');
                    }
                    wishlistBtn.classList.remove('added');
                } else {
                     if (typeof showToast === 'function') {
                        showToast('info', 'Thông báo', data.message || 'Có lỗi xảy ra');
                    }
                }
            })
            .catch(err => {
                ShopLoading.stop(wishlistBtn);
                if (err.message !== 'Vui lòng đăng nhập') {
                    console.error(err);
                }
            });
        }

        // Add to Cart (Simple)
        const cartBtn = e.target.closest('.btn-add-to-cart-simple');
        if (cartBtn) {
            e.preventDefault();
            const variantId = cartBtn.dataset.variantId;
            const quantity = 1;

            ShopLoading.start(cartBtn);

            fetch('{{ route('cart.add') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ 
                    variant_id: variantId, 
                    quantity: quantity 
                })
            })
            .then(res => {
                if (!res.ok) {
                    return res.json().then(err => { throw err; });
                }
                return res.json();
            })
            .then(data => {
                ShopLoading.stop(cartBtn);
                
                // If we get items, it's success (CartController returns cart object)
                if(data.items || data.status === 'ok') {
                    if (typeof showToast === 'function') {
                        showToast('success', 'Thành công', 'Đã thêm vào giỏ hàng');
                    }
                    
                    // Update header cart count
                    if(data.count !== undefined) {
                        document.querySelectorAll('.header-cart-count, .cart-count, .header-action-toggle .count').forEach(el => el.textContent = data.count);
                    }

                    // Update Mini Cart HTML
                    if(data.html) {
                        const cartProducts = document.querySelector('.header-cart-products');
                        if(cartProducts) cartProducts.innerHTML = data.html;
                    }
                    updateMiniCartFooter(data);

                    // Open Mini Cart Offcanvas
                    const cartToggle = document.querySelector('[data-bs-target="#offcanvas-cart"]');
                    if (cartToggle) {
                        // Check if offcanvas is already open
                        const offcanvasEl = document.getElementById('offcanvas-cart');
                        if (offcanvasEl && !offcanvasEl.classList.contains('show')) {
                             cartToggle.click();
                        }
                    }
                } else {
                    if (typeof showToast === 'function') {
                        showToast('error', 'Lỗi', data.message || 'Không thể thêm vào giỏ hàng');
                    }
                }
            })
            .catch(error => {
                ShopLoading.stop(cartBtn);
                console.error(error);
                let msg = 'Có lỗi xảy ra';
                if (error.errors && error.errors.stock) msg = error.errors.stock[0];
                else if (error.error) msg = error.error;
                
                if (typeof showToast === 'function') {
                    showToast('error', 'Lỗi', msg);
                }
            });
        }
    });

    // Quick View Handler
    document.addEventListener('DOMContentLoaded', function() {
        const quickViewModal = document.getElementById('exampleProductModal');
        if (quickViewModal) {
            quickViewModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const slug = button.getAttribute('data-slug');
                const modalBody = quickViewModal.querySelector('.modal-body');
                
                if (!slug) {
                    modalBody.innerHTML = '<p class="text-center p-5">Không tìm thấy thông tin sản phẩm.</p>';
                    return;
                }

                // Show loading
                modalBody.innerHTML = `
                    <div class="d-flex justify-content-center align-items-center" style="min-height: 200px;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                `;

                // Fetch content
                fetch(`/products/${slug}/quick-view`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.text())
                .then(html => {
                    modalBody.innerHTML = html;
                    
                    // Execute scripts in the injected HTML
                    const scripts = modalBody.getElementsByTagName("script");
                    const scriptsToRun = [];
                    
                    for (let i = 0; i < scripts.length; i++) {
                        scriptsToRun.push(scripts[i]);
                    }
                    
                    scriptsToRun.forEach(oldScript => {
                        const newScript = document.createElement("script");
                        Array.from(oldScript.attributes).forEach(attr => {
                            newScript.setAttribute(attr.name, attr.value);
                        });
                        newScript.appendChild(document.createTextNode(oldScript.innerHTML));
                        oldScript.parentNode.replaceChild(newScript, oldScript);
                    });
                })
                .catch(error => {
                    console.error('Quick View Error:', error);
                    modalBody.innerHTML = '<p class="text-center p-5 text-danger">Có lỗi xảy ra khi tải dữ liệu.</p>';
                });
            });
        }
    });

    // --- Product Item Variant Logic ---
    // Inject Styles for Variant Buttons
    (function() {
        if (!document.getElementById('product-variation-styles')) {
            const style = document.createElement('style');
            style.id = 'product-variation-styles';
            style.innerHTML = `
                .product-variation-type-btn.active {
                    border-color: #000 !important;
                    outline: 1px solid #000;
                }
                .product-variation-option-btn {
                    display: inline-block;
                    min-width: 30px;
                    padding: 2px 8px;
                    margin: 0 4px 4px 0;
                    border: 1px solid #e5e5e5;
                    background: #fff;
                    color: #555;
                    cursor: pointer;
                    font-size: 12px;
                    text-align: center;
                    transition: all 0.2s;
                    border-radius: 2px;
                }
                .product-variation-option-btn:hover {
                    border-color: #000;
                    color: #000;
                }
                .product-variation-option-btn.disabled {
                    opacity: 0.5;
                    pointer-events: none;
                    text-decoration: line-through;
                    background: #f9f9f9;
                }
                .product-variation-add-btn {
                    display: inline-block;
                    padding: 4px 12px;
                    margin-top: 5px;
                    border: 1px solid #000;
                    background: #000;
                    color: #fff;
                    font-size: 12px;
                    cursor: pointer;
                    transition: all 0.2s;
                }
                .product-variation-add-btn:hover {
                    background: #333;
                    border-color: #333;
                }
            `;
            document.head.appendChild(style);
        }
    })();

    document.addEventListener('click', function(e) {
        // Handle Variation Type Click (Color/Image)
        const typeBtn = e.target.closest('.product-variation-type-btn');
        if (typeBtn) {
            e.preventDefault();
            e.stopPropagation(); 
            
            const productItem = typeBtn.closest('.product');
            const container = productItem ? productItem.querySelector('.product-content') : null;
            
            if (!container || !productItem) return;
            
            // 1. Update Product Thumb
            const newImg = typeBtn.dataset.image;
            if (productItem && newImg) {
                const thumbImg = productItem.querySelector('.product-image img');
                if (thumbImg) {
                    thumbImg.src = newImg;
                    thumbImg.removeAttribute('srcset'); 
                }
            }
            
            // 2. Handle Next Attributes
            let nextAttrs = [];
            try {
                nextAttrs = JSON.parse(typeBtn.dataset.nextAttrs);
            } catch(err) { console.error('Error parsing next-attrs', err); }

            const nextContainer = container.querySelector('.product-variation-next');
            
            if (nextContainer) {
                nextContainer.innerHTML = '';
                nextContainer.style.display = 'block';
                
                if (nextAttrs.length > 0) {
                    // Add Label for UX if there are named attributes (e.g. Sizes)
                    if (nextAttrs[0].name) {
                        const label = document.createElement('div');
                        label.className = 'product-variation-label';
                        label.textContent = 'Chọn thêm nhanh giỏ hàng +:';
                        label.style.fontSize = '12px';
                        label.style.marginBottom = '4px';
                        label.style.color = '#666';
                        nextContainer.appendChild(label);
                    }

                    nextAttrs.forEach(attr => {
                        // Create button
                        const btn = document.createElement('button');
                        
                        if (attr.name) {
                            // Multi-level variant (e.g. Size)
                            btn.className = 'product-variation-option-btn btn-add-to-cart-simple';
                            btn.textContent = attr.name;
                            btn.dataset.tooltipText = 'Add to cart';
                        } else {
                            // Single-level variant (e.g. just Color)
                            btn.className = 'product-variation-add-btn btn-add-to-cart-simple';
                            btn.textContent = 'Add to Cart';
                        }
                        
                        btn.dataset.variantId = attr.variant_id;
                        
                        if (!attr.in_stock) {
                            btn.disabled = true;
                            btn.classList.add('disabled');
                            btn.title = 'Out of stock';
                        }
                        
                        nextContainer.appendChild(btn);
                    });
                }
            }
            
            // Highlight selected
            if (container) {
                container.querySelectorAll('.product-variation-type-btn').forEach(b => b.classList.remove('active'));
                typeBtn.classList.add('active');
            }
        }
    });

</script>

@stack('scripts')
