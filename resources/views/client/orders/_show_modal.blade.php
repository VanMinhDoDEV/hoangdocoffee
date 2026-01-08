<div style="font-family: inherit; color: #333;">
  <div style="display: flex; flex-wrap: wrap; gap: 24px;">
    <!-- Column 1: Product Summary -->
    <div style="flex: 1 1 400px; min-width: 0;">
      <div class="ul-checkout-bill-summary" style="border: 1px solid #e5e5e5; padding: 24px; border-radius: 12px; background-color: #f9f9f9;">
        <h4 class="ul-checkout-bill-summary-title" style="font-size: 20px; font-weight: 700; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid #ddd; margin-top: 0;">Chi tiết đơn hàng</h4>
        <div>
          <div class="ul-checkout-bill-summary-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; font-weight: 600; color: #555;">
            <span class="left">Sản phẩm</span>
            <span class="right">Thành tiền</span>
          </div>
          <div class="ul-checkout-bill-summary-body">
            @foreach($order->items as $item)
              @php
                $color = trim((string)$item->snapshot_color);
                $size = trim((string)$item->snapshot_size);
              @endphp
              <div class="single-row" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px dashed #e0e0e0;">
                <span class="left" style="display: flex; align-items: center; max-width: 70%;">
                  <span style="display:inline-flex;align-items:center;">
                    @php
                      $imgUrl = null;
                      if ($item->productVariant && $item->productVariant->product && $item->productVariant->product->images->isNotEmpty()) {
                        $raw = $item->productVariant->product->images->first()->url;
                        $imgUrl = (str_starts_with($raw, 'http') || str_starts_with($raw, '//')) ? $raw : asset($raw);
                      }
                    @endphp
                    @if($imgUrl)
                      <img src="{{ $imgUrl }}" alt="" style="width:60px;height:60px;border-radius:6px;object-fit:cover;margin-right:12px; flex-shrink: 0;">
                    @endif
                    <span style="display: flex; flex-direction: column;">
                      <span style="font-weight: 500; color: #212529;">{{ $item->snapshot_name }}</span>
                      <span style="color: #6c757d; font-size: 0.875em;">SKU: {{ $item->snapshot_sku }}</span>
                      <span style="color: #6c757d; font-size: 0.875em;">Thuộc tính:</span>
                      <span style="color: #6c757d; font-size: 0.875em; margin-top:2px;">
                        @if($color)
                          <span style="margin-right:8px;">Màu:</span>
                          <span title="{{ $color }}" style="display:inline-block;width:12px;height:12px;border-radius:50%;vertical-align:middle;margin-right:6px;background-color: {{ $color }};border: 1px solid #ccc"></span>
                          <span style="font-size: 0.875em; opacity:0.7;">{{ $color }}</span>
                        @endif
                        @if($size)
                          <span style="margin-left: 0.5rem;">Size: {{ $size }}</span>
                        @endif
                      </span>
                      <span style="color: #6c757d; font-size: 0.875em; margin-top:2px;">
                        <span>{{ $item->quantity }} x {{ number_format($item->unit_price, 0, ',', '.') }}₫</span>
                      </span>
                    </span>
                  </span>
                </span>
                <span class="right" style="font-weight: 700; color: #212529;">{{ number_format($item->unit_price * $item->quantity, 0, ',', '.') }}₫</span>
              </div>
            @endforeach
            <div class="single-row" style="display: flex; justify-content: space-between; align-items: center; padding-top: 1rem; padding-bottom: 1rem; border-bottom: 1px solid #ddd;">
              <span class="left" style="font-weight: 500;">Tạm tính</span>
              <span class="right" style="font-weight: 700;">{{ number_format($order->subtotal, 0, ',', '.') }}₫</span>
            </div>
            <div class="single-row" style="display: flex; justify-content: space-between; align-items: center; padding-top: 1rem; padding-bottom: 1rem; border-bottom: 1px solid #ddd;">
              <span class="left" style="font-weight: 500;">Giảm giá</span>
              <span class="right" style="font-weight: 700;">{{ number_format($order->discount_amount, 0, ',', '.') }}₫</span>
            </div>
          </div>
          <div class="ul-checkout-bill-summary-footer ul-checkout-bill-summary-header" style="display: flex; justify-content: space-between; align-items: center; margin-top: 1rem; padding-top: 0.5rem;">
            <span class="left" style="font-weight: 700; font-size: 18px;">Tổng cộng</span>
            <span class="right" style="font-weight: 700; color: #0d6efd; font-size: 20px;">{{ number_format($order->total, 0, ',', '.') }}₫</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Column 2: Customer Info -->
    <div style="flex: 1 1 400px; min-width: 0;">
      <!-- Customer Info Card -->
      <div class="ul-checkout-bill-summary" style="border: 1px solid #e5e5e5; padding: 24px; border-radius: 12px; background-color: #fff;">
        <h4 class="ul-checkout-bill-summary-title" style="font-size: 20px; font-weight: 700; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid #ddd; margin-top: 0;">Thông tin khách hàng</h4>
        <div class="ul-checkout-bill-summary-body">
          <div class="single-row" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px dashed #e5e5e5;">
            <span class="left" style="color: #6c757d;">Họ tên</span>
            <span class="right" style="font-weight: 500; color: #212529;">{{ $order->user->name }}</span>
          </div>
          <div class="single-row" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px dashed #e5e5e5;">
            <span class="left" style="color: #6c757d;">Email</span>
            <span class="right" style="font-weight: 500; color: #212529;">{{ $order->user->email }}</span>
          </div>
          <div class="single-row" style="display: flex; justify-content: space-between; align-items: center;">
            <span class="left" style="color: #6c757d;">Số điện thoại</span>
            <span class="right" style="font-weight: 500; color: #212529;">{{ $order->contact_phone ?? $order->user->phone ?? 'N/A' }}</span>
          </div>
        </div>
      </div>

      <!-- Shipping Address Card -->
      <div class="ul-checkout-bill-summary" style="margin-top:18px;border: 1px solid #e5e5e5; padding: 24px; border-radius: 12px; background-color: #fff;">
        <h4 class="ul-checkout-bill-summary-title" style="font-size: 20px; font-weight: 700; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid #ddd; margin-top: 0;">Địa chỉ giao hàng</h4>
        <div class="ul-checkout-bill-summary-body">
          @php
            $addrLine = $order->shipping_address_line ?? $order->shipping_address ?? null;
            $ward = $order->shipping_ward ?? null;
            $city = $order->shipping_city ?? null;
            $fullAddr = trim(implode(', ', array_filter([$addrLine, $ward, $city], function($v){ return !empty($v); })));
          @endphp
          <div class="single-row" style="display: flex; justify-content: space-between; align-items: flex-start;">
            <span class="left" style="color: #6c757d; min-width: 80px;">Địa chỉ</span>
            <span class="right" style="font-weight: 500; color: #212529; text-align: right; flex: 1; margin-left: 15px; line-height: 1.5;">{{ $fullAddr !== '' ? $fullAddr : 'N/A' }}</span>
          </div>
        </div>
      </div>

      <!-- Shipping Activity Card -->
      <div class="ul-checkout-bill-summary" style="margin-top:18px;border: 1px solid #e5e5e5; padding: 24px; border-radius: 12px; background-color: #fff;">
        <h4 class="ul-checkout-bill-summary-title" style="font-size: 20px; font-weight: 700; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid #ddd; margin-top: 0;">Hoạt động vận chuyển</h4>
        <div class="ul-checkout-bill-summary-body">
          <div class="single-row" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px dashed #e5e5e5;">
            <span class="left" style="color: #6c757d;">Đã đặt hàng</span>
            <span class="right" style="font-weight: 500; color: #212529;">{{ $order->created_at->format('d/m/Y H:i') }}</span>
          </div>
          <div class="single-row" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px dashed #e5e5e5;">
            <span class="left" style="color: #6c757d;">Xử lý</span>
            <span class="right" style="font-weight: 500; color: #212529;">Đang xử lý</span>
          </div>
          <div class="single-row" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px dashed #e5e5e5;">
            <span class="left" style="color: #6c757d;">Vận chuyển</span>
            <span class="right" style="font-weight: 500; color: #212529;">Đang chuẩn bị</span>
          </div>
          <div class="single-row" style="display: flex; justify-content: space-between; align-items: center;">
            <span class="left" style="color: #6c757d;">Trạng thái</span>
            <span class="right">
              <span style="background-color: #0d6efd; color: #fff; border-radius: 50rem; padding: 0.5rem 1rem; display: inline-block; font-size: 0.75em; font-weight: 700; text-align: center; white-space: nowrap; vertical-align: baseline;">
                {{ ucfirst($order->status) }}
              </span>
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
