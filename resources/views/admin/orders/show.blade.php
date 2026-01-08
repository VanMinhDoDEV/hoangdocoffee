@extends('layouts.admin')

@section('title', 'Chi tiết đơn hàng #' . $order->id)

@push('styles')
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <style>
    @keyframes slideIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-slide-in { animation: slideIn 0.3s ease-out; }
    .status-badge { position: relative; overflow: hidden; }
    .status-badge::before {
        content: ''; position: absolute; top: 0; left: -100%; width: 100%; height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        transition: left 0.5s;
    }
    .status-badge:hover::before { left: 100%; }
  </style>
@endpush

@section('content')
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-6 animate-slide-in bg-white rounded-2xl p-6 shadow-sm">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-slate-800 mb-2">{{ __('messages.order_title') }} #{{ $order->id }}</h1>
                    <p class="text-sm text-slate-500">
                        {{ $order->created_at->format('d/m/Y H:i') }}
                    </p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <!-- Status Update -->
                    <div class="relative">
                        <select id="orderStatus" class="input-primary appearance-none block w-full pr-8 bg-gray-50">
                            @foreach(['new', 'processing', 'shipped', 'completed', 'cancelled', 'failed', 'refunded'] as $status)
                                <option value="{{ $status }}" {{ $order->status == $status ? 'selected' : '' }}>
                                    {{ __('messages.status_' . $status) }}
                                </option>
                            @endforeach
                        </select>
                         <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                          </div>
                    </div>
                    <button onclick="updateStatus()" class="bg-blue-600 hover:bg-blue-700 text-white p-2.5 rounded-lg transition-colors" title="{{ __('messages.update_status') }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </button>

                    <!-- Edit Button -->
                    <button onclick="openEditModal()" class="bg-amber-500 hover:bg-amber-600 text-white p-2.5 rounded-lg transition-colors" title="{{ __('messages.edit_order') }}">
                         <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    </button>

                    <!-- Delete Button -->
                    <button onclick="deleteOrder()" class="bg-red-500 hover:bg-red-600 text-white p-2.5 rounded-lg transition-colors" title="{{ __('messages.delete_order') }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>

                     <!-- Invoice Button -->
                     <a href="{{ route('admin.orders.invoice', $order->id) }}" target="_blank" class="bg-slate-700 hover:bg-slate-800 text-white p-2.5 rounded-lg transition-colors" title="{{ __('messages.print_invoice') ?? 'In hóa đơn' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Order Details -->
                <div class="animate-slide-in bg-white rounded-2xl p-6 shadow-sm" style="animation-delay: 0.1s;">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold text-slate-800">{{ __('messages.product_details') }}</h2>
                        <!-- <button class="text-blue-500 text-sm font-semibold px-4 py-2 rounded-lg border-2 border-blue-500 hover:bg-blue-500 hover:text-white transition-all">
                            Edit
                        </button> -->
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
                                            <div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center text-xl overflow-hidden">
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

                <!-- Shipping Activity -->
                <div class="animate-slide-in bg-white rounded-2xl p-6 shadow-sm" style="animation-delay: 0.2s;">
                    <h2 class="text-2xl font-bold text-slate-800 mb-6">{{ __('messages.order_status_timeline') }}</h2>
                    <div class="space-y-6">
                        <!-- Timeline Item: Placed -->
                        <div class="flex gap-4">
                            <div class="flex flex-col items-center">
                                <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center text-white text-lg">
                                    <i class="fas fa-box"></i>
                                </div>
                                <div class="w-0.5 h-10 bg-slate-200 mt-2"></div>
                            </div>
                            <div class="flex-1 pb-6">
                                <h4 class="text-base font-semibold text-slate-800 mb-1">{{ __('messages.order_placed') }}</h4>
                                <p class="text-sm text-slate-500 mb-1">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                                <p class="text-sm text-slate-600">{{ __('messages.order_initialized_success') }}</p>
                            </div>
                        </div>
                        
                        <!-- Timeline Item: Current Status -->
                        @php
                            $statusColors = [
                                'pending' => 'bg-yellow-500',
                                'processing' => 'bg-blue-500',
                                'shipped' => 'bg-indigo-500',
                                'completed' => 'bg-green-500',
                                'cancelled' => 'bg-red-500',
                                'refunded' => 'bg-gray-500',
                                'new' => 'bg-blue-400',
                                'failed' => 'bg-red-600'
                            ];
                            $statusColor = $statusColors[$order->status] ?? 'bg-gray-500';
                        @endphp
                        <div class="flex gap-4">
                            <div class="flex flex-col items-center">
                                <div class="w-10 h-10 rounded-full {{ $statusColor }} flex items-center justify-center text-white text-lg">
                                    <i class="fas fa-check"></i>
                                </div>
                            </div>
                            <div class="flex-1 pb-6">
                                <h4 class="text-base font-semibold text-slate-800 mb-1">{{ __('messages.' . $order->status) }}</h4>
                                <p class="text-sm text-slate-500 mb-1">{{ $order->updated_at->format('d/m/Y H:i') }}</p>
                                <p class="text-sm text-slate-600">{{ __('messages.current_order_status') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Customer Details -->
                <div class="animate-slide-in bg-white rounded-2xl p-6 shadow-sm" style="animation-delay: 0.3s;">
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
                    <!-- <p class="text-sm font-semibold text-blue-500">
                        {{ $order->user ? $order->user->orders()->count() . ' Orders' : 'Guest' }}
                    </p> -->
                </div>

                <!-- Contact Info -->
                <div class="animate-slide-in bg-white rounded-2xl p-6 shadow-sm" style="animation-delay: 0.4s;">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-slate-800">{{ __('messages.contact_header') }}</h3>
                        <!-- <button class="text-blue-500 text-sm font-semibold">Edit</button> -->
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
                <div class="animate-slide-in bg-white rounded-2xl p-6 shadow-sm" style="animation-delay: 0.5s;">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-slate-800">{{ __('messages.shipping_address_header') }}</h3>
                        <!-- <button class="text-blue-500 text-sm font-semibold">Edit</button> -->
                    </div>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        {{ $order->shipping_address ?? ($order->user->address ?? 'N/A') }}<br>
                        {{ $order->shipping_city ?? ($order->user->city ?? '') }}
                    </p>
                </div>

                <!-- Payment Method -->
                <div class="animate-slide-in bg-white rounded-2xl p-6 shadow-sm" style="animation-delay: 0.6s;">
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

    <!-- Edit Order Modal -->
    <div id="editOrderModal" class="fixed inset-0 z-[9999] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <!-- Background backdrop -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeEditModal()"></div>

        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <!-- Modal panel -->
                <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-4xl">
                    <form id="editOrderForm" onsubmit="event.preventDefault(); submitEditForm();">
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="w-full">
                            <h3 class="text-lg font-semibold leading-6 text-gray-900 mb-4" id="modal-title">
                                {{ __('messages.edit_order_title') }} #{{ $order->id }}
                            </h3>
                            
                            <!-- Tabs -->
                            <div class="border-b border-gray-200 mb-4">
                                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                                    <button type="button" onclick="switchTab('general')" id="tab-general" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm active-tab">
                                        {{ __('messages.general_tab') }}
                                    </button>
                                    <button type="button" onclick="switchTab('address')" id="tab-address" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                        {{ __('messages.address_tab') }}
                                    </button>
                                    <button type="button" onclick="switchTab('items')" id="tab-items" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                        {{ __('messages.items_tab') }}
                                    </button>
                                </nav>
                            </div>

                                <!-- General Tab -->
                                <div id="content-general" class="tab-content space-y-4">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.order_status') }}</label>
                                            <select name="status" class="input-primary">
                                                @foreach(['new', 'processing', 'shipped', 'completed', 'cancelled', 'failed', 'refunded'] as $status)
                                                    <option value="{{ $status }}" {{ $order->status == $status ? 'selected' : '' }}>
                                                        {{ __('messages.status_' . $status) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.payment_status') }}</label>
                                            <select name="payment_status" class="input-primary">
                                                @foreach(['paid', 'pending', 'failed'] as $status)
                                                    <option value="{{ $status }}" {{ ($order->payment_status ?? 'pending') == $status ? 'selected' : '' }}>
                                                        {{ __('messages.payment_status_' . $status) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.payment_method') }}</label>
                                            <select name="payment_method" class="input-primary">
                                                @foreach(['cod', 'banking', 'momo', 'vnpay', 'zalopay'] as $method)
                                                    <option value="{{ $method }}" {{ ($order->payment_method ?? 'cod') == $method ? 'selected' : '' }}>
                                                        {{ __('messages.payment_method_' . $method) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Address Tab -->
                                <div id="content-address" class="tab-content hidden space-y-4">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="col-span-2 md:col-span-1">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.recipient_name') }}</label>
                                            <input type="text" name="shipping_name" value="{{ $order->shipping_name }}" class="input-primary">
                                        </div>
                                        <div class="col-span-2 md:col-span-1">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.phone_number') }}</label>
                                            <input type="text" name="shipping_phone" value="{{ $order->shipping_phone }}" class="input-primary">
                                        </div>
                                        <div class="col-span-2">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.email') }}</label>
                                            <input type="email" name="shipping_email" value="{{ $order->shipping_email }}" class="input-primary">
                                        </div>
                                        <div class="col-span-2">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.detailed_address') }}</label>
                                            <input type="text" name="shipping_address" value="{{ $order->shipping_address }}" class="input-primary">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.province_city') }}</label>
                                            <input type="text" name="shipping_province" value="{{ $order->shipping_province }}" class="input-primary">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.ward_commune') }}</label>
                                            <input type="text" name="shipping_ward" value="{{ $order->shipping_ward }}" class="input-primary">
                                        </div>
                                    </div>
                                </div>

                                <!-- Items Tab -->
                                <div id="content-items" class="tab-content hidden space-y-4">
                                    <div class="overflow-x-auto border rounded-lg">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('messages.items_tab') }}</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('messages.price_col') }}</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('messages.quantity_col') }}</th>
                                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('messages.action_col') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200" id="items-list">
                                                @foreach($order->items as $item)
                                                <tr id="item-row-{{ $item->id }}">
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $item->snapshot_name ?? ($item->productVariant && $item->productVariant->product ? $item->productVariant->product->name : __('messages.product_fallback')) }}
                                                        </div>
                                                        <div class="text-sm text-gray-500">
                                                            {{ $item->snapshot_sku ? 'SKU: ' . $item->snapshot_sku : '' }}
                                                        </div>
                                                        <input type="hidden" name="items[{{ $loop->index }}][id]" value="{{ $item->id }}">
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <input type="number" name="items[{{ $loop->index }}][unit_price]" value="{{ $item->unit_price }}" class="input-primary w-28">
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <input type="number" name="items[{{ $loop->index }}][quantity]" value="{{ $item->quantity }}" min="1" class="input-primary w-20">
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                        <button type="button" onclick="removeItem({{ $item->id }})" class="text-red-600 hover:text-red-900 font-semibold">{{ __('messages.delete_item') }}</button>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        <div id="removed-items-container"></div>
                                    </div>
                                </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                                <button type="submit" class="inline-flex w-full justify-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 sm:ml-3 sm:w-auto">{{ __('messages.save_changes') }}</button>
                                <button type="button" onclick="closeEditModal()" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">{{ __('messages.cancel_btn') }}</button>
                            </div>
                </form>
            </div>
        </div>
    </div>
    </div>

    <script>
        window.openEditModal = function() {
            console.log('Opening modal...');
            const modal = document.getElementById('editOrderModal');
            if (modal) {
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden'; // Prevent background scrolling
            } else {
                console.error('Modal element not found!');
            }
        }

        window.closeEditModal = function() {
            const modal = document.getElementById('editOrderModal');
            if (modal) {
                modal.classList.add('hidden');
                document.body.style.overflow = ''; // Restore scrolling
            }
        }

        window.switchTab = function(tabName) {
            // Hide all content
            document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
            // Show selected content
            document.getElementById('content-' + tabName).classList.remove('hidden');
            
            // Update tab styles
            document.querySelectorAll('nav button').forEach(el => {
                el.classList.remove('border-blue-500', 'text-blue-600');
                el.classList.add('border-transparent', 'text-gray-500');
            });
            document.getElementById('tab-' + tabName).classList.remove('border-transparent', 'text-gray-500');
            document.getElementById('tab-' + tabName).classList.add('border-blue-500', 'text-blue-600');
        }

        window.removeItem = function(itemId) {
            if(!confirm('{{ __('messages.confirm_remove_item_from_order') }}')) return;
            
            // Hide the row
            document.getElementById('item-row-' + itemId).style.display = 'none';
            
            // Add to removed items list
            const container = document.getElementById('removed-items-container');
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'removed_items[]';
            input.value = itemId;
            container.appendChild(input);
        }

        window.submitEditForm = function() {
            const form = document.getElementById('editOrderForm');
            const formData = new FormData(form);
            
            formData.append('_method', 'PUT');

            fetch('{{ route('admin.orders.update', $order->id) }}', {
                method: 'POST', // Use POST with _method PUT for FormData support
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('{{ __('messages.order_update_success') }}');
                    location.reload();
                } else {
                    alert(data.message || '{{ __('messages.error_occurred') }}');
                    console.error(data);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('{{ __('messages.error_updating_order') }}');
            });
        }

        window.updateStatus = function() {
            const status = document.getElementById('orderStatus').value;
            fetch('{{ route('admin.orders.update', $order->id) }}', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ status: status })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('{{ __('messages.status_update_success') }}');
                    location.reload();
                } else {
                    alert(data.message || '{{ __('messages.error_occurred') }}');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('{{ __('messages.update_status_error') }}');
            });
        }

        window.deleteOrder = function() {
            if(!confirm('{{ __('messages.confirm_delete_order') }}')) return;
            
            fetch('{{ route('admin.orders.destroy', $order->id) }}', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('{{ __('messages.delete_order_success') }}');
                    window.location.href = '{{ route('admin.orders') }}';
                } else {
                    alert('{{ __('messages.delete_order_error') }}: ' + (data.message || '{{ __('messages.unknown') }}'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('{{ __('messages.delete_order_error') }}');
            });
        }
    </script>
@endsection