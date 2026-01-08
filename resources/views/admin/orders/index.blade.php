<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
 <head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ __('messages.orders') }}</title>
  <link rel="shortcut icon" type="image/x-icon" href="{{ !empty($storeSettings['favicon']) ? asset($storeSettings['favicon']) : asset('assets/images/favicon.png') }}">
  <link rel="icon" type="image/png" href="{{ !empty($storeSettings['favicon']) ? asset($storeSettings['favicon']) : asset('assets/images/favicon.png') }}">
  @vite(['resources/css/app.css','resources/js/app.js'])
  <style>
    .nav-link{transition:all .2s ease}
    .nav-link:hover{background-color:rgba(255,255,255,.1)}
    .nav-link.active{background-color:rgba(255,255,255,.15);border-left:3px solid #3498db}
  </style>
 </head>
 <body class="bg-gray-50">
  <div class="w-full min-h-screen flex">
   @include('admin.partials.sidebar')
   <main class="flex-1 overflow-auto md:ml-64 transition-all duration-300">
    @include('admin.partials.topbar', ['title' => __('messages.orders')])
    <div class="p-2 sm:p-8">
      <style>
        .stat-card { transition: all 0.3s ease; }
        .stat-card:hover { transform: translateY(-4px); box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1); }
        .table-row { transition: background-color 0.2s ease; }
        .table-row:hover { background-color: #f9fafb; }
        .status-badge { display: inline-flex; align-items: center; padding: 4px 12px; border-radius: 9999px; font-size: 12px; font-weight: 500; }
        .status-paid { background-color: #d1fae5; color: #065f46; }
        .status-pending { background-color: #fef3c7; color: #92400e; }
        .status-failed { background-color: #fee2e2; color: #991b1b; }
        .status-cancelled { background-color: #f3f4f6; color: #374151; }
        .delivery-badge { display: inline-flex; align-items: center; padding: 4px 12px; border-radius: 6px; font-size: 12px; font-weight: 500; background-color: #eff6ff; color: #1e40af; }
        .avatar-circle { width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 14px; color: white; }
        .action-menu { animation: slideIn 0.15s ease-out; }
        @keyframes slideIn { from { opacity: 0; transform: translateY(-8px); } to { opacity: 1; transform: translateY(0); } }
        .action-menu-item { transition: all 0.15s ease; }
        .action-menu-item:active { transform: scale(0.98); }
      </style>
      @php
        if (!function_exists('getPaymentStatusClass')) {
          function getPaymentStatusClass($status) {
            $s = strtolower((string)$status);
            if ($s === 'paid') return 'status-paid';
            if ($s === 'pending') return 'status-pending';
            if ($s === 'failed') return 'status-failed';
            if ($s === 'cancelled') return 'status-cancelled';
            return 'status-pending';
          }
        }
        $pendingOrders = $stats['pending'] ?? 0;
        $completedOrders = $stats['completed'] ?? 0;
        $refundedOrders = $stats['refunded'] ?? 0;
        $failedOrders = $stats['failed'] ?? 0;
      @endphp
      <div class="w-full min-h-full">
        @php
          $current = strtolower((string)request()->query('status', ''));
          $cards = [];
          if ($current === '') {
            $cards = [
              ['label' => __('messages.pending_payment'), 'count' => $stats['pending'] ?? 0, 'bg' => 'amber-100', 'icon' => 'clock', 'text' => 'amber-600'],
              ['label' => __('messages.completed'), 'count' => $stats['completed'] ?? 0, 'bg' => 'green-100', 'icon' => 'check', 'text' => 'green-600'],
              ['label' => __('messages.refunded'), 'count' => $stats['refunded'] ?? 0, 'bg' => 'blue-100', 'icon' => 'arrow', 'text' => 'blue-600'],
              ['label' => __('messages.failed'), 'count' => $stats['failed'] ?? 0, 'bg' => 'red-100', 'icon' => 'x', 'text' => 'red-600'],
            ];
          } elseif ($current === 'new') {
            $cards = [
              ['label' => __('messages.new_orders'), 'count' => $stats['pending'] ?? 0, 'bg' => 'amber-100', 'icon' => 'clock', 'text' => 'amber-600'],
              ['label' => __('messages.shipped'), 'count' => $stats['shipped'] ?? 0, 'bg' => 'blue-100', 'icon' => 'truck', 'text' => 'blue-600'],
              ['label' => __('messages.completed'), 'count' => $stats['completed'] ?? 0, 'bg' => 'green-100', 'icon' => 'check', 'text' => 'green-600'],
              ['label' => __('messages.cancelled'), 'count' => $stats['refunded'] ?? 0, 'bg' => 'gray-100', 'icon' => 'ban', 'text' => 'gray-700'],
            ];
          } elseif ($current === 'shipped') {
            $cards = [
              ['label' => __('messages.shipped'), 'count' => $stats['shipped'] ?? 0, 'bg' => 'blue-100', 'icon' => 'truck', 'text' => 'blue-600'],
              ['label' => __('messages.delivered'), 'count' => $stats['completed'] ?? 0, 'bg' => 'green-100', 'icon' => 'check', 'text' => 'green-600'],
              ['label' => __('messages.cancelled'), 'count' => $stats['refunded'] ?? 0, 'bg' => 'gray-100', 'icon' => 'ban', 'text' => 'gray-700'],
              ['label' => __('messages.new'), 'count' => $stats['pending'] ?? 0, 'bg' => 'amber-100', 'icon' => 'clock', 'text' => 'amber-600'],
            ];
          } elseif ($current === 'completed') {
            $cards = [
              ['label' => __('messages.completed'), 'count' => $stats['completed'] ?? 0, 'bg' => 'green-100', 'icon' => 'check', 'text' => 'green-600'],
              ['label' => __('messages.cancelled'), 'count' => $stats['refunded'] ?? 0, 'bg' => 'gray-100', 'icon' => 'ban', 'text' => 'gray-700'],
              ['label' => __('messages.new'), 'count' => $stats['pending'] ?? 0, 'bg' => 'amber-100', 'icon' => 'clock', 'text' => 'amber-600'],
              ['label' => __('messages.failed'), 'count' => $stats['failed'] ?? 0, 'bg' => 'red-100', 'icon' => 'x', 'text' => 'red-600'],
            ];
          } elseif ($current === 'cancelled') {
            $cards = [
              ['label' => __('messages.cancelled'), 'count' => $stats['refunded'] ?? 0, 'bg' => 'gray-100', 'icon' => 'ban', 'text' => 'gray-700'],
              ['label' => __('messages.completed'), 'count' => $stats['completed'] ?? 0, 'bg' => 'green-100', 'icon' => 'check', 'text' => 'green-600'],
              ['label' => __('messages.new'), 'count' => $stats['pending'] ?? 0, 'bg' => 'amber-100', 'icon' => 'clock', 'text' => 'amber-600'],
              ['label' => __('messages.failed'), 'count' => $stats['failed'] ?? 0, 'bg' => 'red-100', 'icon' => 'x', 'text' => 'red-600'],
            ];
          } elseif ($current === 'failed') {
            $cards = [
              ['label' => __('messages.failed'), 'count' => $stats['failed'] ?? 0, 'bg' => 'red-100', 'icon' => 'x', 'text' => 'red-600'],
              ['label' => __('messages.cancelled'), 'count' => $stats['refunded'] ?? 0, 'bg' => 'gray-100', 'icon' => 'ban', 'text' => 'gray-700'],
              ['label' => __('messages.completed'), 'count' => $stats['completed'] ?? 0, 'bg' => 'green-100', 'icon' => 'check', 'text' => 'green-600'],
              ['label' => __('messages.new'), 'count' => $stats['pending'] ?? 0, 'bg' => 'amber-100', 'icon' => 'clock', 'text' => 'amber-600'],
            ];
          } elseif ($current === 'processing') {
            $cards = [
              ['label' => __('messages.processing'), 'count' => $stats['processing'] ?? 0, 'bg' => 'amber-100', 'icon' => 'clock', 'text' => 'amber-600'],
              ['label' => __('messages.shipped'), 'count' => $stats['shipped'] ?? 0, 'bg' => 'blue-100', 'icon' => 'truck', 'text' => 'blue-600'],
              ['label' => __('messages.completed'), 'count' => $stats['completed'] ?? 0, 'bg' => 'green-100', 'icon' => 'check', 'text' => 'green-600'],
              ['label' => __('messages.cancelled'), 'count' => $stats['refunded'] ?? 0, 'bg' => 'gray-100', 'icon' => 'ban', 'text' => 'gray-700'],
            ];
          }
        @endphp
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
          @foreach($cards as $c)
          <div class="stat-card bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm text-gray-600 mb-1">{{ $c['label'] }}</p>
                <p class="text-3xl font-bold text-gray-900">{{ $c['count'] }}</p>
              </div>
              <div class="w-12 h-12 rounded-xl flex items-center justify-center bg-{{ $c['bg'] }}">
                @if($c['icon'] === 'check')
                <svg class="w-6 h-6 text-{{ $c['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                @elseif($c['icon'] === 'clock')
                <svg class="w-6 h-6 text-{{ $c['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                @elseif($c['icon'] === 'truck')
                <svg class="w-6 h-6 text-{{ $c['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h10v8H3zM13 10h5l3 3v2h-8V10zM5 21a2 2 0 100-4 2 2 0 000 4zm12 0a2 2 0 100-4 2 2 0 000 4z" /></svg>
                @elseif($c['icon'] === 'ban')
                <svg class="w-6 h-6 text-{{ $c['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636A9 9 0 105.636 18.364 9 9 0 0018.364 5.636zM6 6l12 12" /></svg>
                @elseif($c['icon'] === 'x')
                <svg class="w-6 h-6 text-{{ $c['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                @else
                <svg class="w-6 h-6 text-{{ $c['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9" stroke-width="2"></circle></svg>
                @endif
              </div>
            </div>
          </div>
          @endforeach
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
          <form method="GET" action="{{ route('admin.orders') }}" class="p-6 border-b border-gray-100">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-4">
              <!-- Search -->
              <div class="lg:col-span-4">
                <label class="block text-xs font-medium text-gray-700 mb-1">{{ __('messages.search') }}</label>
                <div class="relative">
                  <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                  <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('messages.search_order') }}" class="input-primary pl-10 w-full h-10">
                </div>
              </div>

              <!-- Status -->
              <div class="lg:col-span-2">
                <label class="block text-xs font-medium text-gray-700 mb-1">{{ __('messages.status') }}</label>
                <select name="status" class="input-primary w-full h-10">
                  <option value="">{{ __('messages.all_statuses') }}</option>
                  @foreach(['new', 'processing', 'shipped', 'completed', 'cancelled', 'failed'] as $s)
                    <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ __('messages.status_' . $s) }}</option>
                  @endforeach
                </select>
              </div>

              <!-- Date From -->
              <div class="lg:col-span-2">
                <label class="block text-xs font-medium text-gray-700 mb-1">{{ __('messages.from_date') }}</label>
                <input type="text" name="date_from" value="{{ request('date_from') }}" class="input-primary datepicker w-full h-10" placeholder="YYYY-MM-DD">
              </div>

              <!-- Date To -->
              <div class="lg:col-span-2">
                <label class="block text-xs font-medium text-gray-700 mb-1">{{ __('messages.to_date') }}</label>
                <input type="text" name="date_to" value="{{ request('date_to') }}" class="input-primary datepicker w-full h-10" placeholder="YYYY-MM-DD">
              </div>

              <!-- Buttons -->
              <div class="lg:col-span-2 flex items-end gap-2">
                <button type="submit" class="h-10 px-4 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center justify-center gap-1 w-full" title="{{ __('messages.filter') }}">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" /></svg>
                </button>
                <a href="{{ route('admin.orders') }}" class="h-10 px-3 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 transition-colors flex items-center justify-center" title="{{ __('messages.reset') }}">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                </a>
                 <a href="{{ route('admin.orders.export', request()->query()) }}" class="h-10 px-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors flex items-center justify-center" title="{{ __('messages.export_excel') }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                </a>
              </div>
            </div>
          </form>
          <div class="overflow-x-auto">
            <table class="w-full">
              <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                  <th class="px-6 py-4 text-left"><input type="checkbox" id="select-all-checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2 cursor-pointer"></th>
                  <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">{{ __('messages.order') }}</th>
                  <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">{{ __('messages.date') }}</th>
                  <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">{{ __('messages.customer') }}</th>
                  <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">{{ __('messages.payment') }}</th>
                  <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">{{ __('messages.status') }}</th>
                  <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">{{ __('messages.method') }}</th>
                  <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">{{ __('messages.actions') }}</th>
                </tr>
              </thead>
              <tbody id="orders-table" class="divide-y divide-gray-100"></tbody>
            </table>
          </div>
          <div class="px-6 py-4 border-t border-gray-100">
            @include('admin.partials.pagination', ['paginator' => $orders, 'entityName' => __('messages.orders')])
          </div>
        </div>
      </div>
      @include('admin.components.order-detail-modal')
      <script>
        const translations = {
          paid: "{{ __('messages.paid') }}",
          pending: "{{ __('messages.pending') }}",
          failed: "{{ __('messages.status_failed') }}",
          cancelled: "{{ __('messages.status_cancelled') }}",
          processing: "{{ __('messages.status_processing') }}",
          completed: "{{ __('messages.status_completed') }}",
          shipped: "{{ __('messages.status_shipped') }}",
          delivered: "{{ __('messages.status_shipped') }}", // Map delivered to shipped if needed or add status_delivered
          new: "{{ __('messages.status_new') }}",
          refunded: "{{ __('messages.status_refunded') }}"
        };
        function t(key) {
          return translations[(key || '').toLowerCase()] || key;
        }

        const orders = @json($uiOrders ?? []);
        function getPaymentStatusClass(status) {
          switch((status || '').toLowerCase()) {
            case 'paid': return 'status-paid';
            case 'pending': return 'status-pending';
            case 'failed': return 'status-failed';
            case 'cancelled': return 'status-cancelled';
            default: return 'status-pending';
          }
        }
        function renderOrders() {
          const tableBody = document.getElementById('orders-table');
          tableBody.innerHTML = (orders || []).map(order => `
            <tr class="table-row">
              <td class="px-6 py-4">
                <input type="checkbox" class="order-checkbox w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2 cursor-pointer" data-order-id="${order.id}" />
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <a href="{{ url('/admin/orders') }}/${order.id}" class="text-sm font-semibold text-blue-600 hover:underline">#${order.id}</a>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="text-sm text-gray-600">${order.date || ''}</span>
              </td>
              <td class="px-6 py-4">
                <div class="flex items-center gap-3">
                  ${order.avatar ?
                    `<img src="${order.avatar}" alt="${order.name}" class="w-9 h-9 rounded-full object-cover" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                     <div class="avatar-circle" style="background-color: ${order.color || '#3b82f6'}; display: none;">${order.initials || ''}</div>` :
                    `<div class="avatar-circle" style="background-color: ${order.color || '#3b82f6'};">${order.initials || ''}</div>`
                  }
                  <div>
                    <p class="text-sm font-medium text-gray-900">${order.name || ''}</p>
                    <p class="text-xs text-gray-500">${order.email || ''}</p>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4">
                <span class="status-badge ${getPaymentStatusClass(order.payment || '')}">${t(order.payment || '')}</span>
              </td>
              <td class="px-6 py-4">
                <span class="delivery-badge">${t(order.status || '')}</span>
              </td>
              <td class="px-6 py-4">
                ${order.method === 'paypal' ?
                  `<div class="flex items-center gap-2">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="#00457C">
                      <path d="M7.076 21.337H2.47a.641.641 0 0 1-.633-.74L4.944 3.72a.77.77 0 0 1 .76-.633h8.346c2.49 0 4.234.488 5.177 1.45.91.93 1.252 2.266 1.02 3.973-.026.185-.06.378-.103.578-.13.6-.293 1.24-.49 1.893a8.78 8.78 0 0 1-3.316 4.605c-1.43.99-3.28 1.482-5.5 1.482h-.687c-.415 0-.77.3-.834.71l-.87 5.56Z"/>
                    </svg>
                    <span class="text-xs text-gray-500">{{ __('messages.paypal') }}</span>
                  </div>` :
                  `<div class="flex items-center gap-2">
                    <svg class="w-8 h-6" viewBox="0 0 48 32" fill="none">
                      <rect width="48" height="32" rx="4" fill="#252525"/>
                      <circle cx="18" cy="16" r="8" fill="#EB001B"/>
                      <circle cx="30" cy="16" r="8" fill="#F79E1B"/>
                      <path d="M24 9.5a8.5 8.5 0 0 0 0 13 8.5 8.5 0 0 0 0-13Z" fill="#FF5F00"/>
                    </svg>
                    <span class="text-xs text-gray-500">••${order.methodLast4 || '0000'}</span>
                  </div>`
                }
              </td>
              <td class="px-6 py-4">
                <div class="relative">
                  <button class="action-menu-btn p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg" data-order-id="${order.id}">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                      <path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/>
                    </svg>
                  </button>
                  <div class="action-menu hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-10" data-menu-id="${order.id}">
                    <a href="#" onclick="event.preventDefault(); openOrderModal(${order.id})" class="action-menu-item w-full flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-t-lg">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                      </svg>
                      <span class="font-medium">{{ __('messages.view') }}</span>
                    </a>
                    <a href="${`{{ url('/admin/orders') }}`}/${order.id}" class="action-menu-item w-full flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-green-50 hover:text-green-600">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                      </svg>
                      <span class="font-medium">{{ __('messages.edit') }}</span>
                    </a>
                    <button class="action-menu-item w-full flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600 rounded-b-lg">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                      </svg>
                      <span class="font-medium">{{ __('messages.delete') }}</span>
                    </button>
                  </div>
                </div>
              </td>
            </tr>
          `).join('');
        }
        document.addEventListener('DOMContentLoaded', function() {

          renderOrders();
          const selectAllCheckbox = document.getElementById('select-all-checkbox');
          if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
              const orderCheckboxes = document.querySelectorAll('.order-checkbox');
              orderCheckboxes.forEach(checkbox => { checkbox.checked = this.checked; });
            });
          }
          const table = document.getElementById('orders-table');
          if (table) {
            table.addEventListener('click', function(e) {
              const menuBtn = e.target.closest('.action-menu-btn');
              if (menuBtn) {
                e.stopPropagation();
                const orderId = menuBtn.getAttribute('data-order-id');
                const menu = document.querySelector(`[data-menu-id="${orderId}"]`);
                document.querySelectorAll('.action-menu').forEach(m => { if (m !== menu) m.classList.add('hidden'); });
                if (menu) menu.classList.toggle('hidden');
              }
              const menuItem = e.target.closest('.action-menu-item');
              if (menuItem) {
                const menu = menuItem.closest('.action-menu');
                if (menu) { menu.classList.add('hidden'); }
              }
            });
          }
          document.addEventListener('click', function() {
            document.querySelectorAll('.action-menu').forEach(menu => { menu.classList.add('hidden'); });
          });
        });
      </script>
   </main>
  </div>
  <script>
    window.toggleSubmenu = function(id){
      const submenu=document.getElementById(id+'-submenu');
      const arrow=document.getElementById(id+'-arrow');
      if(!submenu||!arrow)return;
      submenu.classList.toggle('hidden');
      arrow.classList.toggle('rotate-180');
    };
  </script>
</body>
</html>
