@extends('layouts.admin')

@section('title', __('messages.customer_report'))

@section('content')
<div class="space-y-6">
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
        <div>
            <h2 class="text-xl font-bold text-slate-800">{{ __('messages.customer_report') }} - <span id="period-label" class="font-semibold text-indigo-600 lowercase first-letter:uppercase">{{ $periodLabel ?? __('messages.this_month') }}</span></h2>
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

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
            <p class="text-slate-500 text-sm font-medium mb-1">{{ __('messages.new_customers_this_month') }}</p>
            <h3 class="text-3xl font-bold text-slate-800" id="new-customers">{{ $newCustomers }}</h3>
        </div>
        <!-- Placeholders for more complex stats -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
            <p class="text-slate-500 text-sm font-medium mb-1">{{ __('messages.returning_customers') }}</p>
            <h3 class="text-3xl font-bold text-slate-800" id="returning-customers">{{ $returningCustomers }}</h3>
            <p class="text-xs text-slate-400 mt-2">{{ __('messages.loyal_customers') }}</p>
        </div>
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
            <p class="text-slate-500 text-sm font-medium mb-1">{{ __('messages.avg_lifetime_value') }}</p>
            <h3 class="text-3xl font-bold text-slate-800" id="clv">{{ number_format($clv, 0, ',', '.') }} {{ __('messages.currency_symbol') }}</h3>
            <p class="text-xs text-slate-400 mt-2">{{ __('messages.avg_revenue_per_customer') }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Top Customers Table -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100">
                <h3 class="text-lg font-bold text-slate-800">💎 {{ __('messages.top_highest_spending_customers') }}</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-4 font-semibold text-slate-600">{{ __('messages.customer') }}</th>
                            <th class="px-6 py-4 font-semibold text-slate-600 text-center">{{ __('messages.order_count') }}</th>
                            <th class="px-6 py-4 font-semibold text-slate-600 text-center">{{ __('messages.last_order_date') }}</th>
                            <th class="px-6 py-4 font-semibold text-slate-600 text-right">{{ __('messages.total_spent') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100" id="top-customers-body">
                        @forelse($topCustomers as $customer)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4">
                                <div>
                                    <div class="font-medium text-slate-800">{{ $customer->name }}</div>
                                    <div class="text-sm text-slate-500">{{ $customer->email }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center text-slate-600">{{ $customer->order_count }}</td>
                            <td class="px-6 py-4 text-center text-slate-600">{{ \Carbon\Carbon::parse($customer->last_order_date)->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 text-right font-bold text-indigo-600">{{ number_format($customer->total_spent, 0, ',', '.') }} {{ __('messages.currency_symbol') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-slate-500">{{ __('messages.no_customer_data') }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Customer Segmentation Chart -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
            <h3 class="text-lg font-bold text-slate-800 mb-6">{{ __('messages.customer_segmentation') }}</h3>
            <div class="relative w-full h-64 flex items-center justify-center">
                <canvas id="customerChart"></canvas>
            </div>
            <div class="mt-4 text-center text-sm text-slate-500">
                ({{ __('messages.segmentation_by_behavior') }})
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('customerChart').getContext('2d');
        let chart;

        function initChart(labels, data) {
            if (chart) {
                chart.destroy();
            }

            chart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: data,
                        backgroundColor: [
                            '#60a5fa', // Blue
                            '#34d399', // Green
                            '#f472b6', // Pink
                            '#9ca3af'  // Gray
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                padding: 20
                            }
                        }
                    },
                    cutout: '70%'
                }
            });
        }

        // Initial Chart
        const initialLabels = @json($segmentationLabels);
        const initialData = @json($segmentationData);
        initChart(initialLabels, initialData);

        // Filter Handling
        const filterButtons = document.querySelectorAll('.filter-btn');
        const customDateRange = document.getElementById('custom-date-range');
        const startDateInput = document.getElementById('start-date');
        const endDateInput = document.getElementById('end-date');
        const applyCustomBtn = document.getElementById('apply-custom');

        function fetchData(params) {
             fetch(`{{ route('admin.reports.customers') }}?${params}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                // Update Period Label
                document.getElementById('period-label').innerText = data.periodLabel;
                
                // Update Stats
                document.getElementById('new-customers').innerText = data.newCustomers;
                document.getElementById('returning-customers').innerText = data.returningCustomers;
                document.getElementById('clv').innerText = new Intl.NumberFormat('vi-VN').format(data.clv) + ' {{ __('messages.currency_symbol') }}';
                
                // Update Chart
                initChart(data.segmentationLabels, data.segmentationData);
                
                // Update Table
                const tbody = document.getElementById('top-customers-body');
                tbody.innerHTML = '';
                
                if (data.topCustomers.length === 0) {
                    tbody.innerHTML = `<tr><td colspan="4" class="px-6 py-8 text-center text-slate-500">{{ __('messages.no_customer_data') }}</td></tr>`;
                } else {
                    data.topCustomers.forEach(customer => {
                        const date = new Date(customer.last_order_date);
                        const formattedDate = date.toLocaleDateString('vi-VN', { day: '2-digit', month: '2-digit', year: 'numeric' });
                        
                        const tr = document.createElement('tr');
                        tr.className = 'hover:bg-slate-50 transition-colors';
                        tr.innerHTML = `
                            <td class="px-6 py-4">
                                <div>
                                    <div class="font-medium text-slate-800">${customer.name}</div>
                                    <div class="text-sm text-slate-500">${customer.email}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center text-slate-600">${customer.order_count}</td>
                            <td class="px-6 py-4 text-center text-slate-600">${formattedDate}</td>
                            <td class="px-6 py-4 text-right font-bold text-indigo-600">${new Intl.NumberFormat('vi-VN').format(customer.total_spent)} {{ __('messages.currency_symbol') }}</td>
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
