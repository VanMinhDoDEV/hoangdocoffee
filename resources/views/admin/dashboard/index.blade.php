@extends('layouts.admin')

@section('title') {{ __('messages.dashboard') }} @endsection

@push('styles')
<style>
    .stat-card { transition: transform 0.2s; }
    .stat-card:hover { transform: translateY(-5px); }
</style>
@endpush

@section('content')
    <div class="max-xxl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <div class="bg-white p-6 rounded-2xl shadow-sm flex items-center space-x-4 stat-card">
                <div class="bg-indigo-100 p-3 rounded-lg text-indigo-600"><i class="fas fa-users text-xl"></i></div>
                <div>
                    <h3 class="text-2xl font-bold">{{ number_format($stats['users']) }}</h3>
                    <p class="text-gray-500 text-sm">{{ __('messages.total_customers') }}</p>
                </div>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm flex items-center space-x-4 stat-card">
                <div class="bg-orange-100 p-3 rounded-lg text-orange-600"><i class="fas fa-box text-xl"></i></div>
                <div>
                    <h3 class="text-2xl font-bold">{{ number_format($stats['products']) }}</h3>
                    <p class="text-gray-500 text-sm">{{ __('messages.total_products') }}</p>
                </div>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm flex items-center space-x-4 stat-card">
                <div class="bg-pink-100 p-3 rounded-lg text-pink-600"><i class="fas fa-file-invoice text-xl"></i></div>
                <div>
                    <h3 class="text-2xl font-bold">{{ number_format($stats['orders']) }}</h3>
                    <p class="text-gray-500 text-sm">{{ __('messages.total_orders') }}</p>
                </div>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm flex items-center space-x-4 stat-card">
                <div class="bg-green-100 p-3 rounded-lg text-green-600"><i class="fas fa-chart-line text-xl"></i></div>
                <div>
                    <h3 class="text-2xl font-bold">{{ number_format($stats['revenue'], 0, '.', ',') }}</h3>
                    <p class="text-gray-500 text-sm">{{ __('messages.total_sales') }}</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <div class="lg:col-span-2 bg-white p-6 rounded-2xl shadow-sm">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-bold text-lg text-gray-800">{{ __('messages.sales_trend') }}</h3>
                    <div class="flex items-center space-x-4 text-xs">
                        <span class="flex items-center"><span class="w-3 h-3 bg-indigo-500 rounded-full mr-1"></span> {{ __('messages.current_year') }}</span>
                        <span class="flex items-center"><span class="w-3 h-3 bg-red-500 rounded-full mr-1"></span> {{ __('messages.last_year') }}</span>
                    </div>
                </div>
                <div class="h-64">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-bold text-lg text-gray-800">{{ __('messages.product_views') }}</h3>
                    <div class="text-[10px]">
                        <p class="text-indigo-600">● {{ __('messages.this_week') }}</p>
                        <p class="text-red-500">● {{ __('messages.last_week') }}</p>
                    </div>
                </div>
                <div class="h-64">
                    <canvas id="viewsChart"></canvas>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 bg-white p-6 rounded-2xl shadow-sm overflow-x-auto">
                <h3 class="font-bold text-lg mb-4">{{ __('messages.all_orders') }}</h3>
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-gray-400 text-sm border-b">
                            <th class="pb-3 font-medium">{{ __('messages.order_id') }}</th>
                            <th class="pb-3 font-medium">{{ __('messages.customer_name') }}</th>
                            <th class="pb-3 font-medium">{{ __('messages.date') }}</th>
                            <th class="pb-3 font-medium">{{ __('messages.price') }}</th>
                            <th class="pb-3 font-medium">{{ __('messages.status') }}</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @forelse($recentOrders as $order)
                        <tr class="border-b last:border-0 hover:bg-gray-50">
                            <td class="py-4"><a href="{{ route('admin.orders.show', $order->id) }}" class="text-blue-600 hover:underline">#{{ $order->id }}</a></td>
                            <td class="py-4">{{ $order->user->name ?? __('messages.unknown') }}</td>
                            <td class="py-4 text-gray-500">{{ $order->created_at->format('d M y') }}</td>
                            <td class="py-4 font-semibold">{{ number_format($order->total, 0, '.', ',') }}</td>
                            <td class="py-4">
                                <span class="px-3 py-1 rounded-full text-xs 
                                    {{ $order->status == 'completed' ? 'bg-green-100 text-green-600' : 
                                      ($order->status == 'pending' ? 'bg-yellow-100 text-yellow-600' : 
                                      ($order->status == 'cancelled' ? 'bg-red-100 text-red-600' : 'bg-gray-100 text-gray-600')) }}">
                                    {{ __('messages.' . $order->status) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="py-4 text-center text-gray-500">{{ __('messages.no_orders_found') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm">
                <h3 class="font-bold text-lg mb-6">{{ __('messages.top_categories') }}</h3>
                <div class="space-y-6">
                    @forelse($topCategories as $cat)
                    <div>
                        <div class="flex justify-between text-sm mb-2"><span>{{ $cat->name }}</span><span>{{ $cat->percentage }}%</span></div>
                        <div class="w-full bg-gray-100 h-2 rounded-full">
                            @php
                                $colors = ['bg-indigo-400', 'bg-orange-400', 'bg-red-400', 'bg-green-400'];
                                $color = $colors[$loop->index % 4];
                            @endphp
                            <div class="{{ $color }} h-2 rounded-full" style="width: {{ $cat->percentage }}%"></div>
                        </div>
                    </div>
                    @empty
                    <div class="text-gray-500 text-sm">{{ __('messages.no_sales_data_yet') }}</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@php
    $months = [
        __('messages.jan'), __('messages.feb'), __('messages.mar'), __('messages.apr'), 
        __('messages.may'), __('messages.jun'), __('messages.jul'), __('messages.aug'), 
        __('messages.sep'), __('messages.oct'), __('messages.nov'), __('messages.dec')
    ];
    $days = [
        __('messages.sun_short'), __('messages.mon_short'), __('messages.tue_short'), 
        __('messages.wed_short'), __('messages.thu_short'), __('messages.fri_short'), 
        __('messages.sat_short')
    ];
@endphp
<script>
    // Line Chart Configuration
    const salesCtx = document.getElementById('salesChart').getContext('2d');
    new Chart(salesCtx, {
        type: 'line',
        data: {
            labels: @json($months),
            datasets: [{
                label: '{{ __('messages.current_year') }}',
                data: @json($salesData['current']),
                borderColor: '#6366f1',
                tension: 0.4,
                fill: false
            }, {
                label: '{{ __('messages.last_year') }}',
                data: @json($salesData['last']),
                borderColor: '#ef4444',
                tension: 0.4,
                fill: false
            }]
        },
        options: { maintainAspectRatio: false, plugins: { legend: { display: false } } }
    });

    // Bar Chart Configuration (Product Views)
    const viewsCtx = document.getElementById('viewsChart').getContext('2d');
    new Chart(viewsCtx, {
        type: 'bar',
        data: {
            labels: @json($days),
            datasets: [{
                label: '{{ __('messages.last_week') }}',
                data: [0, 0, 0, 0, 0, 0, 0], // Placeholder: view data not tracked historically
                backgroundColor: '#ef4444',
                borderRadius: 5
            }, {
                label: '{{ __('messages.this_week') }}',
                data: [0, 0, 0, 0, 0, 0, 0], // Placeholder: view data not tracked historically
                backgroundColor: '#a5b4fc',
                borderRadius: 5
            }]
        },
        options: { maintainAspectRatio: false, plugins: { legend: { display: false } } }
    });
</script>
@endpush
