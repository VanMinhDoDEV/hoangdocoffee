{{-- resources/views/admin/customers/index.blade.php --}}
@extends('layouts.admin')

@section('title', __('messages.customers'))

@section('content')
<div class="w-full min-h-screen" style="background-color: #f8fafc;">
  <main class="mx-auto min-h-[500px]">

    <!-- Search & Filter Bar -->
    <div class="rounded-xl p-6 mb-6 shadow-sm bg-white">
      <div class="flex flex-col gap-6">
        <!-- Top: Search + Buttons -->
        <div class="flex flex-col md:flex-row gap-4 items-stretch md:items-center justify-between">
          <div class="flex-1 w-full md:max-w-md">
            <div class="relative">
              <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 opacity-50 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
              </svg>
              <input type="search" id="search" placeholder="{{ __('messages.search_customer') }}..." class="w-full pl-12 pr-4 py-2 rounded-lg border-2 border-slate-200 focus:border-blue-500 focus:outline-none transition-all">
            </div>
          </div>
          <div class="flex gap-3 flex-wrap">
            <button id="export-btn" class="px-4 py-2 rounded-lg font-medium transition-all hover:scale-105 hover:shadow-md flex items-center gap-2 border border-slate-300 bg-white text-slate-700">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
              </svg>
              <span>{{ __('messages.export_excel') }}</span>
            </button>
            <button id="add-customer-btn" class="px-4 py-2 rounded-lg font-medium transition-all hover:scale-105 hover:shadow-md flex items-center gap-2 text-white bg-blue-500 hover:bg-blue-600">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
              </svg>
              <span>{{ __('messages.add_customer') }}</span>
            </button>
          </div>
        </div>

        <!-- Bottom: Filters -->
        <div class="flex flex-col md:flex-row gap-4 items-stretch md:items-center justify-between border-t pt-4" style="border-color: rgba(0,0,0,0.1);">
          <div class="flex flex-wrap gap-3 flex-1">
            <!-- City -->
            <div class="relative min-w-[160px]">
              <select id="city-filter" class="w-full pl-10 pr-4 py-2 rounded-lg border-2 border-slate-200 focus:border-blue-500 focus:outline-none transition-all appearance-none cursor-pointer bg-white">
                <option value="">{{ __('messages.all_cities') }}</option>
                @foreach($cities as $city)
                  <option value="{{ $city }}">{{ $city }}</option>
                @endforeach
              </select>
              <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 opacity-50 pointer-events-none text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/>
              </svg>
            </div>

            <!-- Orders -->
            <div class="relative min-w-[160px]">
              <select id="order-filter" class="w-full pl-10 pr-4 py-2 rounded-lg border-2 border-slate-200 focus:border-blue-500 focus:outline-none transition-all appearance-none cursor-pointer bg-white">
                <option value="">{{ __('messages.order_count') }}</option>
                <option value="0-300">0 - 300</option>
                <option value="300-500">300 - 500</option>
                <option value="500-700">500 - 700</option>
                <option value="700+">700+</option>
              </select>
              <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 opacity-50 pointer-events-none text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
              </svg>
            </div>

            <!-- Spending -->
            <div class="relative min-w-[160px]">
              <select id="spending-filter" class="w-full pl-10 pr-4 py-2 rounded-lg border-2 border-slate-200 focus:border-blue-500 focus:outline-none transition-all appearance-none cursor-pointer bg-white">
                <option value="">{{ __('messages.total_spending') }}</option>
                <option value="0-3000000">0 đ - 3.000.000 đ</option>
                <option value="3000000-6000000">3.000.000 đ - 6.000.000 đ</option>
                <option value="6000000-9000000">6.000.000 đ - 9.000.000 đ</option>
                <option value="9000000+">Trên 9.000.000 đ</option>
              </select>
              <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 opacity-50 pointer-events-none text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
            </div>

            <button id="clear-filters-btn" class="px-4 py-2 rounded-lg font-medium transition-all hover:scale-105 flex items-center gap-2 opacity-70 hover:opacity-100 text-slate-600">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
              </svg>
              <span>{{ __('messages.clear_filters') }}</span>
            </button>
          </div>

          <div class="flex items-center gap-3">
            <label for="entries" class="text-sm font-medium opacity-75 whitespace-nowrap text-slate-600">{{ __('messages.show') }}:</label>
            <select id="entries" class="px-4 py-2 rounded-lg border-2 border-slate-200 focus:border-blue-500 focus:outline-none transition-all cursor-pointer bg-white">
              <option value="10">10</option>
              <option value="25">25</option>
              <option value="50">50</option>
              <option value="100">100</option>
            </select>
          </div>
        </div>
      </div>
    </div>

    <!-- Table -->
    <div class="rounded-xl overflow-hidden shadow-lg bg-white">
      <div class="overflow-x-auto  min-h-[500px]">
        <table class="w-full">
          <thead class="bg-blue-500 text-white">
            <tr>
              <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider w-12">
                <input type="checkbox" id="select-all" class="w-5 h-5 rounded cursor-pointer" style="accent-color: white;">
              </th>
              <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">{{ __('messages.customer') }}</th>
              <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">ID</th>
              <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">{{ __('messages.city') }}</th>
              <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">{{ __('messages.orders') }}</th>
              <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">{{ __('messages.total_spending') }}</th>
              <th class="px-6 py-4 text-center text-sm font-semibold uppercase tracking-wider w-20">{{ __('messages.actions') }}</th>
            </tr>
          </thead>
          <tbody id="customer-table-body">
            @foreach($uiCustomers as $c)
            <tr class="border-b border-slate-200 fade-in" style="animation-delay: {{ $loop->index * 0.05 }}s;">
              <td class="px-6 py-4">
                <input type="checkbox" class="customer-checkbox w-5 h-5 rounded cursor-pointer" style="accent-color: #3b82f6;">
              </td>
              <td class="px-6 py-4">
                <div class="flex items-center gap-4">
                  <div class="w-12 h-12 rounded-full flex items-center justify-center font-semibold text-white bg-blue-500">
                    {{ $c['initials'] }}
                  </div>
                  <div>
                    <a href="{{ route('admin.customers.show', $c['user_id']) }}" class="font-semibold text-slate-800 hover:text-blue-600 transition-colors block">{{ $c['name'] }}</a>
                    <div class="text-sm opacity-60 text-slate-600">{{ $c['email'] }}</div>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 text-sm font-mono opacity-75 text-slate-600">{{ $c['id'] }}</td>
              <td class="px-6 py-4 text-slate-700">{{ $c['city'] }}</td>
              <td class="px-6 py-4 font-semibold text-slate-800">{{ $c['orders'] }}</td>
              <td class="px-6 py-4 font-bold text-blue-500">{{ number_format($c['spent'], 0, ',', '.') }} đ</td>
              <td class="px-6 py-4 text-center">
                <div class="relative">
                  <button class="action-toggle p-2 rounded-lg hover:bg-slate-100 transition-all" onclick="toggleActionMenu('{{ $c['user_id'] }}')">
                    <svg class="w-5 h-5 text-slate-600" fill="currentColor" viewBox="0 0 16 16">
                      <circle cx="8" cy="2" r="1.5"/>
                      <circle cx="8" cy="8" r="1.5"/>
                      <circle cx="8" cy="14" r="1.5"/>
                    </svg>
                  </button>
                  <div id="menu-{{ $c['user_id'] }}" class="action-menu absolute right-0 top-full mt-2 min-w-[160px] bg-white rounded-lg shadow-xl border border-slate-200 z-50 opacity-0 invisible -translate-y-2 transition-all">
                    <button onclick="viewCustomer('{{ $c['user_id'] }}')" class="action-btn w-full text-left px-4 py-3 hover:bg-slate-50 flex items-center gap-3 text-slate-700">
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                      </svg>
                      <span>{{ __('messages.view_details') }}</span>
                    </button>
                    <button onclick="editCustomer('{{ $c['user_id'] }}')" class="action-btn w-full text-left px-4 py-3 hover:bg-slate-50 flex items-center gap-3 text-slate-700">
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                      </svg>
                      <span>{{ __('messages.edit') }}</span>
                    </button>
                    <button onclick="deleteCustomer('{{ $c['user_id'] }}')" class="action-btn w-full text-left px-4 py-3 hover:bg-red-50 flex items-center gap-3 text-red-500">
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                      </svg>
                      <span>{{ __('messages.delete') }}</span>
                    </button>
                  </div>
                </div>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div class="mt-6 px-6 pb-6">
        @include('admin.partials.pagination', ['paginator' => $customers, 'entityName' => __('messages.customers')])
      </div>
    </div>
  </main>

  <!-- Modal Thêm Khách Hàng -->
  <div id="add-customer-modal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black bg-opacity-50 backdrop-blur-sm" onclick="closeAddCustomerModal()"></div>
    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-full max-w-2xl max-h-[90%] overflow-y-auto rounded-2xl shadow-2xl bg-white">
      <form id="add-customer-form" class="p-8" method="POST" action="{{ route('admin.customers.store') }}">
        @csrf
        <input type="hidden" name="role" value="customer">
        <div class="flex items-center justify-between mb-6">
          <div>
            <h2 class="text-3xl font-bold mb-1 text-slate-800">{{ __('messages.add_new_customer') }}</h2>
            <p class="text-sm opacity-75 text-slate-600">{{ __('messages.fill_customer_info') }}</p>
          </div>
          <button type="button" onclick="closeAddCustomerModal()" class="p-2 rounded-lg hover:bg-slate-100 transition-all">
            <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
          </button>
        </div>

        <div class="space-y-5">
          <div>
            <label class="block text-sm font-semibold mb-2 text-slate-700">{{ __('messages.full_name') }} <span class="text-red-500">*</span></label>
            <input type="text" name="name" required placeholder="{{ __('messages.enter_full_name') }}" class="w-full px-4 py-2 rounded-lg border-2 border-slate-200 focus:border-blue-500 focus:outline-none transition-all">
          </div>
          <div>
            <label class="block text-sm font-semibold mb-2 text-slate-700">{{ __('messages.email') }} <span class="text-red-500">*</span></label>
            <input type="email" name="email" required placeholder="example@email.com" class="w-full px-4 py-2 rounded-lg border-2 border-slate-200 focus:border-blue-500 focus:outline-none transition-all">
          </div>
          <div>
            <label class="block text-sm font-semibold mb-2 text-slate-700">{{ __('messages.city') }} <span class="text-red-500">*</span></label>
            <select name="city" required class="w-full px-4 py-2 rounded-lg border-2 border-slate-200 focus:border-blue-500 focus:outline-none transition-all cursor-pointer bg-white">
              <option value="">{{ __('messages.select_city') }}</option>
              @foreach($cities as $city)
                <option value="{{ $city }}">{{ $city }}</option>
              @endforeach
            </select>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
              <label class="block text-sm font-semibold mb-2 text-slate-700">{{ __('messages.order_count') }} <span class="text-red-500">*</span></label>
              <input type="number" name="orders" min="0" required placeholder="0" class="w-full px-4 py-2 rounded-lg border-2 border-slate-200 focus:border-blue-500 focus:outline-none transition-all">
            </div>
            <div>
              <label class="block text-sm font-semibold mb-2 text-slate-700">{{ __('messages.total_spending') }} (đ) <span class="text-red-500">*</span></label>
              <input type="number" name="spent" min="0" step="1000" required placeholder="0" class="w-full px-4 py-2 rounded-lg border-2 border-slate-200 focus:border-blue-500 focus:outline-none transition-all">
            </div>
          </div>
        </div>

        <div class="flex gap-3 mt-8 pt-6 border-t border-slate-200">
          <button type="button" onclick="closeAddCustomerModal()" class="flex-1 px-6 py-2 rounded-lg font-semibold transition-all hover:scale-105 border border-slate-300 text-slate-700">{{ __('messages.cancel') }}</button>
          <button type="submit" class="flex-1 px-6 py-2 rounded-lg font-semibold transition-all hover:scale-105 text-white bg-blue-500 hover:bg-blue-600">{{ __('messages.add_customer') }}</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- CSS hiệu ứng -->
<style>
  .fade-in { animation: fadeIn 0.3s ease-out; }
  @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
  .action-menu { opacity: 0; visibility: hidden; transform: translateY(-10px); transition: all 0.2s ease; }
  .action-menu.show { opacity: 1; visibility: visible; transform: translateY(0); }
  .action-btn { transition: all 0.2s ease; }
  .action-btn:hover { transform: translateX(4px); }
</style>

<script>
  const customers = @json($uiCustomers);
  const baseUrl = "{{ url('admin/customers') }}";

  let currentOpenMenu = null;

  function toggleActionMenu(id) {
    const menu = document.getElementById('menu-' + id);
    if (!menu) {
      console.warn('Menu not found for ID:', id);
      return;
    }
    
    // Nếu menu này đang mở thì đóng lại, nếu chưa mở thì mở ra
    // Đồng thời đóng menu khác đang mở (nếu có)
    if (currentOpenMenu && currentOpenMenu !== menu) {
      currentOpenMenu.classList.remove('show');
    }
    
    menu.classList.toggle('show');
    currentOpenMenu = menu.classList.contains('show') ? menu : null;
  }

  function closeAllMenus() {
    document.querySelectorAll('.action-menu').forEach(m => m.classList.remove('show'));
    currentOpenMenu = null;
  }

  // Đóng menu khi click ra ngoài
  document.addEventListener('click', function(e) {
    if (!e.target.closest('.action-toggle') && !e.target.closest('.action-menu')) {
      closeAllMenus();
    }
  });

  function showNotification(message, type = 'info') {
    const n = document.createElement('div');
    n.className = 'fixed top-4 right-4 px-6 py-4 rounded-lg shadow-lg z-50 fade-in border-l-4';
    n.style.backgroundColor = '#ffffff';
    n.style.color = '#1e293b';
    n.style.borderLeftColor = type === 'error' ? '#ef4444' : '#3b82f6';
    n.textContent = message;
    document.body.appendChild(n);
    setTimeout(() => { n.style.opacity = '0'; setTimeout(() => n.remove(), 300); }, 3000);
  }

  function viewCustomer(id) { 
      window.location.href = baseUrl + '/' + id;
  }
  
  function editCustomer(id) { 
      // Tạm thời hiện thông báo, sau này có thể mở modal edit
      showNotification("{{ __('messages.feature_updating') }}"); 
      closeAllMenus(); 
  }
  
  function deleteCustomer(id) { 
      if(confirm("{{ __('messages.confirm_delete_customer') }}")) {
          const f = document.createElement('form');
          f.method = 'POST';
          f.action = baseUrl + '/' + id;
          f.innerHTML = '<input type="hidden" name="_token" value="{{ csrf_token() }}"><input type="hidden" name="_method" value="DELETE">';
          document.body.appendChild(f);
          f.submit();
      }
      closeAllMenus(); 
  }

  // Checkbox Select All
  document.getElementById('select-all')?.addEventListener('change', function() {
    document.querySelectorAll('.customer-checkbox').forEach(cb => {
      cb.checked = this.checked;
      cb.closest('tr').classList.toggle('bg-blue-50', this.checked);
    });
  });

  // Filters
  const filters = { search: '', city: '', orderRange: '', spendingRange: '' };

  function applyFilters() {
    const q = filters.search.toLowerCase();
    document.querySelectorAll('#customer-table-body tr').forEach((row, i) => {
      const c = customers[i];
      if (!c) return;
      const matchSearch = !q || c.name.toLowerCase().includes(q) || c.email.toLowerCase().includes(q) || c.id.toLowerCase().includes(q);
      const matchCity = !filters.city || c.city === filters.city;
      let matchOrder = true;
      if (filters.orderRange) {
        const o = c.orders;
        if (filters.orderRange === '0-300') matchOrder = o <= 300;
        else if (filters.orderRange === '300-500') matchOrder = o > 300 && o <= 500;
        else if (filters.orderRange === '500-700') matchOrder = o > 500 && o <= 700;
        else if (filters.orderRange === '700+') matchOrder = o > 700;
      }
      let matchSpent = true;
      if (filters.spendingRange) {
        const s = c.spent;
        if (filters.spendingRange === '0-3000000') matchSpent = s <= 3000000;
        else if (filters.spendingRange === '3000000-6000000') matchSpent = s > 3000000 && s <= 6000000;
        else if (filters.spendingRange === '6000000-9000000') matchSpent = s > 6000000 && s <= 9000000;
        else if (filters.spendingRange === '9000000+') matchSpent = s > 9000000;
      }

      if (matchSearch && matchCity && matchOrder && matchSpent) {
        row.style.display = '';
      } else {
        row.style.display = 'none';
      }
    });
  }

  document.getElementById('search')?.addEventListener('input', e => { filters.search = e.target.value; applyFilters(); });
  document.getElementById('city-filter')?.addEventListener('change', e => { filters.city = e.target.value; applyFilters(); });
  document.getElementById('order-filter')?.addEventListener('change', e => { filters.orderRange = e.target.value; applyFilters(); });
  document.getElementById('spending-filter')?.addEventListener('change', e => { filters.spendingRange = e.target.value; applyFilters(); });

  document.getElementById('clear-filters-btn')?.addEventListener('click', () => {
    filters.search = '';
    filters.city = '';
    filters.orderRange = '';
    filters.spendingRange = '';
    document.getElementById('search').value = '';
    document.getElementById('city-filter').value = '';
    document.getElementById('order-filter').value = '';
    document.getElementById('spending-filter').value = '';
    applyFilters();
  });

  document.getElementById('add-customer-btn')?.addEventListener('click', () => {
    document.getElementById('add-customer-modal').classList.remove('hidden');
  });

  function closeAddCustomerModal() {
    document.getElementById('add-customer-modal').classList.add('hidden');
  }
</script>
@endsection
