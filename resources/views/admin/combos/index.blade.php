<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Quản lý Combo</title>
    <link rel="shortcut icon" type="image/x-icon" href="{{ !empty($storeSettings['favicon']) ? asset($storeSettings['favicon']) : asset('assets/images/favicon.png') }}">
    <link rel="icon" type="image/png" href="{{ !empty($storeSettings['favicon']) ? asset($storeSettings['favicon']) : asset('assets/images/favicon.png') }}">
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="bg-gray-50 text-slate-800 antialiased">
<div class="w-full min-h-screen flex">
    
    @include('admin.partials.sidebar')

    <main class="flex-1 overflow-auto md:ml-64 transition-all duration-300">
        @include('admin.partials.topbar', ['title' => 'Quản lý Combo'])

        <div class="flex-1 overflow-auto p-6 lg:p-8">
            <div class="max-w-7xl mx-auto">
                
                @if(session('status'))
                    <div class="mb-6 flex items-center p-4 rounded-lg bg-green-50 text-green-700 border border-green-100 shadow-sm">
                        <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        <span class="font-medium">{{ session('status') }}</span>
                    </div>
                @endif
                @if($errors->any())
                    <div class="mb-6 p-4 rounded-lg bg-red-50 text-red-700 border border-red-100 shadow-sm">
                        <div class="flex items-center mb-2">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            <h3 class="font-semibold">Vui lòng kiểm tra lại dữ liệu</h3>
                        </div>
                        <ul class="list-disc list-inside text-sm pl-2 space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
                    <div>
                        <h2 class="text-2xl font-bold tracking-tight text-slate-900">Danh sách Combo</h2>
                        <p class="text-sm text-slate-500 mt-1">Quản lý các gói combo và khuyến mãi của bạn.</p>
                    </div>
                    <button onclick="openModal()" class="inline-flex items-center justify-center px-5 py-2.5 bg-slate-900 text-white rounded-lg hover:bg-slate-800 transition-all shadow-sm focus:ring-4 focus:ring-slate-200 font-medium text-sm">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Tạo combo mới
                    </button>
                </div>

                <div class="bg-white rounded-xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-slate-100 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50/50 border-b border-slate-100 text-xs uppercase tracking-wider text-slate-500 font-semibold">
                                    <th class="py-4 px-6">Tên Combo / Slug</th>
                                    <th class="py-4 px-6">Giá trọn gói</th>
                                    <th class="py-4 px-6">Thông tin</th>
                                    <th class="py-4 px-6">Trạng thái</th>
                                    <th class="py-4 px-6 text-right">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                            @forelse($combos as $c)
                                <tr class="hover:bg-slate-50/80 transition-colors group">
                                    <td class="py-4 px-6">
                                        <div class="flex flex-col">
                                            <span class="font-semibold text-slate-800 text-sm group-hover:text-indigo-600 transition-colors">{{ $c->name }}</span>
                                            <span class="text-xs text-slate-400 font-mono mt-0.5">{{ $c->slug }}</span>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6 font-medium text-slate-700 tabular-nums">
                                        {{ $c->price !== null ? number_format($c->price, 0, ',', '.') . 'đ' : '-' }}
                                    </td>
                                    <td class="py-4 px-6">
                                        <div class="flex flex-wrap gap-2">
                                            @if($c->free_shipping ?? false)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700 border border-indigo-100">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                                                    Freeship
                                                </span>
                                            @endif
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-600 border border-slate-200">
                                                {{ $c->lines_count ?? 0 }} sản phẩm
                                            </span>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6">
                                        @if($c->is_active)
                                            <div class="inline-flex items-center px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-100">
                                                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full mr-1.5"></span>
                                                <span class="text-xs font-medium">Hoạt động</span>
                                            </div>
                                        @else
                                            <div class="inline-flex items-center px-2.5 py-1 rounded-full bg-slate-100 text-slate-600 border border-slate-200">
                                                <span class="w-1.5 h-1.5 bg-slate-400 rounded-full mr-1.5"></span>
                                                <span class="text-xs font-medium">Tạm dừng</span>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-right">
                                        <div class="flex justify-end items-center space-x-3">
                                            <a href="{{ route('admin.combos.edit', $c->id) }}" class="text-slate-400 hover:text-indigo-600 transition-colors p-1" title="Sửa">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            </a>
                                            <form action="{{ route('admin.combos.destroy', $c->id) }}" method="post" class="inline-block" onsubmit="return confirm('Bạn có chắc muốn xóa combo này không?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-slate-400 hover:text-red-600 transition-colors p-1" title="Xóa">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-12 text-center">
                                        <div class="flex flex-col items-center justify-center text-slate-400">
                                            <svg class="w-12 h-12 mb-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                            <p class="text-base font-medium text-slate-500">Chưa có combo nào</p>
                                            <p class="text-sm">Hãy tạo combo đầu tiên để bắt đầu bán hàng.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($combos->hasPages())
                        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                            {{ $combos->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </main>
</div>

<div id="modal" class="relative z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" onclick="closeModal()"></div>

    <div class="fixed inset-0 overflow-hidden">
        <div class="absolute inset-0 overflow-hidden">
            <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
                <div class="pointer-events-auto w-screen max-w-md transform transition-transform duration-500 ease-in-out translate-x-full" id="modal-panel">
                    <div class="flex h-full flex-col bg-white shadow-2xl">
                        <div class="px-6 py-6 border-b border-slate-100 flex items-center justify-between bg-white">
                            <h2 class="text-lg font-semibold text-slate-900" id="modal-title">Tạo Combo Mới</h2>
                            <button onclick="closeModal()" class="text-slate-400 hover:text-slate-500 focus:outline-none p-2 rounded-full hover:bg-slate-50 transition-colors">
                                <span class="sr-only">Close panel</span>
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <div class="flex-1 overflow-y-auto px-6 py-6 bg-white">
                            <form id="modal-form" action="{{ route('admin.combos.store') }}" method="post" class="space-y-6">
                                @csrf
                                
                                <div>
                                    <label for="name" class="block text-sm font-medium leading-6 text-slate-900">Tên Combo <span class="text-red-500">*</span></label>
                                    <div class="mt-2">
                                        <input type="text" name="name" id="name" required 
                                            class="block w-full rounded-lg border-0 py-2.5 px-3 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 transition-all"
                                            placeholder="VD: Combo Tết Sum Vầy">
                                    </div>
                                </div>

                                <div>
                                    <label for="slug" class="block text-sm font-medium leading-6 text-slate-900">Slug (URL)</label>
                                    <div class="mt-2">
                                        <input type="text" name="slug" id="slug" 
                                            class="block w-full rounded-lg border-0 py-2.5 px-3 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 bg-slate-50"
                                            placeholder="Để trống để tự động tạo">
                                        <p class="mt-1 text-xs text-slate-500">Đường dẫn tĩnh cho sản phẩm.</p>
                                    </div>
                                </div>

                                <div>
                                    <label for="price" class="block text-sm font-medium leading-6 text-slate-900">Giá trọn gói <span class="text-red-500">*</span></label>
                                    <div class="mt-2 relative rounded-md shadow-sm">
                                        <input type="number" name="price" id="price" required step="0.01" min="0"
                                            class="block w-full rounded-lg border-0 py-2.5 px-3 pr-12 text-slate-900 ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                            placeholder="0">
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                            <span class="text-slate-500 sm:text-sm">VND</span>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label for="description" class="block text-sm font-medium leading-6 text-slate-900">Mô tả ngắn</label>
                                    <div class="mt-2">
                                        <textarea id="description" name="description" rows="4" 
                                            class="block w-full rounded-lg border-0 py-2.5 px-3 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"></textarea>
                                    </div>
                                </div>

                                <div class="bg-slate-50 rounded-lg p-4 space-y-4 border border-slate-100">
                                    <div class="relative flex items-start">
                                        <div class="flex h-6 items-center">
                                            <input id="is_active" name="is_active" type="checkbox" value="1" checked 
                                                class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-600 cursor-pointer">
                                        </div>
                                        <div class="ml-3 text-sm leading-6">
                                            <label for="is_active" class="font-medium text-slate-900 cursor-pointer">Kích hoạt ngay</label>
                                            <p class="text-slate-500">Hiển thị combo này trên website ngay sau khi tạo.</p>
                                        </div>
                                    </div>

                                    <div class="relative flex items-start">
                                        <div class="flex h-6 items-center">
                                            <input id="free_shipping" name="free_shipping" type="checkbox" value="1" 
                                                class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-600 cursor-pointer">
                                        </div>
                                        <div class="ml-3 text-sm leading-6">
                                            <label for="free_shipping" class="font-medium text-slate-900 cursor-pointer">Miễn phí vận chuyển</label>
                                            <p class="text-slate-500">Áp dụng chính sách Freeship cho riêng combo này.</p>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="flex-shrink-0 border-t border-slate-100 px-6 py-5 bg-slate-50 flex justify-end gap-3">
                            <button type="button" onclick="closeModal()" 
                                class="rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 transition-colors">
                                Hủy bỏ
                            </button>
                            <button type="button" onclick="document.getElementById('modal-form').submit()" 
                                class="inline-flex justify-center rounded-lg bg-slate-900 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-slate-800 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-slate-900 transition-colors">
                                Tạo Combo
                            </button>
                        </div>
                    </div>
                </div>
            </div>
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

    function openModal() {
        const modal = document.getElementById('modal');
        const panel = document.getElementById('modal-panel');
        modal.classList.remove('hidden');
        // Small delay to allow display:block to apply before transition starts
        setTimeout(() => {
            panel.classList.remove('translate-x-full');
        }, 10);
    }

    function closeModal() {
        const modal = document.getElementById('modal');
        const panel = document.getElementById('modal-panel');
        panel.classList.add('translate-x-full');
        // Wait for transition to finish
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 500);
    }
</script>
</body>
</html>