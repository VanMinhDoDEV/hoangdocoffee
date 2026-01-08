@extends('client.layouts.master')

@section('title', 'Giỏ hàng | ' . ($storeSettings['name'] ?? 'Hoang Do Coffee'))

@section('content')
    <!-- Page Banner Section Start -->
    <div class="page-banner-section section">
        <div class="container">
            <ul class="breadcrumb">
                <li><a href="{{ route('home') }}">Trang chủ</a></li>
                <li>Giỏ hàng</li>
            </ul>
        </div>
    </div>
    <!-- Page Banner Section End -->

    <!-- Shopping Cart Section Start -->
    <div class="section section-padding">
        <div class="container">
            {{-- 
            @if(session('status'))
                <div class="alert alert-success mb-4">
                    {{ session('status') }}
                </div>
            @endif
            --}}

            @if($errors->any())
                <div class="alert alert-danger mb-4">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div id="cart-main-container">
                @include('client.cart.partials.cart-main')
            </div>
        </div>
    </div>
    <!-- Shopping Cart Section End -->

    @push('scripts')
    <script>
        function clearCart() {
            ShopAlert.confirm(
                'Xóa giỏ hàng',
                'Bạn có chắc muốn xóa toàn bộ giỏ hàng?',
                function() {
                    fetch('{{ route('cart.clear') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.main_html) {
                            document.getElementById('cart-main-container').innerHTML = data.main_html;
                        }
                        if (data.html) {
                             const miniCartContainer = document.querySelector('.header-cart-products');
                             if (miniCartContainer) {
                                 miniCartContainer.innerHTML = data.html;
                             }
                             const countElements = document.querySelectorAll('.header-cart-count, .cart-count, .header-action-toggle .count');
                             countElements.forEach(el => el.textContent = data.count);
                             const subtotalElements = document.querySelectorAll('.header-cart-total .amount, .header-cart-total span');
                             subtotalElements.forEach(el => el.textContent = data.subtotal_format);
                        }
                        showToast('success', 'Thành công', 'Đã xóa toàn bộ giỏ hàng');
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showToast('error', 'Lỗi', 'Có lỗi xảy ra khi xóa giỏ hàng');
                    });
                }
            );
        }

        function proceedCheckout() {
            document.getElementById('checkout-form').submit();
        }

        function syncQuantity(variantId, value) {
            // Sync mobile quantity changes to desktop input if needed, though form submission handles it by name
            // Actually, we should make sure both inputs have the same name or handle it.
            // Since we use the same name 'quantities[]' for desktop, and 'quantities_mobile[]' for mobile,
            // we might need to handle this in controller or sync them.
            // Best approach: use same name 'quantities[id]' for both? No, that would submit duplicate keys.
            // Better: use 'quantities[id]' for desktop and sync mobile changes to it.
            // Or just check which one is visible.
            
            // For simplicity in this template:
            // Let's assume the user uses one view. But if they resize?
            // Let's rely on the fact that only one set is visible/interacted with usually.
            // But to be safe, let's keep the names consistent or handle in JS.
            
            const desktopInput = document.querySelector(`input[name="quantities[${variantId}]"]`);
            if (desktopInput) desktopInput.value = value;
        }

        // Add event listener for mobile inputs to sync to desktop inputs
        $(document).on('change', 'input[name^="quantities_mobile"]', function() {
            const name = this.name;
            const idMatch = name.match(/\[(\d+)\]/);
            if (idMatch) {
                const id = idMatch[1];
                const desktopInput = document.querySelector(`input[name="quantities[${id}]"]`);
                if (desktopInput) desktopInput.value = this.value;
            }
        });
        
        // Add event listener for desktop inputs to sync to mobile inputs
        $(document).on('change', 'input[name^="quantities["]', function() {
            if (!this.name.includes('mobile')) {
                const name = this.name;
                const idMatch = name.match(/\[(\d+)\]/);
                if (idMatch) {
                    const id = idMatch[1];
                    const mobileInput = document.querySelector(`input[name="quantities_mobile[${id}]"]`);
                    if (mobileInput) mobileInput.value = this.value;
                }
            }
        });

        // The active.js handles the click but doesn't trigger 'change' event on the input automatically?
        // We need to verify if active.js triggers change.
        // Looking at active.js code: $box.value = ...
        // It does NOT trigger change event programmatically. We should fix that here or in active.js.
        // Let's attach our own click handler to trigger change.
        
        $(document).on('click', '.product-quantity-count .qty-btn', function() {
            const $btn = $(this);
            const $box = $btn.siblings('.product-quantity-box');
            const name = $box.attr('name') || '';
            const idMatch = name.match(/\[(\d+)\]/);
            if (idMatch) {
                const id = parseInt(idMatch[1], 10);
                let val = parseInt($box.val() || '1', 10);
                if ($btn.hasClass('inc')) val += 1;
                else if ($btn.hasClass('dec')) val = Math.max(1, val - 1);
                $box.val(val);
                updateMiniCartItem(id, val);
            } else {
                $box.trigger('change');
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            const totalsTable = document.querySelector('.cart-totals table tbody');
            if (totalsTable) {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <th>Mã khuyến mại</th>
                    <td>
                        <div class="input-group">
                            <input type="text" id="promo-code-input" class="form-control" placeholder="Nhập mã khuyến mại">
                            <button type="button" id="apply-promo-btn" class="btn btn-outline-dark btn-primary-hover rounded-0">Áp dụng</button>
                        </div>
                        <div class="small text-muted mt-1">Áp dụng cho các rule Mix & Match yêu cầu mã.</div>
                    </td>
                `;
                totalsTable.appendChild(tr);
            }
            const btn = document.getElementById('apply-promo-btn');
            btn?.addEventListener('click', function() {
                const code = (document.getElementById('promo-code-input')?.value || '').trim();
                if (!code) return;
                fetch('{{ route('cart.apply_promo') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: JSON.stringify({ code })
                })
                .then(r => r.json())
                .then(data => {
                    if (data.main_html && document.getElementById('cart-main-container')) {
                        document.getElementById('cart-main-container').innerHTML = data.main_html;
                    }
                    if (data.html) {
                        const miniCartContainer = document.querySelector('.header-cart-products');
                        if (miniCartContainer) miniCartContainer.innerHTML = data.html;
                    }
                    const countElements = document.querySelectorAll('.header-cart-count, .cart-count, .header-action-toggle .count');
                    countElements.forEach(el => el.textContent = data.count);
                    const subtotalElements = document.querySelectorAll('.header-cart-total .amount, .header-cart-total span');
                    subtotalElements.forEach(el => el.textContent = data.subtotal_format);
                    if (typeof showToast === 'function') showToast('success', 'Thành công', 'Đã áp dụng mã khuyến mại');
                })
                .catch(err => {
                    console.error(err);
                    if (typeof showToast === 'function') showToast('error', 'Lỗi', 'Không áp dụng được mã');
                });
            });
        });

    </script>
    @endpush
@endsection
