<!doctype html>
<html lang="vi" class="h-full bg-gray-50">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Cấu hình Giá theo số lượng</title>
  <link rel="shortcut icon" type="image/x-icon" href="{{ !empty($storeSettings['favicon']) ? asset($storeSettings['favicon']) : asset('assets/images/favicon.png') }}">
  <link rel="icon" type="image/png" href="{{ !empty($storeSettings['favicon']) ? asset($storeSettings['favicon']) : asset('assets/images/favicon.png') }}">
  @vite(['resources/css/app.css','resources/js/app.js'])
  <style>
      [x-cloak] { display: none !important; }
      /* Custom Scrollbar for sleek look */
      .custom-scrollbar::-webkit-scrollbar { width: 6px; }
      .custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; }
      .custom-scrollbar::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 4px; }
      .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #9ca3af; }
  </style>
</head>
<body class="h-full antialiased text-gray-900">
 <div class="flex h-full w-full">
  @include('admin.partials.sidebar')
  
  <main class="flex-1 overflow-auto md:ml-64 transition-all duration-300">
    @include('admin.partials.topbar', ['title' => 'Giá sỉ / Tier Pricing'])
    
    <div class="flex-1 overflow-auto p-4 sm:p-8 custom-scrollbar">
      <div class="max-w-7xl mx-auto">
        
        <div class="sm:flex sm:items-center sm:justify-between mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Giá theo số lượng</h1>
                <p class="mt-2 text-sm text-gray-600">Thiết lập giảm giá tự động khi khách hàng mua số lượng lớn.</p>
            </div>
            <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                <button type="button" onclick="openCreateModal()" 
                    class="block rounded-lg bg-indigo-600 px-4 py-2.5 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition-all">
                    <span class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Thêm bậc giá
                    </span>
                </button>
            </div>
        </div>

        @if(session('status'))
            <div class="mb-6 rounded-md bg-green-50 p-4 border border-green-200">
                <div class="flex">
                    <div class="flex-shrink-0"><svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg></div>
                    <div class="ml-3"><p class="text-sm font-medium text-green-800">{{ session('status') }}</p></div>
                </div>
            </div>
        @endif
        @if($errors->any())
            <div class="mb-6 rounded-md bg-red-50 p-4 border border-red-200">
                <div class="flex">
                    <div class="flex-shrink-0"><svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" /></svg></div>
                    <div class="ml-3">
                        <ul class="list-disc list-inside text-sm text-red-700">
                            @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm ring-1 ring-gray-900/5 overflow-hidden">
            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 sm:pl-6">Sản phẩm</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Giá gốc</th>
                    <th scope="col" class="px-3 py-3.5 text-center text-xs font-semibold uppercase tracking-wide text-gray-500">Min Qty</th>
                    <th scope="col" class="px-3 py-3.5 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">Giá ưu đãi</th>
                    <th scope="col" class="px-3 py-3.5 text-center text-xs font-semibold uppercase tracking-wide text-gray-500">Ưu đãi</th>
                    <th scope="col" class="px-3 py-3.5 text-center text-xs font-semibold uppercase tracking-wide text-gray-500">Trạng thái</th>
                    <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6"><span class="sr-only">Actions</span></th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                  @forelse($tiers as $t)
                    @php
                        $v = $t->variant;
                        $p = $v ? $v->product : ($t->product ?? null);
                        $img = $v && $v->images && $v->images->first()
                            ? $v->images->first()->url
                            : ($p && $p->images && $p->images->where('is_primary', true)->first()
                                ? $p->images->where('is_primary', true)->first()->url
                                : ($p && $p->images && $p->images->first() ? $p->images->first()->url : null));
                    @endphp
                    <tr class="hover:bg-gray-50 transition-colors group">
                      <td class="whitespace-nowrap py-4 pl-4 pr-3 sm:pl-6">
                        <div class="flex items-center">
                            <div class="h-10 w-10 flex-shrink-0">
                                @if($img)
                                    <img class="h-10 w-10 rounded-lg object-cover border border-gray-200" src="{{ Str::startsWith($img, ['http://', 'https://']) ? $img : asset($img) }}" alt="">
                                @else
                                    <div class="h-10 w-10 rounded-lg bg-gray-100 flex items-center justify-center border border-gray-200">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2z"/></svg>
                                    </div>
                                @endif
                            </div>
                            <div class="ml-4">
                                <div class="font-medium text-gray-900">{{ $p ? $p->name : 'Sản phẩm không tồn tại' }}</div>
                                <div class="text-xs text-gray-500 mt-0.5">
                                    @if($v && $v->options)
                                        <span class="inline-flex items-center rounded-md bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10">
                                            {{ $v->options->map(fn($o)=>optional($o->attributeValue)->value)->join(' / ') }}
                                        </span>
                                    @endif
                                    <span class="ml-1">SKU: {{ $v ? $v->sku : ($p ? $p->product_sku : 'N/A') }}</span>
                                </div>
                            </div>
                        </div>
                      </td>
                      <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                        @if($v)
                            {{ number_format($v->price, 0, ',', '.') }}đ
                        @elseif($p)
                            {{ number_format($p->price, 0, ',', '.') }}đ
                        @else
                            -
                        @endif
                      </td>
                      <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-900 font-semibold text-center">
                          <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-indigo-50 text-indigo-700 text-xs font-bold border border-indigo-100">
                              {{ $t->min_qty }}
                          </span>
                      </td>
                      <td class="whitespace-nowrap px-3 py-4 text-sm text-right font-bold text-gray-900">
                        {{ number_format($t->price, 0, ',', '.') }}đ
                      </td>
                      <td class="whitespace-nowrap px-3 py-4 text-sm text-center">
                        @if($t->free_shipping ?? false)
                          <span class="inline-flex items-center rounded-full bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10">Freeship</span>
                        @else
                          <span class="text-gray-300">-</span>
                        @endif
                      </td>
                      <td class="whitespace-nowrap px-3 py-4 text-sm text-center">
                        @if($t->is_active)
                            <span class="inline-flex items-center rounded-full bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">Active</span>
                        @else
                            <span class="inline-flex items-center rounded-full bg-gray-100 px-2 py-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10">Inactive</span>
                        @endif
                      </td>
                      <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                        <div class="flex justify-end items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            <button type="button"
                                    class="text-indigo-600 hover:text-indigo-900 bg-white border border-gray-200 rounded p-1.5 shadow-sm hover:border-indigo-300"
                                    data-id="{{ $t->id }}"
                                    data-variant-id="{{ $t->product_variant_id }}"
                                    data-variant-name="{{ $p ? $p->name : '' }} {{ $v && $v->options ? '('.$v->options->map(fn($o)=>optional($o->attributeValue)->value)->join(' / ').')' : '' }}"
                                    data-min="{{ $t->min_qty }}"
                                    data-price="{{ $t->price }}"
                                    data-active="{{ $t->is_active ? 1 : 0 }}"
                                    data-freeship="{{ $t->free_shipping ? 1 : 0 }}"
                                    onclick="openEditModal(this)">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                            </button>
                            <form action="{{ route('admin.volume_pricing.destroy', $t->id) }}" method="post" class="inline-block" onsubmit="return confirm('Bạn chắc chắn muốn xóa bậc giá này?');">
                              @csrf @method('DELETE')
                              <button type="submit" class="text-red-600 hover:text-red-900 bg-white border border-gray-200 rounded p-1.5 shadow-sm hover:border-red-300">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                              </button>
                            </form>
                        </div>
                      </td>
                    </tr>
                  @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                            <h3 class="mt-2 text-sm font-semibold text-gray-900">Chưa có dữ liệu</h3>
                            <p class="mt-1 text-sm text-gray-500">Bắt đầu bằng cách thêm một bậc giá mới cho sản phẩm.</p>
                        </td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
        </div>
        <div class="mt-5 border-t border-gray-200 pt-5">
            {{ $tiers->links() }}
        </div>
      </div>
    </div>
  </main>
 </div>

 <div id="modal" class="relative z-50 hidden" aria-labelledby="slide-over-title" role="dialog" aria-modal="true">
   <div class="fixed inset-0 bg-gray-500/75 backdrop-blur-sm transition-opacity opacity-0" id="modal-backdrop"></div>
   <div class="fixed inset-0 overflow-hidden">
     <div class="absolute inset-0 overflow-hidden">
       <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
         <div id="modal-panel" class="pointer-events-auto w-screen max-w-md transform transition duration-500 ease-in-out translate-x-full sm:duration-700">
           <div class="flex h-full flex-col overflow-y-scroll bg-white shadow-2xl">
             <div class="px-4 py-6 sm:px-6 bg-gray-50 border-b border-gray-200">
               <div class="flex items-start justify-between">
                 <h2 class="text-lg font-semibold leading-6 text-gray-900" id="modal-title">Thêm bậc giá</h2>
                 <div class="ml-3 flex h-7 items-center">
                   <button type="button" onclick="closeModal()" class="rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                     <span class="sr-only">Close panel</span>
                     <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                   </button>
                 </div>
               </div>
               <p class="mt-1 text-sm text-gray-500">Điền thông tin bên dưới để thiết lập giá bán sỉ.</p>
             </div>
             
             <div class="relative flex-1 px-4 py-6 sm:px-6">
                <form id="modal-form" action="{{ route('admin.volume_pricing.store') }}" method="post" class="space-y-6">
                    @csrf
                    <input type="hidden" name="_method" id="form-method" value="POST">
                    <input type="hidden" name="product_variant_id" id="product_variant_id">
                    
                    <div id="product_select_block" class="relative">
                      <label class="block text-sm font-medium leading-6 text-gray-900">Tìm sản phẩm</label>
                      <div class="mt-2 relative">
                          <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a1 1 0 11-1.414 1.414l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" /></svg>
                          </div>
                          <input id="product_search" type="text" 
                                class="block w-full rounded-md border-0 py-2 pl-10 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" 
                                placeholder="Nhập tên hoặc SKU..." autocomplete="off">
                          <div id="search_results" class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm hidden custom-scrollbar"></div>
                      </div>
                    </div>

                    <div id="product_readonly_block" class="hidden">
                      <label class="block text-sm font-medium leading-6 text-gray-900">Sản phẩm đang chọn</label>
                      <div class="mt-2">
                        <input id="product_readonly" type="text" class="block w-full rounded-md border-0 py-2 bg-gray-50 text-gray-500 shadow-sm ring-1 ring-inset ring-gray-200 sm:text-sm sm:leading-6 cursor-not-allowed" readonly>
                      </div>
                    </div>

                    <div class="grid grid-cols-2 gap-5">
                      <div>
                        <label class="block text-sm font-medium leading-6 text-gray-900">Số lượng tối thiểu <span class="text-red-500">*</span></label>
                        <div class="mt-2">
                            <input name="min_qty" id="min_qty" type="number" min="1" required
                                class="block w-full rounded-md border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                      </div>
                      <div>
                        <label class="block text-sm font-medium leading-6 text-gray-900">Đơn giá ưu đãi <span class="text-red-500">*</span></label>
                        <div class="mt-2 relative rounded-md shadow-sm">
                            <input name="price" id="price" type="number" step="0.01" required
                                class="block w-full rounded-md border-0 py-2 pr-10 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                <span class="text-gray-500 sm:text-sm">đ</span>
                            </div>
                        </div>
                      </div>
                    </div>

                    <div class="border-t border-gray-100 pt-6 space-y-4">
                        <div class="relative flex items-start">
                            <div class="flex h-6 items-center">
                              <input name="is_active" id="is_active" type="checkbox" value="1" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
                            </div>
                            <div class="ml-3 text-sm leading-6">
                              <label for="is_active" class="font-medium text-gray-900">Kích hoạt</label>
                              <p class="text-gray-500">Bậc giá sẽ được áp dụng ngay lập tức.</p>
                            </div>
                        </div>
                        
                        <div class="relative flex items-start">
                            <div class="flex h-6 items-center">
                              <input name="free_shipping" id="free_shipping" type="checkbox" value="1" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
                            </div>
                            <div class="ml-3 text-sm leading-6">
                              <label for="free_shipping" class="font-medium text-gray-900">Freeship</label>
                              <p class="text-gray-500">Miễn phí vận chuyển khi đạt số lượng này.</p>
                            </div>
                        </div>
                    </div>
                  </form>
             </div>

             <div class="flex flex-shrink-0 justify-end px-4 py-4 bg-gray-50 border-t border-gray-200 gap-3">
               <button type="button" onclick="closeModal()" class="rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Hủy bỏ</button>
               <button type="button" onclick="document.getElementById('modal-form').submit()" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Lưu thay đổi</button>
             </div>
           </div>
         </div>
       </div>
     </div>
   </div>
 </div>

 <script>
  // Animation helpers
  function enter(el, transition) {
      el.classList.remove('hidden');
      setTimeout(() => {
          el.classList.remove('opacity-0', 'translate-x-full');
      }, 10);
  }
  function leave(el, panel, transition) {
      panel.classList.add('translate-x-full');
      el.classList.add('opacity-0');
      setTimeout(() => {
          el.classList.add('hidden');
      }, 500);
  }

  function openCreateModal(){
    const modal = document.getElementById('modal');
    const backdrop = document.getElementById('modal-backdrop');
    const panel = document.getElementById('modal-panel');
    
    // Reset form
    document.getElementById('product_select_block').classList.remove('hidden');
    document.getElementById('product_readonly_block').classList.add('hidden');
    document.getElementById('product_variant_id').value='';
    document.getElementById('product_search').value='';
    document.getElementById('min_qty').value='';
    document.getElementById('price').value='';
    document.getElementById('is_active').checked=true;
    document.getElementById('free_shipping').checked=false;
    
    // Config form
    const form = document.getElementById('modal-form');
    document.getElementById('modal-title').textContent = 'Thêm bậc giá mới';
    form.action='{{ route('admin.volume_pricing.store') }}'; 
    document.getElementById('form-method').value='POST';
    
    // Show
    enter(backdrop);
    modal.classList.remove('hidden');
    setTimeout(() => panel.classList.remove('translate-x-full'), 10);
  }

  function openEditModal(btn){
    const modal = document.getElementById('modal');
    const backdrop = document.getElementById('modal-backdrop');
    const panel = document.getElementById('modal-panel');
    
    // Get data
    const id=btn.getAttribute('data-id'); 
    const pid=btn.getAttribute('data-variant-id'); 
    const pname=btn.getAttribute('data-variant-name');
    const min=btn.getAttribute('data-min'); 
    const price=btn.getAttribute('data-price'); 
    const active=btn.getAttribute('data-active')==='1'; 
    const freeship=btn.getAttribute('data-freeship')==='1';
    
    // Set form
    document.getElementById('product_select_block').classList.add('hidden');
    document.getElementById('product_readonly_block').classList.remove('hidden');
    document.getElementById('product_readonly').value=pname;
    document.getElementById('product_variant_id').value=pid;
    document.getElementById('min_qty').value=min;
    document.getElementById('price').value=price;
    document.getElementById('is_active').checked=active;
    document.getElementById('free_shipping').checked=freeship;
    
    // Config form
    document.getElementById('modal-title').textContent = 'Cập nhật bậc giá';
    const form = document.getElementById('modal-form');
    form.action='{{ url('/admin/volume-pricing') }}/'+id; 
    document.getElementById('form-method').value='PUT';
    
    // Show
    enter(backdrop);
    modal.classList.remove('hidden');
    setTimeout(() => panel.classList.remove('translate-x-full'), 10);
  }

  function closeModal(){
    const modal = document.getElementById('modal');
    const backdrop = document.getElementById('modal-backdrop');
    const panel = document.getElementById('modal-panel');
    
    panel.classList.add('translate-x-full');
    backdrop.classList.add('opacity-0');
    setTimeout(() => modal.classList.add('hidden'), 500);
  }

  // Search Logic (Refined for new UI)
  (function(){
    const input=document.getElementById('product_search');
    const idEl=document.getElementById('product_variant_id');
    const box=document.getElementById('search_results');
    
    async function search(q){
      try{
        const resp=await fetch('{{ route('admin.combos.products.search') }}?q='+encodeURIComponent(q),{
            credentials:'same-origin',
            headers:{'Accept':'application/json','X-Requested-With':'XMLHttpRequest'}
        });
        const data=await resp.json();
        render(Array.isArray(data.items)?data.items:[]);
      }catch(e){ render([]); }
    }
    
    function render(items){
      box.innerHTML=''; 
      if(!items||items.length===0){ 
          box.classList.add('hidden'); 
          // Don't clear ID here to allow user typing without selecting immediately clearing id
          return; 
      }
      
      items.slice(0,50).forEach(it=>{
        const div = document.createElement('div');
        div.className = 'cursor-pointer hover:bg-indigo-50 px-4 py-2 transition-colors border-b border-gray-100 last:border-0';
        
        const imgUrl = it.image ? it.image : null;
        const imgHtml = imgUrl 
            ? `<img src="${imgUrl}" class="h-8 w-8 rounded object-cover flex-shrink-0 border border-gray-200">` 
            : `<div class="h-8 w-8 rounded bg-gray-100 flex items-center justify-center flex-shrink-0 text-gray-400 border border-gray-200"><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2z"></path></svg></div>`;
            
        div.innerHTML = `
            <div class="flex items-center gap-3">
                ${imgHtml}
                <div class="min-w-0 flex-1">
                    <div class="text-sm font-medium text-gray-900 truncate">${it.name || 'Sản phẩm không tên'}</div>
                    <div class="text-xs text-gray-500">SKU: ${it.sku || 'N/A'} ${it.price ? ' • '+ new Intl.NumberFormat('vi-VN').format(it.price) +'đ' : ''}</div>
                </div>
            </div>
        `;
        
        div.addEventListener('click',()=>{ 
            idEl.value=it.id; 
            input.value=it.name; 
            box.classList.add('hidden'); 
        });
        box.appendChild(div);
      }); 
      box.classList.remove('hidden');
    }
    
    if(input){
        let debounceTimer;
        input.addEventListener('input',()=>{ 
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                const q=input.value.trim(); 
                if(q.length>=2) search(q); else box.classList.add('hidden'); 
            }, 300);
        });
        
        input.addEventListener('focus',()=>{ 
            const q=input.value.trim(); 
            if(q.length>=2) search(q); 
        });
        
        // Close search when clicking outside
        document.addEventListener('click', function(event) {
            if (!input.contains(event.target) && !box.contains(event.target)) {
                box.classList.add('hidden');
            }
        });
    }
  })();
 </script>
</body>
</html>