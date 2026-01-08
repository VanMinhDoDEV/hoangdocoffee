<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hóa đơn #{{ $order->id }} - Hoang Do Coffee</title>
    <style>
        @page {
            size: A4;
            margin: 0;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif; /* Fallback for Vietnamese if needed */
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            font-size: 14px;
            line-height: 1.5;
            color: #333;
            margin: 0;
            padding: 40px;
            background: #fff;
        }
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
        }
        .header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 2px solid #eee;
        }
        .company-info h1 {
            color: #2c3e50;
            margin: 0 0 5px;
            font-size: 24px;
        }
        .company-info p {
            margin: 0;
            color: #7f8c8d;
            font-size: 13px;
        }
        .invoice-details {
            text-align: right;
        }
        .invoice-details h2 {
            margin: 0 0 10px;
            color: #2c3e50;
            font-size: 20px;
            text-transform: uppercase;
        }
        .invoice-details p {
            margin: 0;
            color: #555;
        }
        .billing-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
        }
        .billing-col {
            flex: 1;
        }
        .billing-col h3 {
            font-size: 14px;
            text-transform: uppercase;
            color: #7f8c8d;
            margin: 0 0 10px;
            letter-spacing: 1px;
        }
        .billing-col p {
            margin: 0 0 5px;
        }
        .billing-col strong {
            color: #2c3e50;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        th {
            background: #f8f9fa;
            color: #2c3e50;
            font-weight: 600;
            text-align: left;
            padding: 12px;
            border-bottom: 2px solid #eee;
        }
        td {
            padding: 12px;
            border-bottom: 1px solid #eee;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .total-section {
            width: 300px;
            margin-left: auto;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        .total-row.final {
            border-bottom: none;
            border-top: 2px solid #2c3e50;
            padding-top: 15px;
            margin-top: 5px;
            font-weight: bold;
            font-size: 16px;
            color: #2c3e50;
        }
        .footer {
            margin-top: 60px;
            text-align: center;
            color: #95a5a6;
            font-size: 12px;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
        .print-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #3498db;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: background 0.2s;
        }
        .print-btn:hover {
            background: #2980b9;
        }
        @media print {
            body {
                background: white;
                padding: 0;
            }
            .invoice-container {
                width: 100%;
                max-width: none;
            }
            .print-btn {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="header">
            <div class="company-info">
                <h1>HOANG DO COFFEE</h1>
                <p>{{ __('messages.company_address_label') }}: Số 123, Đường ABC, Quận XYZ, TP.HCM</p>
                <p>{{ __('messages.company_email_label') }}: contact@hoangdocoffee.com</p>
                <p>{{ __('messages.company_phone_label') }}: (028) 3838 8383</p>
            </div>
            <div class="invoice-details">
                <h2>{{ __('messages.invoice_title') }}</h2>
                <p>{{ __('messages.invoice_number') }}: #{{ $order->id }}</p>
                <p>{{ __('messages.invoice_date') }}: {{ $order->created_at->format('d/m/Y') }}</p>
                <p>{{ __('messages.status_label') ?? __('messages.status') }}: {{ __('messages.status_' . $order->status) }}</p>
            </div>
        </div>

        <div class="billing-info">
            <div class="billing-col">
                <h3>{{ __('messages.bill_to') }}</h3>
                <p><strong>{{ $order->shipping_name ?? ($order->user->name ?? __('messages.guest_customer')) }}</strong></p>
                <p>{{ $order->shipping_address ?? ($order->user->address ?? 'N/A') }}</p>
                <p>{{ $order->shipping_city ?? ($order->user->city ?? '') }}</p>
                <p>{{ $order->shipping_phone ?? ($order->user->phone ?? '') }}</p>
                <p>{{ $order->shipping_email ?? ($order->user->email ?? '') }}</p>
            </div>
            <div class="billing-col text-right">
                <h3>{{ __('messages.payment_info') }}</h3>
                <p>{{ __('messages.payment_method_label') }}: {{ __('messages.' . $order->payment_method) ?? $order->payment_method }}</p>
                <p>{{ __('messages.payment_status_label') }}: {{ $order->payment_status == 'paid' ? __('messages.paid') : __('messages.unpaid') }}</p>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>{{ __('messages.product') }}</th>
                    <th class="text-right">{{ __('messages.unit_price') }}</th>
                    <th class="text-center">{{ __('messages.quantity_short') }}</th>
                    <th class="text-right">{{ __('messages.amount') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>
                        <strong>{{ $item->snapshot_name ?? ($item->productVariant && $item->productVariant->product ? $item->productVariant->product->name : __('messages.product_fallback')) }}</strong>
                        <br>
                        <small style="color: #7f8c8d;">
                            @php
                                $sku = $item->snapshot_sku && $item->snapshot_sku !== 'N/A' ? $item->snapshot_sku : ($item->productVariant->sku ?? '');
                                $attributes = [];
                                if ($item->snapshot_color && $item->snapshot_color !== 'N/A') $attributes[] = $item->snapshot_color;
                                if ($item->snapshot_size && $item->snapshot_size !== 'N/A') $attributes[] = $item->snapshot_size;
                                if (empty($attributes) && $item->productVariant && $item->productVariant->options) {
                                    foreach ($item->productVariant->options as $option) {
                                        if ($option->attribute && $option->attributeValue) {
                                            $attributes[] = $option->attribute->name . ': ' . $option->attributeValue->value;
                                        }
                                    }
                                }
                            @endphp
                            {{ $sku ? 'SKU: ' . $sku : '' }}
                            @if(!empty($attributes)) - @endif
                            {{ implode(' | ', $attributes) }}
                        </small>
                    </td>
                    <td class="text-right">{{ number_format($item->unit_price) }}đ</td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-right">{{ number_format($item->unit_price * $item->quantity) }}đ</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total-section">
            <div class="total-row">
                <span>{{ __('messages.subtotal') }}:</span>
                <span>{{ number_format($order->subtotal) }}đ</span>
            </div>
            @if(($order->discount_amount ?? 0) > 0)
            <div class="total-row" style="color: #27ae60;">
                <span>{{ __('messages.discount') }}:</span>
                <span>-{{ number_format($order->discount_amount) }}đ</span>
            </div>
            @endif
            @if(($order->tax ?? 0) > 0)
            <div class="total-row">
                <span>{{ __('messages.tax') }}:</span>
                <span>{{ number_format($order->tax) }}đ</span>
            </div>
            @endif
            <div class="total-row">
                <span>{{ __('messages.shipping_fee') }}:</span>
                <span>{{ number_format($order->shipping_cost ?? 0) }}đ</span>
            </div>
            <div class="total-row final">
                <span>{{ __('messages.total_due') }}:</span>
                <span>{{ number_format($order->total) }}đ</span>
            </div>
        </div>

        <div class="footer">
            <p>{{ __('messages.thank_you') }}</p>
            <p>{{ __('messages.contact_hotline') }}</p>
        </div>
    </div>

    <button onclick="window.print()" class="print-btn">
        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
        {{ __('messages.print_invoice') }}
    </button>
</body>
</html>