<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Quản lý Mix & Match Promotion</title>
  <link rel="shortcut icon" type="image/x-icon" href="{{ !empty($storeSettings['favicon']) ? asset($storeSettings['favicon']) : asset('assets/images/favicon.png') }}">
  @vite(['resources/css/app.css','resources/js/app.js'])
  
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <style>
    body { font-family: 'Inter', sans-serif; }
    /* Custom Scrollbar for refined look */
    ::-webkit-scrollbar { width: 6px; height: 6px; }
    ::-webkit-scrollbar-track { background: #f1f5f9; }
    ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
    ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    
    .search-results-dropdown {
        position: absolute;
        z-index: 60;
        width: 100%;
        max-height: 240px;
        overflow-y: auto;
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 0.75rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        margin-top: 0.5rem;
    }
  </style>
</head>
<body class="bg-slate-50 text-slate-600 antialiased">
 <div class="w-full min-h-screen flex">
  @include('admin.partials.sidebar')

  <main class="flex-1 overflow-auto md:ml-64 transition-all duration-300">
    @include('admin.partials.topbar', ['title' => 'Chiến dịch Mix & Match'])

    <div class="flex-1 overflow-auto p-6 lg:p-8">
      <div class="max-w-7xl mx-auto">
        
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Danh sách khuyến mãi</h1>
                <p class="text-slate-500 text-sm mt-1">Quản lý các chương trình mua kèm, combo giảm giá.</p>
            </div>
            <button onclick="openModal()" class="inline-flex items-center justify-center px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white text-sm font-medium rounded-xl transition-all shadow-lg shadow-slate-200 focus:ring-4 focus:ring-slate-100">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Tạo chiến dịch mới
            </button>
        </div>

        @if(session('status'))
          <div class="mb-6 p-4 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-100 flex items-center shadow-sm">
            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('status') }}
          </div>
        @endif

        @if($errors->any())
          <div class="mb-6 p-4 rounded-xl bg-rose-50 text-rose-700 border border-rose-100 shadow-sm">
            <div class="flex items-center mb-2 font-medium">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Có lỗi xảy ra:
            </div>
            <ul class="list-disc list-inside text-sm space-y-1 ml-1">
              @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
          <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
              <thead>
                <tr class="bg-slate-50/80 border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500 font-semibold">
                  <th class="py-4 px-6 min-w-[200px]">Tên chương trình</th>
                  <th class="py-4 px-6">Phạm vi</th>
                  <th class="py-4 px-6">Điều kiện</th>
                  <th class="py-4 px-6">Ưu đãi</th>
                  <th class="py-4 px-6 text-center">Mã Code</th>
                  <th class="py-4 px-6">Thời gian</th>
                  <th class="py-4 px-6 text-center">Trạng thái</th>
                  <th class="py-4 px-6 text-right">Hành động</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100">
                @forelse($rules as $r)
                  <tr class="hover:bg-slate-50/80 transition-colors group">
                    <td class="py-4 px-6">
                        <div class="font-semibold text-slate-900">{{ $r->name }}</div>
                        @if($r->free_shipping)
                            <div class="mt-1 inline-flex items-center text-[10px] uppercase font-bold tracking-wide text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-full border border-indigo-100">
                                + Freeship
                            </div>
                        @endif
                    </td>
                    <td class="py-4 px-6">
                      @php
                          $ids = json_decode($r->condition_json, true);
                          $count = is_array($ids) ? count($ids) : 0;
                      @endphp
                      <div class="flex items-center">
                          <span class="flex items-center justify-center w-8 h-8 rounded-full bg-blue-50 text-blue-600 text-xs font-bold mr-2 ring-1 ring-blue-100">
                              {{ $count }}
                          </span>
                          <span class="text-sm text-slate-600">Sản phẩm</span>
                      </div>
                    </td>
                    <td class="py-4 px-6 text-sm">
                        Mua từ <strong class="text-slate-900">{{ $r->min_total_qty }}</strong> sp
                    </td>
                    <td class="py-4 px-6">
                        @if($r->discount_type === 'percent')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-rose-50 text-rose-600 text-sm font-bold border border-rose-100">
                                -{{ $r->discount_value }}%
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-emerald-50 text-emerald-600 text-sm font-bold border border-emerald-100">
                                -{{ number_format($r->discount_value, 0, ',', '.') }}đ
                            </span>
                        @endif
                    </td>
                    <td class="py-4 px-6 text-center">
                      @if($r->requires_code)
                          <span class="inline-block px-3 py-1 bg-slate-100 text-slate-700 rounded-md text-xs font-mono border border-slate-200 select-all cursor-pointer hover:bg-white hover:border-slate-300 transition-colors" title="Click to copy">
                              {{ $r->promo_code }}
                          </span>
                      @else
                          <span class="text-slate-400 text-xs italic">Tự động</span>
                      @endif
                    </td>
                    <td class="py-4 px-6">
                        <div class="flex flex-col text-xs text-slate-500 gap-1">
                            <span class="flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                {{ $r->starts_at ? \Carbon\Carbon::parse($r->starts_at)->format('d/m/Y H:i') : '--' }}
                            </span>
                            <span class="flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-rose-400"></span>
                                {{ $r->ends_at ? \Carbon\Carbon::parse($r->ends_at)->format('d/m/Y H:i') : '--' }}
                            </span>
                        </div>
                    </td>
                    <td class="py-4 px-6 text-center">
                      @if($r->is_active)
                          <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100/60 text-emerald-700 ring-1 ring-inset ring-emerald-600/20">
                              <span class="w-1.5 h-1.5 rounded-full bg-emerald-600 mr-1.5"></span>
                              Hoạt động
                          </span>
                      @else
                          <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-600 ring-1 ring-inset ring-slate-500/10">
                              Tạm dừng
                          </span>
                      @endif
                    </td>
                    <td class="py-4 px-6 text-right">
                        <div class="flex items-center justify-end gap-2 opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity">
                            <button onclick='openModal(@json($r))' class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Chỉnh sửa">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </button>
                            <form action="{{ route('admin.promotion_rules.destroy', $r->id) }}" method="post" onsubmit="return confirm('Bạn có chắc chắn muốn xóa chương trình này?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Xóa">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="8" class="py-16 text-center">
                        <div class="flex flex-col items-center justify-center text-slate-400">
                            <svg class="w-16 h-16 mb-4 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            <span class="text-base font-medium text-slate-500">Chưa có chương trình khuyến mãi nào</span>
                            <p class="text-sm mt-1">Bắt đầu bằng cách tạo một chiến dịch mới.</p>
                        </div>
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
          <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
              {{ $rules->links() }}
          </div>
        </div>
      </div>
    </div>
  </main>
 </div>

 <div id="modal" class="fixed inset-0 z-50 hidden transition-opacity duration-300" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-[2px] transition-opacity" onclick="closeModal()"></div>
    
    <div class="absolute inset-y-0 right-0 w-full max-w-2xl bg-white shadow-2xl flex flex-col transform transition-transform duration-300 translate-x-full border-l border-slate-200" id="modal-panel">
        <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center bg-white sticky top-0 z-10">
            <div>
                <h3 class="text-xl font-bold text-slate-900" id="modal-title">Tạo khuyến mãi mới</h3>
                <p class="text-sm text-slate-500">Thiết lập điều kiện và quy tắc giảm giá.</p>
            </div>
            <button onclick="closeModal()" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-full transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        
        <div class="flex-1 overflow-y-auto p-6 lg:p-8 bg-white">
            <form id="modal-form" action="{{ route('admin.promotion_rules.store') }}" method="post" class="space-y-8">
                @csrf
                <input type="hidden" name="_method" id="form-method" value="POST">
                
                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Tên chương trình <span class="text-rose-500">*</span></label>
                        <input name="name" id="name" type="text" 
                               class="w-full border-slate-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-slate-900 focus:border-slate-900 placeholder:text-slate-400 transition-all shadow-sm" 
                               required placeholder="VD: Combo Mua 2 Giảm 10%">
                    </div>

                    <div class="grid grid-cols-2 gap-5">
                         <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">SL tối thiểu <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <input name="min_total_qty" id="min_total_qty" type="number" min="1" 
                                    class="w-full border-slate-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-slate-900 focus:border-slate-900 shadow-sm" 
                                    required value="2">
                                <span class="absolute right-4 top-2.5 text-slate-400 text-sm">sản phẩm</span>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Loại giảm</label>
                                <select name="discount_type" id="discount_type" class="w-full border-slate-300 rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-slate-900 shadow-sm bg-slate-50">
                                    <option value="percent">Phần trăm %</option>
                                    <option value="amount">Tiền mặt (đ)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Giá trị <span class="text-rose-500">*</span></label>
                                <input name="discount_value" id="discount_value" type="number" step="0.01" min="0" 
                                    class="w-full border-slate-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-slate-900 focus:border-slate-900 shadow-sm font-medium" 
                                    required>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="border-slate-100">

                <div>
                    <h4 class="text-sm uppercase tracking-wide text-slate-500 font-bold mb-4">Cấu hình nâng cao</h4>
                    <div class="bg-slate-50 rounded-xl p-5 border border-slate-100 space-y-4">
                        
                        <div>
                             <label class="flex items-center cursor-pointer mb-2">
                                <input name="requires_code" id="requires_code" type="checkbox" value="1" class="w-5 h-5 rounded text-slate-900 border-slate-300 focus:ring-slate-900 transition-all" onchange="toggleCodeInput()">
                                <span class="ml-3 text-sm font-medium text-slate-700">Yêu cầu nhập mã Code</span>
                            </label>
                            
                            <div id="code-input-container" class="hidden pl-8 mt-2 transition-all">
                                <input name="promo_code" id="promo_code" type="text" 
                                    class="w-full border-slate-300 rounded-lg px-4 py-2 font-mono uppercase text-sm tracking-wider focus:ring-2 focus:ring-slate-900 placeholder:normal-case placeholder:font-sans" 
                                    placeholder="Nhập mã code (VD: SALE2025)">
                            </div>
                        </div>

                        <label class="flex items-center cursor-pointer">
                            <input name="free_shipping" id="free_shipping" type="checkbox" value="1" class="w-5 h-5 rounded text-slate-900 border-slate-300 focus:ring-slate-900 transition-all">
                            <span class="ml-3 text-sm font-medium text-slate-700">Kích hoạt Freeship</span>
                            <span class="ml-auto text-xs text-slate-400 bg-white px-2 py-1 rounded border">Tùy chọn</span>
                        </label>
                    </div>
                </div>

                <hr class="border-slate-100">

                <div>
                    <h4 class="text-sm uppercase tracking-wide text-slate-500 font-bold mb-4">Sản phẩm áp dụng</h4>
                    <div class="relative mb-4">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                             <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <input type="text" id="product-search" 
                               class="w-full pl-10 border-slate-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-slate-900 focus:border-slate-900 shadow-sm transition-shadow" 
                               placeholder="Tìm tên sản phẩm hoặc SKU để thêm...">
                        <div id="search-results" class="search-results-dropdown hidden"></div>
                    </div>

                    <input type="hidden" name="condition_json" id="condition_json">
                    
                    <div id="selected-products" class="grid grid-cols-1 gap-3 max-h-[300px] overflow-y-auto custom-scrollbar p-1">
                        <div id="empty-products-msg" class="text-center py-8 bg-slate-50 rounded-xl border-2 border-dashed border-slate-200">
                            <svg class="w-10 h-10 text-slate-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            <span class="text-slate-400 text-sm">Chưa có sản phẩm nào được chọn</span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-5 pt-2">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Bắt đầu</label>
                        <input name="starts_at" id="starts_at" type="datetime-local" class="w-full border-slate-300 rounded-xl px-3 py-2.5 text-sm focus:ring-slate-900">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Kết thúc</label>
                        <input name="ends_at" id="ends_at" type="datetime-local" class="w-full border-slate-300 rounded-xl px-3 py-2.5 text-sm focus:ring-slate-900">
                    </div>
                </div>

                <div class="bg-emerald-50/50 rounded-xl p-4 border border-emerald-100 flex items-center">
                    <input name="is_active" id="is_active" type="checkbox" value="1" checked class="w-5 h-5 rounded text-emerald-600 border-emerald-300 focus:ring-emerald-600">
                    <div class="ml-3">
                        <label for="is_active" class="text-sm font-medium text-emerald-900 block">Kích hoạt ngay</label>
                        <p class="text-xs text-emerald-700">Chương trình sẽ có hiệu lực ngay sau khi lưu.</p>
                    </div>
                </div>
            </form>
        </div>
        
        <div class="p-6 border-t border-slate-100 bg-slate-50 flex justify-end gap-3 sticky bottom-0 z-10">
            <button onclick="closeModal()" class="px-5 py-2.5 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 text-slate-700 font-medium transition-colors shadow-sm">Hủy bỏ</button>
            <button onclick="document.getElementById('modal-form').submit()" class="px-6 py-2.5 bg-slate-900 text-white rounded-xl hover:bg-slate-800 font-medium transition-all shadow-md hover:shadow-lg">Lưu chiến dịch</button>
        </div>
    </div>
 </div>

 <script>
  window.toggleSubmenu = function(id){
    const submenu = document.getElementById(id+'-submenu');
    const arrow = document.getElementById(id+'-arrow');
    if(!submenu || !arrow) return;
    submenu.classList.toggle('hidden');
    arrow.classList.toggle('rotate-180');
  };

  // State
  let selectedProducts = [];
  
  function toggleCodeInput() {
      const isChecked = document.getElementById('requires_code').checked;
      const container = document.getElementById('code-input-container');
      if (isChecked) {
          container.classList.remove('hidden');
      } else {
          container.classList.add('hidden');
      }
  }

  function openModal(rule = null) {
      const modal = document.getElementById('modal');
      const panel = document.getElementById('modal-panel');
      const form = document.getElementById('modal-form');
      const title = document.getElementById('modal-title');
      const methodInput = document.getElementById('form-method');

      modal.classList.remove('hidden');
      // Small delay to allow display:block to apply before transition
      setTimeout(() => panel.classList.remove('translate-x-full'), 10);

      // Reset form
      form.reset();
      selectedProducts = [];
      renderSelectedProducts();
      document.getElementById('empty-products-msg').classList.remove('hidden');

      if (rule) {
          // Edit Mode
          title.textContent = 'Cập nhật khuyến mãi';
          form.action = `/admin/promotion-rules/${rule.id}`;
          methodInput.value = 'PUT';
          
          document.getElementById('name').value = rule.name;
          document.getElementById('min_total_qty').value = rule.min_total_qty;
          document.getElementById('discount_type').value = rule.discount_type;
          document.getElementById('discount_value').value = rule.discount_value;
          document.getElementById('requires_code').checked = rule.requires_code;
          document.getElementById('promo_code').value = rule.promo_code || '';
          document.getElementById('is_active').checked = rule.is_active;
          document.getElementById('free_shipping').checked = rule.free_shipping || false;
          
          if(rule.starts_at) document.getElementById('starts_at').value = rule.starts_at.slice(0, 16);
          if(rule.ends_at) document.getElementById('ends_at').value = rule.ends_at.slice(0, 16);

          toggleCodeInput();

          // Load products
          if (rule.condition_json) {
              try {
                  const ids = JSON.parse(rule.condition_json);
                  if (Array.isArray(ids) && ids.length > 0) {
                      loadProductsByIds(ids);
                  }
              } catch (e) { console.error(e); }
          }

      } else {
          // Create Mode
          title.textContent = 'Tạo khuyến mãi mới';
          form.action = "{{ route('admin.promotion_rules.store') }}";
          methodInput.value = 'POST';
          toggleCodeInput();
      }
  }

  function closeModal() {
      const modal = document.getElementById('modal');
      const panel = document.getElementById('modal-panel');
      panel.classList.add('translate-x-full');
      setTimeout(() => modal.classList.add('hidden'), 300);
  }

  // Product Search Logic
  const searchInput = document.getElementById('product-search');
  const searchResults = document.getElementById('search-results');
  let searchTimeout;

  searchInput.addEventListener('input', (e) => {
      clearTimeout(searchTimeout);
      const q = e.target.value.trim();
      if (q.length < 2) {
          searchResults.classList.add('hidden');
          return;
      }
      
      searchTimeout = setTimeout(async () => {
          try {
              const res = await fetch(`{{ route('admin.combos.products.search') }}?q=${encodeURIComponent(q)}`);
              const data = await res.json();
              renderSearchResults(data.items || []);
          } catch (e) {
              console.error(e);
          }
      }, 300);
  });

  function renderSearchResults(items) {
      searchResults.innerHTML = '';
      if (items.length === 0) {
          searchResults.classList.add('hidden');
          return;
      }
      
      items.forEach(item => {
          const div = document.createElement('div');
          div.className = 'px-4 py-3 hover:bg-slate-50 cursor-pointer border-b border-slate-100 last:border-0 flex justify-between items-center transition-colors';
          
          const info = document.createElement('div');
          info.innerHTML = `<div class="text-sm font-medium text-slate-800">${item.name}</div><div class="text-xs text-slate-500 font-mono">SKU: ${item.sku}</div>`;
          
          const action = document.createElement('span');
          action.className = 'text-xs font-medium text-indigo-600 bg-indigo-50 px-2 py-1 rounded';
          action.innerText = '+ Thêm';

          div.appendChild(info);
          div.appendChild(action);
          
          div.onclick = () => addProduct(item);
          searchResults.appendChild(div);
      });
      
      searchResults.classList.remove('hidden');
  }

  function addProduct(item) {
      if (!selectedProducts.find(p => p.id === item.id)) {
          selectedProducts.push(item);
          renderSelectedProducts();
      }
      searchInput.value = '';
      searchResults.classList.add('hidden');
  }

  function removeProduct(id) {
      selectedProducts = selectedProducts.filter(p => p.id !== id);
      renderSelectedProducts();
  }

  function renderSelectedProducts() {
      const container = document.getElementById('selected-products');
      const emptyMsg = document.getElementById('empty-products-msg');
      const input = document.getElementById('condition_json');
      
      // Clear current list except empty msg
      Array.from(container.children).forEach(c => {
          if (c.id !== 'empty-products-msg') c.remove();
      });

      if (selectedProducts.length === 0) {
          emptyMsg.classList.remove('hidden');
          input.value = '';
      } else {
          emptyMsg.classList.add('hidden');
          input.value = JSON.stringify(selectedProducts.map(p => p.id));
          
          selectedProducts.forEach(p => {
              const div = document.createElement('div');
              div.className = 'flex justify-between items-center bg-white border border-slate-200 p-3 rounded-xl shadow-sm hover:shadow-md transition-shadow group';
              
              let html = '<div class="flex items-center gap-3">';
              if(p.image) {
                  html += `<img src="${p.image}" class="w-10 h-10 rounded-lg object-cover border border-slate-100">`;
              } else {
                  html += `<div class="w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center text-slate-400"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg></div>`;
              }
              
              const priceHtml = p.price ? `<span class="text-emerald-600 ml-1 font-semibold text-xs bg-emerald-50 px-1.5 py-0.5 rounded">${new Intl.NumberFormat('vi-VN').format(p.price)}đ</span>` : '';
              html += `<div><div class="text-sm font-semibold text-slate-800 line-clamp-1">${p.name || 'Sản phẩm #' + p.id}</div><div class="text-xs text-slate-500 mt-0.5">SKU: <span class="font-mono">${p.sku || 'N/A'}</span> ${priceHtml}</div></div></div>`;
              
              div.innerHTML = html + `
                  <button type="button" onclick="removeProduct(${p.id})" class="text-slate-400 hover:text-rose-500 hover:bg-rose-50 p-1.5 rounded-lg transition-colors">
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                  </button>
              `;
              container.appendChild(div);
          });
      }
  }

  async function loadProductsByIds(ids) {
      if (!Array.isArray(ids) || ids.length === 0) return;
      
      try {
          const res = await fetch(`{{ route('admin.combos.products.search') }}?ids=${ids.join(',')}`);
          const data = await res.json();
          if (data.items && Array.isArray(data.items)) {
              selectedProducts = data.items;
              renderSelectedProducts();
          }
      } catch (e) {
          console.error("Failed to load products:", e);
          // Fallback just in case
          selectedProducts = ids.map(id => ({ id: id, name: 'Sản phẩm #' + id, sku: 'ID: ' + id }));
          renderSelectedProducts();
      }
  }
 </script>
</body>
</html>