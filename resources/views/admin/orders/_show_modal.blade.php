<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-6 animate-slide-in bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-800 mb-2">{{ __('messages.order_title') }} #{{ $order->id }}</h1>
                <p class="text-sm text-slate-500">
                    {{ $order->created_at->format('d/m/Y H:i') }}
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <span class="px-4 py-2 rounded-lg bg-gray-100 text-gray-700 font-medium capitalize">
                    {{ __('messages.status_' . $order->status) }}
                </span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Order Details -->
            <div class="animate-slide-in bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-slate-800">{{ __('messages.product_details') }}</h2>
                </div>

                <!-- Products Table -->
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b-2 border-gray-50">
                                <th class="text-left py-3 px-2 text-sm font-semibold text-slate-600">{{ __('messages.product') }}</th>
                                <th class="text-right py-3 px-2 text-sm font-semibold text-slate-600">{{ __('messages.price') }}</th>
                                <th class="text-center py-3 px-2 text-sm font-semibold text-slate-600">{{ __('messages.quantity_short') }}</th>
                                <th class="text-right py-3 px-2 text-sm font-semibold text-slate-600">{{ __('messages.total') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr class="border-b border-gray-50">
                                <td class="py-4 px-2">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center text-xl overflow-hidden shrink-0">
                                            @if($item->productVariant && $item->productVariant->product && $item->productVariant->product->images && $item->productVariant->product->images->count() > 0)
                                                <img src="{{ $item->productVariant->product->images->first()->url }}" alt="" class="w-full h-full object-cover">
                                            @else
                                                <i class="fas fa-box text-gray-400"></i>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-slate-800 mb-1">
                                                {{ $item->snapshot_name ?? ($item->productVariant && $item->productVariant->product ? $item->productVariant->product->name : __('messages.product_fallback')) }}
                                            </p>
                                            <p class="text-xs text-slate-500">
                                                @php
                                                    $sku = $item->snapshot_sku && $item->snapshot_sku !== 'N/A' ? $item->snapshot_sku : ($item->productVariant->sku ?? '');
                                                    $attributes = [];
                                                    
                                                    // Try snapshot first
                                                    if ($item->snapshot_color && $item->snapshot_color !== 'N/A') $attributes[] = $item->snapshot_color;
                                                    if ($item->snapshot_size && $item->snapshot_size !== 'N/A') $attributes[] = $item->snapshot_size;

                                                    // If no snapshot attributes, try current variant
                                                    if (empty($attributes) && $item->productVariant && $item->productVariant->options) {
                                                        foreach ($item->productVariant->options as $option) {
                                                            if ($option->attribute && $option->attributeValue) {
                                                                    $attributes[] = $option->attribute->name . ': ' . $option->attributeValue->value;
                                                            }
                                                        }
                                                    }
                                                @endphp
                                                
                                                {{ $sku ? 'SKU: ' . $sku : '' }}
                                                @foreach($attributes as $attr)
                                                    <span class="mx-1">|</span> {{ $attr }}
                                                @endforeach
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-2 text-right text-sm text-slate-800">
                                    {{ number_format($item->unit_price) }}đ
                                </td>
                                <td class="py-4 px-2 text-center text-sm text-slate-800">
                                    {{ $item->quantity }}
                                </td>
                                <td class="py-4 px-2 text-right text-sm font-semibold text-slate-800">
                                    {{ number_format($item->unit_price * $item->quantity) }}đ
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Summary -->
                <div class="mt-6 pt-6 border-t-2 border-gray-50">
                    <div class="space-y-3 max-w-sm ml-auto">
                        <div class="flex justify-between items-center">
                            <span class="text-base text-slate-800">{{ __('messages.subtotal') }}:</span>
                            <span class="text-base font-semibold text-slate-800">{{ number_format($order->subtotal) }}đ</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-base text-emerald-500">{{ __('messages.discount') }}:</span>
                            <span class="text-base font-semibold text-emerald-500">-{{ number_format($order->discount_amount ?? 0) }}đ</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-base text-slate-800">{{ __('messages.tax') }}:</span>
                            <span class="text-base font-semibold text-slate-800">{{ number_format($order->tax ?? 0) }}đ</span>
                        </div>
                            <div class="flex justify-between items-center">
                            <span class="text-base text-slate-800">{{ __('messages.shipping_fee') }}:</span>
                            <span class="text-base font-semibold text-slate-800">{{ number_format($order->shipping_cost ?? 0) }}đ</span>
                        </div>
                        <div class="border-t-2 border-gray-50 pt-3 mt-3">
                            <div class="flex justify-between items-center">
                                <span class="text-lg font-bold text-slate-800">{{ __('messages.total_due') }}:</span>
                                <span class="text-lg font-bold text-slate-800">{{ number_format($order->total) }}đ</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Customer Details -->
            <div class="animate-slide-in bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <h2 class="text-xl font-bold text-slate-800 mb-5">{{ __('messages.customer') }}</h2>
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-emerald-500 flex items-center justify-center text-white text-xl font-bold">
                        {{ substr($order->shipping_name ?? ($order->user->name ?? 'G'), 0, 1) }}
                    </div>
                    <div>
                        <p class="text-base font-semibold text-slate-800">
                            {{ $order->shipping_name ?? ($order->user->name ?? __('messages.guest_customer')) }}
                        </p>
                        <p class="text-sm text-slate-500">
                            ID: #{{ $order->user_id ?? 'N/A' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Contact Info -->
            <div class="animate-slide-in bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-slate-800">{{ __('messages.contact_header') }}</h3>
                </div>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-slate-500 mb-1">{{ __('messages.email') }}</p>
                        <p class="text-sm text-slate-800 break-all">{{ $order->shipping_email ?? ($order->user->email ?? 'N/A') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500 mb-1">{{ __('messages.phone_number') }}</p>
                        <p class="text-sm text-slate-800">{{ $order->shipping_phone ?? ($order->user->phone ?? 'N/A') }}</p>
                    </div>
                </div>
            </div>

            <!-- Shipping Address -->
            <div class="animate-slide-in bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-slate-800">{{ __('messages.shipping_address_header') }}</h3>
                </div>
                <p class="text-sm text-slate-600 leading-relaxed">
                    {{ $order->shipping_address ?? ($order->user->address ?? 'N/A') }}<br>
                    {{ $order->shipping_city ?? ($order->user->city ?? '') }}
                </p>
            </div>

            <!-- Payment Method -->
            <div class="animate-slide-in bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <h3 class="text-lg font-bold text-slate-800 mb-3"><i class="fas fa-credit-card mr-2"></i> {{ __('messages.payment') }}</h3>
                <div class="flex items-center p-3 bg-slate-50 rounded-lg border border-slate-100">
                    @if($order->payment_method == 'cod')
                        <i class="fas fa-money-bill-wave text-green-500 text-xl mr-3"></i>
                        <div>
                            <p class="font-semibold text-slate-800">{{ __('messages.cod') }}</p>
                            <p class="text-xs text-slate-500">{{ __('messages.cod_desc_customer') }}</p>
                        </div>
                    @else
                        <i class="fas fa-credit-card text-blue-500 text-xl mr-3"></i>
                        <div>
                            <p class="font-semibold text-slate-800">{{ __('messages.' . $order->payment_method) }}</p>
                            <p class="text-xs text-slate-500">{{ __('messages.payment_via') }} {{ __('messages.' . $order->payment_method) }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
