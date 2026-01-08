@if(empty($items) || count($items) === 0)
    <div class="text-center py-5">
        <p class="mb-4">Giỏ hàng của bạn đang trống.</p>
        <a href="{{ route('products.index') }}" class="btn btn-dark btn-primary-hover rounded-0">Tiếp tục mua sắm</a>
    </div>
@else
    <form action="{{ route('cart.update_all') }}" method="POST" id="update-cart-form">
        @csrf
        <div class="row mb-n6 mb-lg-n10">

            <div class="col-12 mb-6 mb-lg-10">

                <!-- Cart Table For Tablet & Up Devices Start -->
                <table class="cart-table table table-bordered text-center align-middle mb-6 d-none d-md-table">
                    <thead>
                        <tr>
                            <th class="image">Hình ảnh</th>
                            <th class="title">Sản phẩm</th>
                            <th class="price">Đơn giá</th>
                            <th class="quantity">Số lượng</th>
                            <th class="total">Thành tiền</th>
                            <th class="remove">Xóa</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @foreach($items as $it)
                        <tr>
                            <th>
                                <a href="{{ route('sanpham.show', $it['slug'] ?? '#') }}">
                                    @if(!empty($it['image']))
                                        <img src="{{ $it['image'] }}" alt="{{ $it['name'] }}" style="max-width: 90px;">
                                    @else
                                        <img src="{{ asset('assets/images/products/cart/product-1.jpg') }}" alt="{{ $it['name'] }}" style="max-width: 90px;">
                                    @endif
                                </a>
                            </th>
                            <td>
                                <a href="{{ route('sanpham.show', $it['slug'] ?? '#') }}">{{ $it['name'] }}</a>
                                <div class="text-muted small mt-1">
                                    @if(!empty($it['options']))
                                        @foreach($it['options'] as $opt)
                                            <span class="d-block">{{ $opt['name'] }}: {{ $opt['value'] }}</span>
                                        @endforeach
                                    @endif
                                </div>
                            </td>
                            <td>{{ number_format((float)$it['price'], 0, ',', '.') }}₫</td>
                            <td>
                                <div class="product-quantity-count">
                                    <button type="button" class="dec qty-btn">-</button>
                                    <input class="product-quantity-box" type="text" name="quantities[{{ $it['variant_id'] }}]" value="{{ $it['quantity'] }}" min="1" max="{{ $it['max'] ?? 99 }}">
                                    <button type="button" class="inc qty-btn">+</button>
                                </div>
                            </td>
                            <td>{{ number_format((float)$it['price'] * $it['quantity'], 0, ',', '.') }}₫</td>
                            <td>
                                <button type="button" class="remove-btn" onclick="removeMiniCartItem({{ $it['variant_id'] }})"><i class="sli-close"></i></button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <!-- Cart Table For Tablet & Up Devices End -->

                <!-- Cart Table For Mobile Devices Start -->
                <div class="cart-products-mobile d-md-none">
                    @foreach($items as $it)
                    <div class="cart-product-mobile">
                        <div class="cart-product-mobile-thumb">
                            <a href="{{ route('sanpham.show', $it['slug'] ?? '#') }}" class="cart-product-mobile-image">
                                @if(!empty($it['image']))
                                    <img src="{{ $it['image'] }}" alt="{{ $it['name'] }}" width="90" height="103">
                                @else
                                    <img src="{{ asset('assets/images/products/cart/product-1.jpg') }}" alt="{{ $it['name'] }}" width="90" height="103">
                                @endif
                            </a>
                            <button type="button" class="cart-product-mobile-remove" onclick="removeMiniCartItem({{ $it['variant_id'] }})"><i class="sli-close"></i></button>
                        </div>
                        <div class="cart-product-mobile-content">
                            <h5 class="cart-product-mobile-title"><a href="{{ route('sanpham.show', $it['slug'] ?? '#') }}">{{ $it['name'] }}</a></h5>
                            <div class="text-muted small mb-2">
                                @if(!empty($it['options']))
                                    @foreach($it['options'] as $opt)
                                        <span class="d-inline-block me-2">{{ $opt['name'] }}: {{ $opt['value'] }}</span>
                                    @endforeach
                                @endif
                            </div>
                            <span class="cart-product-mobile-quantity">{{ $it['quantity'] }} x {{ number_format((float)$it['price'], 0, ',', '.') }}₫</span>
                            <span class="cart-product-mobile-total"><b>Tổng:</b> {{ number_format((float)$it['price'] * $it['quantity'], 0, ',', '.') }}₫</span>
                            <div class="product-quantity-count">
                                <button type="button" class="dec qty-btn">-</button>
                                <input class="product-quantity-box" type="text" name="quantities_mobile[{{ $it['variant_id'] }}]" value="{{ $it['quantity'] }}" min="1" max="{{ $it['max'] ?? 99 }}" onchange="syncQuantity({{ $it['variant_id'] }}, this.value)">
                                <button type="button" class="inc qty-btn">+</button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <!-- Cart Table For Mobile Devices End -->

                <!-- Cart Action Buttons Start -->
                <div class="row justify-content-between gap-3">
                    <div class="col-auto"><a href="{{ route('products.index') }}" class="btn btn-outline-dark btn-primary-hover rounded-0">Tiếp tục mua sắm</a></div>
                    <div class="col-auto d-flex flex-wrap gap-3">
                        <button type="submit" class="btn btn-outline-dark btn-primary-hover rounded-0">Cập nhật giỏ hàng</button>
                        <button type="button" onclick="clearCart()" class="btn btn-outline-light btn-primary-hover rounded-0">Xóa giỏ hàng</button>
                    </div>
                </div>
                <!-- Cart Action Buttons End -->

            </div>

            <!-- Cart Totals Start -->
            <div class="col">
                <div class="cart-totals">
                    <h4 class="title">Tổng đơn hàng</h4>
                    @php
                        $computedSubtotal = (float)($subtotal ?? 0);
                        $computedDiscount = (float)($discount ?? 0);
                        $computedTotal = max(0, $computedSubtotal - $computedDiscount);
                        if (!isset($discount)) {
                            $available = [];
                            $priceMap = [];
                            foreach ($items as $it) {
                                $vid = (int)($it['variant_id'] ?? 0);
                                if ($vid) {
                                    $available[$vid] = ($available[$vid] ?? 0) + (int)($it['quantity'] ?? 0);
                                    $priceMap[$vid] = (float)($it['price'] ?? 0);
                                }
                            }
                            $combos = \App\Models\Combo::with('lines')->where('is_active', true)->get();
                            $computedDiscount = 0.0;
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
                                    if ($raw > $target) { $computedDiscount += ($raw - $target); }
                                    foreach ($lineVariants as $lv) {
                                        $vid = $lv['vid']; $per = $lv['per'];
                                        $available[$vid] = max(0, (int)$available[$vid] - ($per * $sets));
                                    }
                                }
                            }
                            $computedTotal = max(0, $computedSubtotal - $computedDiscount);
                        }
                    @endphp
                    <table class="table table-borderless bg-transparent">
                        <tbody>
                            <tr class="subtotal">
                                <th>Tạm tính</th>
                                <td><span class="amount">{{ number_format($computedSubtotal, 0, ',', '.') }}₫</span></td>
                            </tr>
                            @if($computedDiscount > 0)
                            <tr class="discount">
                                <th>Giảm giá Combo</th>
                                <td><span class="amount text-success">-{{ number_format($computedDiscount, 0, ',', '.') }}₫</span></td>
                            </tr>
                            @endif
                            <tr class="total">
                                <th>Tổng cộng</th>
                                <td><strong><span class="amount">{{ number_format($computedTotal, 0, ',', '.') }}₫</span></strong></td>
                            </tr>
                        </tbody>
                    </table>
                    <button type="button" onclick="proceedCheckout()" class="btn btn-dark btn-primary-hover rounded-0">Tiến hành thanh toán</button>
                </div>
            </div>
            <!-- Cart Totals End -->

        </div>
    </form>

    <form id="remove-form" action="{{ route('cart.remove') }}" method="POST" style="display: none;">
        @csrf
        <input type="hidden" name="variant_id" id="remove_variant_id">
    </form>

    <form id="clear-form" action="{{ route('cart.clear') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <form id="checkout-form" action="{{ route('checkout.from_cart') }}" method="POST" style="display: none;">
        @csrf
    </form>
@endif
