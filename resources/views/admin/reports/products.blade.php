@extends('layouts.admin')

@section('title', __('messages.best_selling_products'))

@section('content')
<div class="space-y-6">
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
        <div>
            <p class="text-slate-500 mt-1">{{ __('messages.top_20_best_selling') }} - <span id="period-label" class="font-semibold text-indigo-600 lowercase first-letter:uppercase">{{ $periodLabel ?? __('messages.this_month') }}</span></p>
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

    <!-- Chart -->
    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
        <div class="relative w-full h-[400px]">
            <canvas id="productsChart"></canvas>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4 font-semibold text-slate-600">#</th>
                        <th class="px-6 py-4 font-semibold text-slate-600">{{ __('messages.product') }}</th>
                        <th class="px-6 py-4 font-semibold text-slate-600">SKU</th>
                        <th class="px-6 py-4 font-semibold text-slate-600 text-right">{{ __('messages.selling_price') }}</th>
                        <th class="px-6 py-4 font-semibold text-slate-600 text-right">{{ __('messages.sold') }}</th>
                        <th class="px-6 py-4 font-semibold text-slate-600 text-right">{{ __('messages.total_revenue') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100" id="products-table-body">
                    @forelse($topProducts as $index => $product)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 text-slate-500">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 font-medium text-slate-800">{{ $product->name }}</td>
                        <td class="px-6 py-4 text-slate-500 font-mono text-sm">{{ $product->sku }}</td>
                        <td class="px-6 py-4 text-right text-slate-600">{{ number_format($product->price, 0, ',', '.') }} đ</td>
                        <td class="px-6 py-4 text-right font-bold text-blue-600">{{ $product->total_qty }}</td>
                        <td class="px-6 py-4 text-right font-bold text-indigo-600">{{ number_format($product->total_revenue, 0, ',', '.') }} đ</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-slate-500">{{ __('messages.no_product_sales_data') }}</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('productsChart').getContext('2d');
        let chart;

        function initChart(labels, data) {
            if (chart) {
                chart.destroy();
            }

            // Truncate labels for display
            const displayLabels = labels.map(l => l.substring(0, 20) + (l.length > 20 ? '...' : ''));

            chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: displayLabels,
                    datasets: [{
                        label: '{{ __('messages.revenue') }} ({{ __('messages.currency_code') }})',
                        data: data,
                        backgroundColor: '#6366f1',
                        borderRadius: 4,
                        barPercentage: 0.6
                    }]
                },
                options: {
                    indexAxis: 'y', // Horizontal bar chart
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(context.raw);
                                },
                                title: function(context) {
                                    // Show full name on hover
                                    return labels[context[0].dataIndex];
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            grid: {
                                color: '#f1f5f9'
                            },
                            ticks: {
                                callback: function(value) {
                                    return new Intl.NumberFormat('vi-VN', { notation: "compact", compactDisplay: "short" }).format(value);
                                }
                            }
                        },
                        y: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }

        // Initial Chart
        const initialProducts = @json($topProducts->take(10));
        const initialLabels = initialProducts.map(p => p.name);
        const initialData = initialProducts.map(p => p.total_revenue);
        initChart(initialLabels, initialData);

        // Filter Handling
        const filterButtons = document.querySelectorAll('.filter-btn');
        const customDateRange = document.getElementById('custom-date-range');
        const startDateInput = document.getElementById('start-date');
        const endDateInput = document.getElementById('end-date');
        const applyCustomBtn = document.getElementById('apply-custom');

        function fetchData(params) {
             fetch(`{{ route('admin.reports.products') }}?${params}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                // Update Period Label
                document.getElementById('period-label').innerText = data.periodLabel;
                
                // Update Chart (take top 10)
                const top10Products = data.topProducts.slice(0, 10);
                const labels = top10Products.map(p => p.name);
                const chartData = top10Products.map(p => p.total_revenue);
                initChart(labels, chartData);
                
                // Update Table
                const tbody = document.getElementById('products-table-body');
                tbody.innerHTML = '';
                
                if (data.topProducts.length === 0) {
                    tbody.innerHTML = `<tr><td colspan="6" class="px-6 py-8 text-center text-slate-500">{{ __('messages.no_product_sales_data') }}</td></tr>`;
                } else {
                    data.topProducts.forEach((product, index) => {
                        const tr = document.createElement('tr');
                        tr.className = 'hover:bg-slate-50 transition-colors';
                        tr.innerHTML = `
                            <td class="px-6 py-4 text-slate-500">${index + 1}</td>
                            <td class="px-6 py-4 font-medium text-slate-800">${product.name}</td>
                            <td class="px-6 py-4 text-slate-500 font-mono text-sm">${product.sku}</td>
                            <td class="px-6 py-4 text-right text-slate-600">${new Intl.NumberFormat('vi-VN').format(product.price)} đ</td>
                            <td class="px-6 py-4 text-right font-bold text-blue-600">${product.total_qty}</td>
                            <td class="px-6 py-4 text-right font-bold text-indigo-600">${new Intl.NumberFormat('vi-VN').format(product.total_revenue)} đ</td>
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
            const start = startDateInput.value;
            const end = endDateInput.value;
            if (start && end) {
                fetchData(`period=custom&start_date=${start}&end_date=${end}`);
            }
        });
    });
</script>
@endpush
