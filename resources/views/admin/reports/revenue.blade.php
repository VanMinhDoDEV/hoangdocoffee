@extends('layouts.admin')

@section('title', __('messages.revenue_report'))

@section('content')
<div class="space-y-6">
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">{{ __('messages.revenue_report') }}</h1>
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

    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
        <div class="relative w-full h-96">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
        <h3 class="text-xl font-bold text-slate-800 mb-6">{{ __('messages.details') }}</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="px-6 py-4 font-semibold text-slate-600">{{ __('messages.date') }}</th>
                        <th class="px-6 py-4 font-semibold text-slate-600 text-right">{{ __('messages.revenue') }}</th>
                    </tr>
                </thead>
                <tbody id="revenue-table-body" class="divide-y divide-slate-100">
                    @forelse($dailyRevenue as $item)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 text-slate-800">{{ \Carbon\Carbon::parse($item->date)->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 text-right font-bold text-indigo-600">{{ number_format($item->total, 0, ',', '.') }} đ</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="2" class="px-6 py-8 text-center text-slate-500">{{ __('messages.no_revenue_data') }}</td>
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
        const ctx = document.getElementById('revenueChart').getContext('2d');
        let revenueChart;
        const initialLabels = @json($labels);
        const initialData = @json($data);

        function initChart(labels, data) {
            if (revenueChart) {
                revenueChart.destroy();
            }
            
            // Format dates for chart labels
            const formattedLabels = labels.map(date => {
                const d = new Date(date);
                return `${d.getDate()}/${d.getMonth() + 1}`;
            });

            revenueChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: formattedLabels,
                    datasets: [{
                        label: '{{ __('messages.revenue') }} ({{ __('messages.currency_code') }})',
                        data: data,
                        borderColor: '#4f46e5',
                        backgroundColor: 'rgba(79, 70, 229, 0.1)',
                        borderWidth: 3,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#4f46e5',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'top' },
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
                            grid: { color: '#f1f5f9' },
                            ticks: {
                                callback: function(value) {
                                    return new Intl.NumberFormat('vi-VN', { maximumSignificantDigits: 3 }).format(value);
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
             fetch(`{{ route('admin.reports.revenue') }}?${params}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                // Update Period Label
                document.getElementById('period-label').innerText = data.periodLabel;
                
                // Update Chart
                initChart(data.labels, data.data);
                
                // Update Table
                const tbody = document.getElementById('revenue-table-body');
                tbody.innerHTML = '';
                
                if (data.dailyRevenue.length === 0) {
                    tbody.innerHTML = `<tr><td colspan="2" class="px-6 py-8 text-center text-slate-500">{{ __('messages.no_revenue_data') }}</td></tr>`;
                } else {
                    data.dailyRevenue.forEach(item => {
                        const d = new Date(item.date);
                        const formattedDate = `${String(d.getDate()).padStart(2, '0')}/${String(d.getMonth() + 1).padStart(2, '0')}/${d.getFullYear()}`;
                        
                        const tr = document.createElement('tr');
                        tr.className = 'hover:bg-slate-50 transition-colors';
                        tr.innerHTML = `
                            <td class="px-6 py-4 text-slate-800">${formattedDate}</td>
                            <td class="px-6 py-4 text-right font-bold text-indigo-600">${new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(item.total).replace('₫', '').trim()} đ</td>
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
                alert('{{ __('messages.check_related_data') }}'); 
                return;
            }

            fetchData(`period=custom&start_date=${startDate}&end_date=${endDate}`);
        });
    });
</script>
@endpush
