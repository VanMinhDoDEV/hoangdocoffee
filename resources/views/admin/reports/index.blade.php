@extends('layouts.admin')

@section('title', __('messages.overview_report'))

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">{{ __('messages.overview_report') }}</h1>
            <p class="text-slate-500 mt-1 flex items-center gap-2">
                <span>{{ __('messages.reporting_period') }}:</span>
                <span id="period-label" class="font-medium text-indigo-600">{{ \Carbon\Carbon::now()->startOfMonth()->format('d/m/Y') }} - {{ \Carbon\Carbon::now()->endOfMonth()->format('d/m/Y') }}</span>
            </p>
        </div>
        <div class="flex flex-wrap gap-2 items-center">
            <div id="custom-date-range" class="hidden flex items-center gap-2 bg-white p-1 rounded-lg border border-slate-200 shadow-sm mr-2">
                <input type="date" id="start-date" placeholder="{{ __('messages.start_date') }}" class="text-sm border-none focus:ring-0 text-slate-600 bg-transparent p-1">
                <span class="text-slate-400">-</span>
                <input type="date" id="end-date" placeholder="{{ __('messages.end_date') }}" class="text-sm border-none focus:ring-0 text-slate-600 bg-transparent p-1">
                <button id="apply-custom" class="bg-indigo-600 text-white text-xs px-2 py-1 rounded hover:bg-indigo-700 transition-colors">{{ __('messages.apply') }}</button>
            </div>
            <div class="flex flex-wrap gap-2" id="report-filters">
                <button data-period="today" class="filter-btn px-3 py-1.5 text-sm font-medium rounded-lg bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 hover:text-indigo-600 transition-colors">{{ __('messages.today') }}</button>
                <button data-period="yesterday" class="filter-btn px-3 py-1.5 text-sm font-medium rounded-lg bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 hover:text-indigo-600 transition-colors">{{ __('messages.yesterday') }}</button>
                <button data-period="this_week" class="filter-btn px-3 py-1.5 text-sm font-medium rounded-lg bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 hover:text-indigo-600 transition-colors">{{ __('messages.this_week') }}</button>
                <button data-period="this_month" class="filter-btn px-3 py-1.5 text-sm font-medium rounded-lg bg-indigo-50 border border-indigo-200 text-indigo-600 transition-colors">{{ __('messages.this_month') }}</button>
                <button data-period="this_year" class="filter-btn px-3 py-1.5 text-sm font-medium rounded-lg bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 hover:text-indigo-600 transition-colors">{{ __('messages.this_year') }}</button>
                <button data-period="custom" class="filter-btn px-3 py-1.5 text-sm font-medium rounded-lg bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 hover:text-indigo-600 transition-colors">{{ __('messages.custom') }}</button>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Revenue -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <span id="revenue-growth" class="px-3 py-1 rounded-lg text-xs font-bold bg-green-50 text-green-700">+{{ $revenueGrowth }}%</span>
            </div>
            <p class="text-slate-500 text-sm font-medium mb-1">{{ __('messages.revenue') }}</p>
            <h3 id="revenue-value" class="text-2xl font-bold text-slate-800">{{ number_format($revenue, 0, ',', '.') }} đ</h3>
        </div>

        <!-- Orders -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                </div>
            </div>
            <p class="text-slate-500 text-sm font-medium mb-1">{{ __('messages.completed_orders') }}</p>
            <h3 id="orders-count" class="text-2xl font-bold text-slate-800">{{ $ordersCount }}</h3>
        </div>

        <!-- Profit (Est) -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-purple-50 flex items-center justify-center text-purple-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                </div>
                <span id="margin-value" class="px-3 py-1 rounded-lg text-xs font-bold bg-purple-50 text-purple-700">{{ __('messages.margin') }}: {{ $revenue > 0 ? number_format(($profit / $revenue) * 100, 1) : 0 }}%</span>
            </div>
            <p class="text-slate-500 text-sm font-medium mb-1">{{ __('messages.gross_profit_est') }}</p>
            <h3 id="profit-value" class="text-2xl font-bold text-slate-800">{{ number_format($profit, 0, ',', '.') }} đ</h3>
        </div>

        <!-- Avg Order Value -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-orange-50 flex items-center justify-center text-orange-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                </div>
            </div>
            <p class="text-slate-500 text-sm font-medium mb-1">{{ __('messages.avg_order_value') }}</p>
            <h3 id="avg-order-value" class="text-2xl font-bold text-slate-800">{{ $ordersCount > 0 ? number_format($revenue / $ordersCount, 0, ',', '.') : 0 }} đ</h3>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Top Products -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
            <h3 class="text-xl font-bold text-slate-800 mb-6">🏆 {{ __('messages.top_5_best_selling_products') }}</h3>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-slate-50 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                            <th class="px-4 py-3 rounded-l-lg">{{ __('messages.product') }}</th>
                            <th class="px-4 py-3 text-right">{{ __('messages.quantity') }}</th>
                            <th class="px-4 py-3 rounded-r-lg text-right">{{ __('messages.revenue') }}</th>
                        </tr>
                    </thead>
                    <tbody id="top-products-body" class="divide-y divide-slate-100">
                        @foreach($topProducts as $index => $product)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-3">
                                    <span class="w-6 h-6 rounded flex items-center justify-center text-xs font-bold bg-indigo-100 text-indigo-700">{{ $index + 1 }}</span>
                                    <span class="font-medium text-slate-700 truncate max-w-[200px]">{{ $product->name }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-4 text-right font-medium text-slate-600">{{ $product->total_qty }}</td>
                            <td class="px-4 py-4 text-right font-bold text-slate-800">{{ number_format($product->total_revenue, 0, ',', '.') }} đ</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Revenue Chart -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm flex flex-col">
            <h3 id="chart-title" class="text-xl font-bold text-slate-800 mb-6 w-full text-left">📊 {{ __('messages.revenue') }}</h3>
            <div class="relative w-full h-64">
                <canvas id="overviewChart"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('overviewChart').getContext('2d');
        let overviewChart;
        const initialLabels = @json($chartLabels);
        const initialData = @json($chartData);

        function initChart(labels, data) {
            if (overviewChart) {
                overviewChart.destroy();
            }
            overviewChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: '{{ __('messages.revenue') }} (VNĐ)',
                        data: data,
                        backgroundColor: '#4f46e5',
                        borderRadius: 8,
                        barThickness: 'flex',
                        maxBarThickness: 30
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) label += ': ';
                                    if (context.parsed.y !== null) {
                                        label += new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(context.parsed.y);
                                    }
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { display: true, drawBorder: false },
                            ticks: {
                                callback: function(value) {
                                    return new Intl.NumberFormat('vi-VN', { notation: "compact", compactDisplay: "short" }).format(value);
                                }
                            }
                        },
                        x: { grid: { display: false } }
                    }
                }
            });
        }

        initChart(initialLabels, initialData);

        // Handle Filter Clicks
        const filterButtons = document.querySelectorAll('.filter-btn');
        const customDateRange = document.getElementById('custom-date-range');
        const startDateInput = document.getElementById('start-date');
        const endDateInput = document.getElementById('end-date');
        const applyCustomBtn = document.getElementById('apply-custom');

        function fetchData(params) {
             fetch(`{{ route('admin.reports.index') }}?${params}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                // Update Text Values
                document.getElementById('revenue-value').innerText = data.revenue;
                document.getElementById('orders-count').innerText = data.ordersCount;
                document.getElementById('revenue-growth').innerText = (data.revenueGrowth > 0 ? '+' : '') + data.revenueGrowth + '%';
                document.getElementById('profit-value').innerText = data.profit;
                document.getElementById('margin-value').innerText = '{{ __('messages.margin') }}: ' + data.margin;
                document.getElementById('avg-order-value').innerText = data.avgOrderValue;
                document.getElementById('period-label').innerText = data.periodLabel;
                
                // Update Chart
                initChart(data.chartLabels, data.chartData);
                
                // Update Top Products Table
                const tbody = document.getElementById('top-products-body');
                tbody.innerHTML = '';
                if(data.topProducts.length === 0) {
                     tbody.innerHTML = `<tr><td colspan="3" class="px-4 py-4 text-center text-slate-500">{{ __('messages.no_product_sales_data') }}</td></tr>`;
                } else {
                    data.topProducts.forEach((product, index) => {
                        const tr = document.createElement('tr');
                        tr.className = 'hover:bg-slate-50 transition-colors';
                        tr.innerHTML = `
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-3">
                                    <span class="w-6 h-6 rounded flex items-center justify-center text-xs font-bold bg-indigo-100 text-indigo-700">${index + 1}</span>
                                    <span class="font-medium text-slate-700 truncate max-w-[200px]">${product.name}</span>
                                </div>
                            </td>
                            <td class="px-4 py-4 text-right font-medium text-slate-600">${product.total_qty}</td>
                            <td class="px-4 py-4 text-right font-bold text-slate-800">${new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(product.total_revenue).replace('₫', '').trim()} đ</td>
                        `;
                        tbody.appendChild(tr);
                    });
                }
                showToast('success', '{{ __('messages.success') }}', '{{ __('messages.filter_applied_successfully') }}');
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('error', '{{ __('messages.error') }}', '{{ __('messages.failed_to_apply_filter') }}');
            });
        }

        filterButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                const period = this.dataset.period;
                
                // Update UI active state
                filterButtons.forEach(b => {
                    b.classList.remove('bg-indigo-50', 'border-indigo-200', 'text-indigo-600');
                    b.classList.add('bg-white', 'border-slate-200', 'text-slate-600', 'hover:bg-slate-50', 'hover:text-indigo-600');
                });
                this.classList.remove('bg-white', 'border-slate-200', 'text-slate-600', 'hover:bg-slate-50', 'hover:text-indigo-600');
                this.classList.add('bg-indigo-50', 'border-indigo-200', 'text-indigo-600');

                if (period === 'custom') {
                    customDateRange.classList.remove('hidden');
                } else {
                    customDateRange.classList.add('hidden');
                    fetchData(`period=${period}`);
                }
            });
        });

        applyCustomBtn.addEventListener('click', function() {
            const startDate = startDateInput.value;
            const endDate = endDateInput.value;

            if (!startDate || !endDate) {
                alert('{{ __('messages.check_related_data') }}'); // Or a more specific message
                return;
            }

            fetchData(`period=custom&start_date=${startDate}&end_date=${endDate}`);
        });
    });
</script>
@endpush
