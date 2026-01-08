@extends('client.layouts.master')

@section('title', 'Thanh toán | ' . ($storeSettings['name'] ?? 'Shop06'))

@section('content')
<!-- BREADCRUMB SECTION START -->
@include('client.components.breadcrumb', [
    'title' => 'Thanh toán',
    'items' => [
        ['label' => 'Trang chủ', 'url' => route('home')],
        ['label' => 'Thanh toán', 'url' => '']
    ]
])
<!-- BREADCRUMB SECTION END -->

<div class="shop-product-checkuot section section-padding">
    <div class="container">
    @if(session('status'))
        <div class="mb-4">
            <div style="color: #0f5132; background-color: #d1e7dd; border-color: #badbcc; padding: 1rem; border-radius: 0.25rem;">
                {{ session('status') }}
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-4">
            <div style="color: #842029; background-color: #f8d7da; border-color: #f5c2c7; padding: 1rem; border-radius: 0.25rem;">
                @foreach($errors->all() as $e)
                    <div>{{ $e }}</div>
                @endforeach
            </div>
        </div>
    @endif

    <form action="{{ route('checkout.process') }}" method="POST" class="ul-checkout-form">
        @csrf
        <div class="row ul-bs-row">
            <div class="col-lg-8 col-12">
                <div class="row ul-bs-row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="customer_email">Email</label>
                            <input class="form-field" type="email" name="customer_email" id="customer_email" value="{{ old('customer_email', $user->email ?? '') }}" placeholder="email@example.com" required>
                        </div>
                    </div>
                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            <label for="customer_name">Họ tên</label>
                            <input class="form-field" type="text" name="customer_name" id="customer_name" value="{{ old('customer_name', $defaultAddress->name ?? $user->name ?? '') }}" required>
                        </div>
                    </div>
                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            <label for="customer_phone">Số điện thoại</label>
                            <input class="form-field" type="tel" name="customer_phone" id="customer_phone" value="{{ old('customer_phone', $defaultAddress->phone ?? $user->phone ?? '') }}" required>
                        </div>
                    </div>
                </div>

                @if(isset($addresses) && $addresses->count() > 0)
                <div class="form-group">
                    <label>Sổ địa chỉ</label>
                    <div>
                        @foreach($addresses as $addr)
                        <label class="d-flex align-items-start gap-3 p-3 border rounded cursor-pointer" style="border-color:#e5e5e5;">
                            <input type="radio" name="selected_address" value="{{ $addr->id }}" class="address-radio"
                                data-name="{{ $addr->name }}"
                                data-phone="{{ $addr->phone }}"
                                data-city="{{ $addr->city }}"
                                data-ward="{{ $addr->ward }}"
                                data-address="{{ $addr->address_line }}"
                                {{ (isset($defaultAddress) && $defaultAddress->id == $addr->id) ? 'checked' : '' }}
                            >
                            <div>
                                <div class="fw-medium">{{ $addr->name }} <span class="text-muted">| {{ $addr->phone }}</span></div>
                                <div class="small text-muted mt-1">{{ $addr->address_line }}, {{ $addr->ward }}, {{ $addr->city }}</div>
                                @if($addr->is_default)
                                    <span class="d-inline-block mt-2 px-2 py-1 bg-light text-muted small rounded">Mặc định</span>
                                @endif
                            </div>
                        </label>
                        @endforeach
                        <label class="d-flex align-items-center gap-3 p-3 border rounded cursor-pointer" style="border-color:#e5e5e5;">
                            <input type="radio" name="selected_address" value="new" class="address-radio">
                            <span class="fw-medium">Sử dụng địa chỉ mới</span>
                        </label>
                    </div>
                    <input type="hidden" name="address_id" id="address_id" value="{{ $defaultAddress->id ?? '' }}">
                </div>
                @endif

                <div class="row ul-bs-row row-cols-lg-2 row-cols-1">
                    <div class="col-lg-6">
                        <div class="form-group mt-3">
                            <label for="province_select">Tỉnh / Thành phố</label>
                            <select class="form-field" name="shipping_province" id="province_select" data-selected="{{ old('shipping_province', $defaultAddress->city ?? '') }}" required>
                                <option value="">Chọn Tỉnh/Thành</option>
                            </select>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group mt-3">
                            <label for="ward_select">Phường / Xã</label>
                            <select class="form-field" name="shipping_ward" id="ward_select" data-selected="{{ old('shipping_ward', $defaultAddress->ward ?? '') }}" required disabled>
                                <option value="">Chọn Phường/Xã</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="shipping_address">Địa chỉ cụ thể</label>
                            <input class="form-field" type="text" name="shipping_address" id="shipping_address" value="{{ old('shipping_address', $defaultAddress->address_line ?? '') }}" placeholder="Số nhà, tên đường..." required>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="note">Ghi chú đơn hàng (tùy chọn)</label>
                            <textarea class="form-field" name="note" id="note" rows="2">{{ old('note') }}</textarea>
                        </div>
                    </div>
                </div>

                @if(!Auth::check())
                <div class="form-group">
                    <label class="d-flex align-items-center gap-2">
                        <input type="checkbox" id="create_account" name="create_account" value="1" {{ old('create_account') ? 'checked' : '' }}>
                        <span>Tạo tài khoản mới với thông tin trên</span>
                    </label>
                </div>
                <div id="password_field" class="{{ old('create_account') ? '' : 'd-none' }}">
                    <div class="form-group">
                        <label for="password">Mật khẩu</label>
                        <input type="password" name="password" id="password">
                    </div>
                </div>
                @endif
            </div>

            <div class="col-lg-4 col-12">
                <div class="ul-checkout-bill-summary" style="border: 1px solid #e5e5e5; padding: 24px; border-radius: 12px; background-color: #f9f9f9;">
                    <h4 class="ul-checkout-bill-summary-title" style="font-size: 20px; font-weight: 700; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid #ddd;">Đơn hàng của bạn</h4>
                    <div>
                        <div class="ul-checkout-bill-summary-header d-flex justify-content-between align-items-center mb-3" style="font-weight: 600; color: #555;">
                            <span class="left">Sản phẩm</span>
                            <span class="right">Tạm tính</span>
                        </div>
                        <div class="ul-checkout-bill-summary-body">
                            @foreach($items as $item)
                                @php
                                    $colorAliases = ['color','màu','mau','màu sắc','mau sac','mausac','mau_sac'];
                                    $sizeAliases = ['size','kích thước','kich thuoc','kích cỡ','kich co','cỡ','co'];
                                    $colorVal = null; $sizeVal = null; $others = [];
                                    foreach (($item['options'] ?? []) as $opt) {
                                        $n = strtolower(trim((string)($opt['name'] ?? '')));
                                        $v = trim((string)($opt['value'] ?? ''));
                                        if (in_array($n, $colorAliases, true)) { $colorVal = $v; continue; }
                                        if (in_array($n, $sizeAliases, true)) { $sizeVal = $v; continue; }
                                        $others[] = $opt;
                                    }
                                @endphp
                                <div class="single-row d-flex justify-content-between align-items-center mb-3 pb-3" style="border-bottom: 1px dashed #e0e0e0;">
                                    <span class="left d-flex align-items-center" style="max-width: 70%;">
                                        @if(!empty($item['image']))
                                            <img src="{{ $item['image'] }}" alt="" style="width:60px;height:60px;border-radius:6px;object-fit:cover;margin-right:12px; flex-shrink: 0;">
                                        @endif
                                        <span class="d-flex flex-column">
                                            <span class="fw-medium text-dark">{{ $item['name'] }}</span>
                                            <span class="text-muted small mt-1">
                                                {{ $item['quantity'] }} x {{ number_format($item['price'], 0, ',', '.') }}₫
                                                @if($colorVal)
                                                    <span title="{{ $colorVal }}" style="display:inline-block;width:12px;height:12px;border-radius:50%;vertical-align:middle;margin-left:4px;background-color: {{ $colorVal }};border: 1px solid #ccc"></span>
                                                @endif
                                                @if($sizeVal)
                                                    <span class="ms-1">| {{ $sizeVal }}</span>
                                                @endif
                                                @foreach($others as $opt)
                                                    <span class="ms-1">| {{ $opt['name'] }}: {{ $opt['value'] }}</span>
                                                @endforeach
                                            </span>
                                        </span>
                                    </span>
                                    <span class="right fw-bold text-dark">{{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}₫</span>
                                </div>
                            @endforeach
                            <div class="single-row d-flex justify-content-between align-items-center py-3" style="border-bottom: 1px solid #ddd;">
                                <span class="left fw-medium">Tạm tính</span>
                                <span class="right fw-bold">{{ number_format($subtotal, 0, ',', '.') }}₫</span>
                            </div>
                            @if(($discount ?? 0) > 0)
                            <div class="single-row d-flex justify-content-between align-items-center py-3" style="border-bottom: 1px solid #ddd;">
                                <span class="left fw-medium text-success">Giảm giá Combo</span>
                                <span class="right fw-bold text-success">-{{ number_format($discount, 0, ',', '.') }}₫</span>
                            </div>
                            @endif
                        </div>
                        <div class="ul-checkout-bill-summary-footer ul-checkout-bill-summary-header d-flex justify-content-between align-items-center mt-3 pt-2">
                            <span class="left fw-bold" style="font-size: 18px;">Tổng cộng</span>
                            <span class="right fw-bold text-primary" style="font-size: 20px;">{{ number_format(($total ?? $subtotal), 0, ',', '.') }}₫</span>
                        </div>
                    </div>
                </div>

                <!-- PAYMENT SECTION -->
                <div class="ul-checkout-payment-section mt-4 pt-4 border-top">
                    <h4 class="mb-4 font-weight-bold text-dark" style="font-size: 20px;">Phương thức thanh toán</h4>
                    
                    <div class="ul-checkout-payment-methods">
                        @if(isset($availablePaymentMethods) && count($availablePaymentMethods) > 0)
                            @foreach($availablePaymentMethods as $code => $method)
                            <div class="payment-method-item mb-3">
                                <label for="payment_method_{{ $code }}" class="d-flex align-items-start p-3 border rounded cursor-pointer payment-label position-relative overflow-hidden" style="background: #fff; transition: all 0.2s ease; border-color: #e5e5e5;">
                                    <input type="radio" name="payment_method" id="payment_method_{{ $code }}" value="{{ $code }}" class="mt-1 me-3 form-check-input" {{ $loop->first ? 'checked' : '' }} style="width: 18px; height: 18px; cursor: pointer;">
                                    <div class="flex-grow-1">
                                        <div class="d-flex align-items-center justify-content-between mb-1">
                                            <span class="font-weight-bold text-dark" style="font-size: 16px;">{{ $method['label'] }}</span>
                                            @if(!empty($method['icon']))
                                                <i class="{{ $method['icon'] }} text-muted" style="font-size: 22px;"></i>
                                            @endif
                                        </div>
                                        @if(!empty($method['description']))
                                            <div class="text-muted small" style="line-height: 1.4;">{{ $method['description'] }}</div>
                                        @endif
                                    </div>
                                </label>
                            </div>
                            @endforeach
                        @else
                            <div class="alert alert-warning mb-3">
                                <i class="flaticon-warning me-2"></i> Không có phương thức thanh toán nào khả dụng cho các sản phẩm trong giỏ hàng.
                            </div>
                        @endif

                        <button type="submit" class="ul-checkout-form-btn btn btn-primary w-100 py-3 mt-3 fw-bold text-uppercase" style="letter-spacing: 1px;">Đặt hàng</button>
                    </div>
                </div>
                <!-- PAYMENT SECTION END -->
            </div>
        </div>
    </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const provinceSelect = document.getElementById('province_select');
    const wardSelect = document.getElementById('ward_select');
    const createAccountCheckbox = document.getElementById('create_account');
    const passwordField = document.getElementById('password_field');
    const addressRadios = document.querySelectorAll('.address-radio');
    const addressIdInput = document.getElementById('address_id');
    const customerNameInput = document.getElementById('customer_name');
    const customerPhoneInput = document.getElementById('customer_phone');
    const shippingAddressInput = document.getElementById('shipping_address');

    let provinceMap = {}; // Map Name -> Code

    // Toggle password field
    if (createAccountCheckbox) {
        createAccountCheckbox.addEventListener('change', function() {
            if (this.checked) {
                passwordField.classList.remove('d-none');
                passwordField.querySelector('input').required = true;
            } else {
                passwordField.classList.add('d-none');
                passwordField.querySelector('input').required = false;
            }
        });
    }

    const loadWards = (provinceCode, selectedWard = null) => {
        wardSelect.innerHTML = '<option value="">Chọn Phường/Xã</option>';
        wardSelect.disabled = true;

        if (provinceCode) {
            fetch(`{{ route('api.provinces') }}/${provinceCode}/communes`)
                .then(res => res.json())
                .then(data => {
                    const list = Array.isArray(data) ? data : (data.data || []);
                    list.forEach(w => {
                        const opt = document.createElement('option');
                        opt.value = w.name;
                        opt.textContent = w.name;
                        wardSelect.appendChild(opt);
                    });
                    wardSelect.disabled = false;
                    
                    if (selectedWard) {
                        wardSelect.value = selectedWard;
                    }
                })
                .catch(err => console.error('Error loading communes:', err));
        }
    };

    // Load Provinces
    fetch('{{ route('api.provinces') }}')
        .then(res => res.json())
        .then(data => {
            // Data structure check: might be array or {data: []}
            const list = Array.isArray(data) ? data : (data.data || []);
            const selectedProvince = provinceSelect.dataset.selected;
            let selectedCode = null;

            list.forEach(p => {
                const opt = document.createElement('option');
                opt.value = p.name; // Use name as value
                opt.textContent = p.name;
                opt.dataset.code = p.code;
                provinceSelect.appendChild(opt);
                
                // Store in map
                provinceMap[p.name] = p.code;

                if (p.name === selectedProvince) {
                    selectedCode = p.code;
                }
            });

            if (selectedProvince && selectedCode) {
                provinceSelect.value = selectedProvince;
                loadWards(selectedCode, wardSelect.dataset.selected);
            }
        })
        .catch(err => console.error('Error loading provinces:', err));

    // Load Wards (Communes) when Province changes
    provinceSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const code = selectedOption.dataset.code;
        loadWards(code);
    });

    // Handle Address Selection
    addressRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            addressRadios.forEach(r => {
                const label = r.closest('label');
                if (r.checked && r.value !== 'new') {
                    label.style.borderColor = '#000';
                    label.style.backgroundColor = '#f8f9fa';
                } else if (r.value !== 'new') {
                    label.style.borderColor = '#e5e5e5';
                    label.style.backgroundColor = 'transparent';
                } else {
                     if(r.checked) {
                        label.style.borderColor = '#000';
                     } else {
                        label.style.borderColor = '#e5e5e5';
                     }
                }
            });

            if (this.value === 'new') {
                addressIdInput.value = '';
                customerNameInput.value = '';
                customerPhoneInput.value = '';
                shippingAddressInput.value = '';
                provinceSelect.value = '';
                wardSelect.innerHTML = '<option value="">Chọn Phường/Xã</option>';
                wardSelect.disabled = true;
            } else {
                addressIdInput.value = this.value;
                customerNameInput.value = this.dataset.name;
                customerPhoneInput.value = this.dataset.phone;
                shippingAddressInput.value = this.dataset.address;
                
                const city = this.dataset.city;
                const ward = this.dataset.ward;

                if (city && provinceMap[city]) {
                    provinceSelect.value = city;
                    loadWards(provinceMap[city], ward);
                }
            }
        });
    });
});
</script>
@endsection
