<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Chỉnh sửa Combo | Quản trị</title>
    <link rel="shortcut icon" type="image/x-icon" href="{{ !empty($storeSettings['favicon']) ? asset($storeSettings['favicon']) : asset('assets/images/favicon.png') }}">
    @vite(['resources/css/app.css','resources/js/app.js'])
    <style>
        /* Custom scrollbar cho dropdown tìm kiếm */
        .custom-scroll::-webkit-scrollbar { width: 6px; }
        .custom-scroll::-webkit-scrollbar-track { background: #f1f1f1; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        .custom-scroll::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="bg-gray-50 font-sans text-slate-800 antialiased">
    <div class="w-full min-h-screen flex">
        @include('admin.partials.sidebar')

        <main class="flex-1 overflow-auto md:ml-64 transition-all duration-300">
            @include('admin.partials.topbar', ['title' => 'Quản lý Combo'])

            <div class="flex-1 overflow-y-auto custom-scroll p-6 lg:p-8">
                <div class="max-w-7xl mx-auto">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
                        <div>
                            <h1 class="text-2xl font-bold text-slate-900">Sửa Combo: {{ $combo->name }}</h1>
                            <p class="text-slate-500 text-sm mt-1">Quản lý thông tin và các sản phẩm bên trong combo.</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <a href="{{ route('admin.combos.index') }}" class="inline-flex items-center justify-center px-4 py-2 border border-slate-300 shadow-sm text-sm font-medium rounded-lg text-slate-700 bg-white hover:bg-slate-50 focus:outline-none transition-all">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                                Quay lại
                            </a>
                        </div>
                    </div>

                    @if(session('status'))
                        <div class="mb-6 p-4 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200 flex items-center gap-3 shadow-sm">
                            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            <span class="font-medium">{{ session('status') }}</span>
                        </div>
                    @endif
                    @if($errors->any())
                        <div class="mb-6 p-4 rounded-xl bg-red-50 text-red-700 border border-red-200 shadow-sm">
                            <div class="flex items-center gap-2 mb-2 font-semibold">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 001 1h2a1 1 0 001-1V6a1 1 0 00-1-1h-2z" clip-rule="evenodd"/></svg>
                                Đã có lỗi xảy ra
                            </div>
                            <ul class="list-disc list-inside text-sm space-y-1 ml-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                        
                        <div class="lg:col-span-4 space-y-6">
                            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                                    <h2 class="text-base font-semibold text-slate-800 flex items-center gap-2">
                                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></path></svg>
                                        Thông tin chung
                                    </h2>
                                </div>
                                <div class="p-6">
                                    <form action="{{ route('admin.combos.update', $combo->id) }}" method="post" class="space-y-5">
                                        @csrf
                                        @method('PUT')
                                        
                                        <div>
                                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Tên Combo <span class="text-red-500">*</span></label>
                                            <input name="name" type="text" class="w-full border-slate-200 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-3 py-2.5 transition-colors placeholder:text-slate-400" value="{{ $combo->name }}" required placeholder="Ví dụ: Combo Tết Sum Vầy">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Slug URL</label>
                                            <input name="slug" type="text" class="w-full border-slate-200 bg-slate-50 text-slate-500 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-3 py-2.5 transition-colors" value="{{ $combo->slug }}">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Giá bán</label>
                                            <div class="relative rounded-md shadow-sm">
                                                <input name="price" type="number" step="0.01" class="w-full border-slate-200 rounded-lg focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm pl-3 pr-12 py-2.5" value="{{ $combo->price }}" placeholder="0">
                                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                    <span class="text-slate-500 sm:text-sm">đ</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Mô tả</label>
                                            <textarea name="description" class="w-full border-slate-200 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-3 py-2.5 transition-colors" rows="4" placeholder="Nhập mô tả chi tiết...">{{ $combo->description }}</textarea>
                                        </div>

                                        <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg border border-slate-100">
                                            <span class="text-sm font-medium text-slate-700">Trạng thái kích hoạt</span>
                                            <label class="relative inline-flex items-center cursor-pointer">
                                                <input name="is_active" type="checkbox" value="1" class="sr-only peer" {{ $combo->is_active ? 'checked' : '' }}>
                                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                                            </label>
                                        </div>

                                        <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg border border-slate-100">
                                            <span class="text-sm font-medium text-slate-700">Miễn phí vận chuyển (Freeship)</span>
                                            <label class="relative inline-flex items-center cursor-pointer">
                                                <input name="free_shipping" type="checkbox" value="1" class="sr-only peer" {{ ($combo->free_shipping ?? false) ? 'checked' : '' }}>
                                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                                            </label>
                                        </div>

                                        <div class="pt-2">
                                            <button type="submit" class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-slate-900 hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-900 transition-all">
                                                Lưu thay đổi
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="lg:col-span-8 space-y-6">
                            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 flex flex-col min-h-[600px]">
                                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                                    <h2 class="text-base font-semibold text-slate-800 flex items-center gap-2">
                                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></path></svg>
                                        Sản phẩm trong Combo
                                    </h2>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                        {{ $combo->lines->count() }} sản phẩm
                                    </span>
                                </div>
                                
                                <div class="p-6 bg-white border-b border-slate-100">
                                    <div class="flex flex-col md:flex-row gap-4">
                                        <div class="relative flex-1 group">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-slate-400 group-focus-within:text-indigo-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                            </div>
                                            <input id="product-search" type="text" class="w-full pl-10 border-slate-200 rounded-lg focus:border-indigo-500 focus:ring-indigo-500 px-3 py-2.5 transition-shadow" placeholder="Tìm kiếm theo tên hoặc SKU..." autocomplete="off">
                                            
                                            <div id="search-results" class="hidden absolute z-50 w-full mt-1 bg-white rounded-xl shadow-xl border border-slate-100 max-h-60 overflow-y-auto custom-scroll ring-1 ring-black ring-opacity-5"></div>
                                        </div>
                                        
                                        <div class="flex gap-2">
                                            <div class="w-24">
                                                <input id="add-quantity" type="number" class="w-full border-slate-200 rounded-lg focus:border-indigo-500 focus:ring-indigo-500 px-3 py-2.5 text-center" min="1" value="1">
                                            </div>
                                            <button id="add-line-btn" type="button" class="flex-shrink-0 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg shadow-sm transition-colors flex items-center gap-2">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                                <span class="hidden sm:inline">Thêm</span>
                                            </button>
                                        </div>
                                    </div>
                                    <p class="mt-2 text-xs text-slate-400">Gõ ít nhất 2 ký tự để tìm kiếm sản phẩm.</p>
                                </div>

                                <div class="overflow-x-auto flex-1">
                                    <table class="w-full text-left border-collapse">
                                        <thead class="bg-slate-50 sticky top-0 z-10">
                                            <tr class="text-xs uppercase tracking-wider text-slate-500 font-semibold">
                                                <th class="py-4 px-6 border-b border-slate-200">Sản phẩm</th>
                                                <th class="py-4 px-6 border-b border-slate-200 text-center w-32">SKU</th>
                                                <th class="py-4 px-6 border-b border-slate-200 text-center w-40">Số lượng</th>
                                                <th class="py-4 px-6 border-b border-slate-200 text-right w-24"></th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-100">
                                            @forelse($combo->lines as $line)
                                            @php
                                                $v = $line->variant;
                                                $p = $v ? $v->product : ($line->product ?? null);
                                                $img = $v && $v->images && $v->images->first() ? $v->images->first()->url : ($p && $p->images->where('is_primary', true)->first() ? $p->images->where('is_primary', true)->first()->url : null);
                                            @endphp
                                                <tr class="hover:bg-slate-50/80 transition-colors group">
                                                    <td class="py-3 px-6">
                                                        <div class="flex items-center gap-4">
                                                            <div class="relative w-12 h-12 rounded-lg overflow-hidden bg-slate-100 border border-slate-200 flex-shrink-0">
                                                                @if($img)
                                                                    <img src="{{ Str::startsWith($img, ['http://','https://']) ? $img : asset('storage/'.$img) }}" alt="" class="w-full h-full object-cover">
                                                                @else
                                                                    <div class="w-full h-full flex items-center justify-center text-slate-300">
                                                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/></svg>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div>
                                                                <div class="font-medium text-slate-900 group-hover:text-indigo-600 transition-colors line-clamp-1">{{ $p ? $p->name : 'Sản phẩm không tồn tại' }}</div>
                                                                <div class="text-xs text-slate-500 mt-0.5">
                                                                    @if($v && $v->options)
                                                                        {{ $v->options->map(fn($o)=>optional($o->attributeValue)->value)->join(' / ') }}
                                                                    @endif
                                                                    (SKU: {{ $v ? $v->sku : 'N/A' }})
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="py-3 px-6 text-center">
                                                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-slate-100 text-slate-600 border border-slate-200">{{ $v ? $v->sku : ($p ? $p->product_sku : 'N/A') }}</span>
                                                    </td>
                                                    <td class="py-3 px-6">
                                                        <form action="{{ route('admin.combos.lines.update', [$combo->id, $line->id]) }}" method="post" class="flex items-center justify-center">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="relative flex items-center max-w-[100px]">
                                                                <input name="quantity" type="number" min="1" class="block w-full rounded-md border-0 py-1.5 pl-3 pr-8 text-slate-900 ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 text-center" value="{{ $line->quantity }}">
                                                                <div class="absolute inset-y-0 right-0 flex items-center">
                                                                     <button type="submit" title="Cập nhật" class="h-full p-1 text-slate-400 hover:text-indigo-600 transition-colors">
                                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                                     </button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </td>
                                                    <td class="py-3 px-6 text-right">
                                                        <form action="{{ route('admin.combos.lines.destroy', [$combo->id, $line->id]) }}" method="post" onsubmit="return confirm('Xóa sản phẩm này khỏi combo?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all" title="Xóa">
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="py-12 text-center">
                                                        <div class="flex flex-col items-center justify-center text-slate-400">
                                                            <svg class="w-12 h-12 mb-3 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                                                            <p class="text-base font-medium text-slate-500">Chưa có sản phẩm nào trong combo</p>
                                                            <p class="text-sm mt-1">Sử dụng ô tìm kiếm bên trên để thêm sản phẩm.</p>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
    (function(){
        const input = document.getElementById('product-search');
        const box = document.getElementById('search-results');
        const addBtn = document.getElementById('add-line-btn');
        let selectedProductId = null;

        // Xử lý click outside để đóng dropdown
        document.addEventListener('click', function(event) {
            if (!input.contains(event.target) && !box.contains(event.target)) {
                box.classList.add('hidden');
            }
        });

        function render(items){
            box.innerHTML = '';
            if(!items || items.length === 0){
                if(input.value.length >= 2) {
                     box.innerHTML = '<div class="px-4 py-3 text-sm text-slate-500 text-center">Không tìm thấy sản phẩm</div>';
                     box.classList.remove('hidden');
                } else {
                     box.classList.add('hidden');
                }
                selectedProductId = null;
                return;
            }

            items.slice(0,50).forEach(it => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'w-full text-left px-4 py-3 hover:bg-indigo-50 flex items-center gap-3 transition-colors border-b border-slate-50 last:border-0';
                
                // Placeholder cho ảnh nếu không có
                const imgUrl = it.image ? (it.image.startsWith('http') ? it.image : '/storage/'+it.image) : null;
                const imgHtml = imgUrl 
                    ? `<img src="${imgUrl}" class="w-10 h-10 rounded object-cover border border-slate-200">`
                    : `<div class="w-10 h-10 rounded bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-400"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg></div>`;

                btn.innerHTML = `
                    <div class="flex-shrink-0">${imgHtml}</div>
                    <div class="flex-1 min-w-0">
                        <div class="text-sm font-medium text-slate-900 truncate">${it.name || 'Sản phẩm không tên'}</div>
                        <div class="text-xs text-slate-500 flex items-center gap-2">
                            <span class="bg-slate-100 px-1.5 py-0.5 rounded text-slate-600 border border-slate-200 font-mono">${it.sku || 'No SKU'}</span>
                            ${it.price ? '<span>'+ new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(it.price) +'</span>' : ''}
                        </div>
                    </div>
                `;
                
                btn.addEventListener('click', () => {
                    selectedProductId = it.id;
                    input.value = it.name;
                    box.classList.add('hidden');
                    // Focus vào ô số lượng để nhập nhanh
                    document.getElementById('add-quantity').focus();
                });
                box.appendChild(btn);
            });
            box.classList.remove('hidden');
        }

        async function search(q){
            try{
                // Giả lập loading UI
                box.innerHTML = '<div class="px-4 py-3 text-sm text-slate-400 text-center flex items-center justify-center gap-2"><svg class="animate-spin h-4 w-4 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Đang tìm...</div>';
                box.classList.remove('hidden');

                const resp = await fetch('{{ route('admin.combos.products.search') }}?q='+encodeURIComponent(q), {
                    credentials: 'same-origin',
                    headers: { 'Accept':'application/json','X-Requested-With':'XMLHttpRequest' }
                });
                const data = await resp.json();
                render(Array.isArray(data.items)?data.items:[]);
            }catch(e){
                render([]);
            }
        }

        if(input){
            let timeout = null;
            input.addEventListener('input', () => {
                clearTimeout(timeout);
                const q = input.value.trim();
                if(q.length >= 2) {
                    // Debounce search
                    timeout = setTimeout(() => search(q), 300);
                } else {
                    box.classList.add('hidden');
                }
            });
            
            input.addEventListener('focus', () => {
                const q = input.value.trim();
                if(q.length >= 2) search(q);
            });
        }

        if(addBtn){
            addBtn.addEventListener('click', () => {
                const qtyEl = document.getElementById('add-quantity');
                const qty = parseInt(qtyEl.value || '1', 10);

                if(!selectedProductId) {
                    alert('Vui lòng chọn sản phẩm từ danh sách tìm kiếm trước.');
                    input.focus();
                    return;
                }
                if(!qty || qty < 1) {
                    alert('Số lượng không hợp lệ.');
                    return;
                }

                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route('admin.combos.lines.store', $combo->id) }}';
                
                const token = document.createElement('input');
                token.type = 'hidden'; token.name = '_token'; token.value = '{{ csrf_token() }}';
                
                const pid = document.createElement('input');
                pid.type = 'hidden'; pid.name = 'product_variant_id'; pid.value = selectedProductId;
                
                const qEl = document.createElement('input');
                qEl.type = 'hidden'; qEl.name = 'quantity'; qEl.value = qty;
                
                form.appendChild(token);
                form.appendChild(pid);
                form.appendChild(qEl);
                document.body.appendChild(form);
                form.submit();
            });
        }
    })();
    </script>
    <script>
        window.toggleSubmenu = function(id){
            var submenu=document.getElementById(id+'-submenu');
            var arrow=document.getElementById(id+'-arrow');
            if(!submenu||!arrow)return;
            submenu.classList.toggle('hidden');
            arrow.classList.toggle('rotate-180');
        };
    </script>
</body>
</html>
