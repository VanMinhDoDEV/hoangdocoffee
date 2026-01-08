<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
 <head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ __('messages.stock_movement_history') }}</title>
  <link rel="shortcut icon" type="image/x-icon" href="{{ !empty($storeSettings['favicon']) ? asset($storeSettings['favicon']) : asset('assets/images/favicon.png') }}">
  <link rel="icon" type="image/png" href="{{ !empty($storeSettings['favicon']) ? asset($storeSettings['favicon']) : asset('assets/images/favicon.png') }}">
  @vite(['resources/css/app.css','resources/js/app.js'])
  <style>
    .nav-link{transition:all .2s ease}
    .nav-link:hover{background-color:rgba(255,255,255,.1)}
    .nav-link.active{background-color:rgba(255,255,255,.15);border-left:3px solid #3498db}
    .table-row{transition:background-color .2s ease}
    .table-row:hover{background-color:#f9fafb}
  </style>
 </head>
 <body class="bg-gray-50">
  <div class="w-full min-h-screen flex">
   @include('admin.partials.sidebar')
   <main class="flex-1 overflow-auto md:ml-64 transition-all duration-300">
    @include('admin.partials.topbar', ['title' => __('messages.stock_movement_history')])
    <div class="p-8">
      <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6">
        <div class="p-6 flex items-center justify-between">
          <div>
            <h2 class="text-xl font-semibold text-gray-800">{{ __('messages.warehouse') }}: {{ $warehouse->name ?? 'MAIN' }}</h2>
            <p class="text-gray-500 text-sm mt-1">{{ __('messages.movement_tracking_desc') }}</p>
          </div>
          <div class="flex items-center gap-2">
            <form class="flex items-center gap-2" method="get" action="{{ route('admin.inventory.movements') }}">
              <input type="hidden" name="warehouse_id" value="{{ $warehouse->id ?? '' }}">
              <select name="type" class="border rounded-lg px-3 py-2 text-sm">
                <option value="">{{ __('messages.all_types') }}</option>
                <option value="receipt" {{ request('type')==='receipt'?'selected':'' }}>{{ __('messages.receipt') }}</option>
                <option value="shipment" {{ request('type')==='shipment'?'selected':'' }}>{{ __('messages.shipment') }}</option>
                <option value="adjustment" {{ request('type')==='adjustment'?'selected':'' }}>{{ __('messages.adjustment') }}</option>
                <option value="reservation" {{ request('type')==='reservation'?'selected':'' }}>{{ __('messages.reservation') }}</option>
                <option value="release" {{ request('type')==='release'?'selected':'' }}>{{ __('messages.release') }}</option>
              </select>
              <input type="text" name="q" value="{{ request('q') }}" placeholder="{{ __('messages.search_sku_product') }}" class="border rounded-lg px-3 py-2 text-sm w-52">
              <button class="px-3 py-2 rounded-lg bg-blue-600 text-white text-sm">{{ __('messages.filter') }}</button>
            </form>
          </div>
        </div>
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead>
              <tr class="border-b border-gray-100 bg-gray-50">
                <th class="text-left py-3 px-6 text-xs font-semibold text-gray-600 uppercase tracking-wider">{{ __('messages.time') }}</th>
                <th class="text-left py-3 px-6 text-xs font-semibold text-gray-600 uppercase tracking-wider">{{ __('messages.type') }}</th>
                <th class="text-left py-3 px-6 text-xs font-semibold text-gray-600 uppercase tracking-wider">{{ __('messages.product') }}</th>
                <th class="text-left py-3 px-6 text-xs font-semibold text-gray-600 uppercase tracking-wider">{{ __('messages.quantity') }}</th>
                <th class="text-left py-3 px-6 text-xs font-semibold text-gray-600 uppercase tracking-wider">{{ __('messages.notes') }}</th>
              </tr>
            </thead>
            <tbody>
              @forelse($movements as $m)
              <tr class="table-row border-b border-gray-100">
                <td class="py-3 px-6 text-sm text-gray-700">{{ $m->created_at }}</td>
                <td class="py-3 px-6 text-sm">
                  @php
                    $typeLabel = [
                      'receipt' => __('messages.receipt'),
                      'shipment' => __('messages.shipment'),
                      'adjustment' => __('messages.adjustment'),
                      'reservation' => __('messages.reservation'),
                      'release' => __('messages.release'),
                    ][$m->movement_type] ?? $m->movement_type;
                  @endphp
                  <span class="inline-flex items-center px-2 py-1 rounded bg-slate-100 text-slate-700 text-xs">{{ $typeLabel }}</span>
                </td>
                <td class="py-3 px-6 text-sm text-gray-700">{{ optional($m->variant)->sku }}</td>
                <td class="py-3 px-6 text-sm text-gray-700">{{ $m->quantity }}</td>
                <td class="py-3 px-6 text-sm text-gray-500">{{ $m->notes }}</td>
              </tr>
              @empty
              <tr>
                <td colspan="6" class="py-8 px-6 text-center text-gray-500">{{ __('messages.no_movements') }}</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
        <div class="p-4">
          @include('admin.partials.pagination', ['paginator' => $movements, 'entityName' => __('messages.inventory_movement')])
        </div>
      </div>
    </div>
   </main>
  </div>
 </body>
</html>
