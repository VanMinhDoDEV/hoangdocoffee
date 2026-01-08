<!doctype html>
<html lang="{{ app()->getLocale() }}">
 <head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ __('messages.product_list') }}</title>
  <link rel="shortcut icon" type="image/x-icon" href="{{ !empty($storeSettings['favicon']) ? asset($storeSettings['favicon']) : asset('assets/images/favicon.png') }}">
  <link rel="icon" type="image/png" href="{{ !empty($storeSettings['favicon']) ? asset($storeSettings['favicon']) : asset('assets/images/favicon.png') }}">
  @vite(['resources/css/app.css','resources/js/app.js'])
  <style>
    .nav-link{transition:all .2s ease}
    .nav-link:hover{background-color:rgba(255,255,255,.1)}
    .nav-link.active{background-color:rgba(255,255,255,.15);border-left:3px solid #3498db}
    .table-row{transition:background-color .2s ease}
    .table-row:hover{background-color:#f9fafb}
    .badge{border-radius:9999px;padding:.25rem .5rem;font-size:.75rem}
  </style>
 </head>
 <body class="bg-gray-50">
 <div class="w-full min-h-screen flex">
  @include('admin.partials.sidebar')
  <main class="flex-1 overflow-auto md:ml-64 transition-all duration-300">
    @include('admin.partials.topbar', ['title' => __('messages.product_list')])
    <div class="p-8">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
     <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
      <div class="flex items-center justify-between mb-4">
       <h3 class="text-gray-600 font-medium text-sm">{{ __('messages.total_products') }}</h3>
       <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center">
        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h13M3 12h9m-9 5h6"></path></svg>
       </div>
      </div>
      <p class="text-3xl font-bold text-gray-900">{{ $statsProducts['total'] ?? '—' }}</p>
      <div class="flex items-center justify-between"><span class="text-sm text-gray-500">{{ __('messages.records') }}</span><span class="text-sm font-semibold text-green-600"></span></div>
     </div>
     <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
      <div class="flex items-center justify-between mb-4">
       <h3 class="text-gray-600 font-medium text-sm">{{ __('messages.selling') }}</h3>
       <div class="w-10 h-10 bg-green-50 rounded-lg flex items-center justify-center">
        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
       </div>
      </div>
      <p class="text-3xl font-bold text-gray-900">{{ $statsProducts['active'] ?? '—' }}</p>
      <div class="flex items-center justify-between"><span class="text-sm text-gray-500">{{ __('messages.products') }}</span><span class="text-sm font-semibold text-green-600"></span></div>
     </div>
     <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
      <div class="flex items-center justify-between mb-4">
       <h3 class="text-gray-600 font-medium text-sm">{{ __('messages.out_of_stock') }}</h3>
       <div class="w-10 h-10 bg-orange-50 rounded-lg flex items-center justify-center">
        <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 8v.01M12 19a7 7 0 100-14 7 7 0 000 14z"></path></svg>
       </div>
      </div>
      <p class="text-3xl font-bold text-gray-900">{{ $statsProducts['out_of_stock'] ?? '—' }}</p>
      <div class="flex items-center justify-between"><span class="text-sm text-gray-500">{{ __('messages.products') }}</span><span class="text-sm font-semibold text-gray-400"></span></div>
     </div>
     <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
      <div class="flex items-center justify-between mb-4">
       <h3 class="text-gray-600 font-medium text-sm">{{ __('messages.total_variants') }}</h3>
       <div class="w-10 h-10 bg-purple-50 rounded-lg flex items-center justify-center">
        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
       </div>
      </div>
      <p class="text-3xl font-bold text-gray-900">{{ $statsProducts['variants'] ?? '—' }}</p>
      <div class="flex items-center justify-between"><span class="text-sm text-gray-500">{{ __('messages.records') }}</span><span class="text-sm font-semibold text-red-600"></span></div>
     </div>
    </div>
 
     <div class="bg-white rounded-xl shadow-sm border border-gray-100">
      <div class="p-6 border-b border-gray-100">
       <div class="flex flex-col lg:flex-row lg:items-center gap-4">
        <div class="flex items-center gap-2 text-gray-700 font-semibold">
         <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
         <span>{{ __('messages.filter') }}</span>
        </div>
        <form method="get" action="{{ route('admin.products') }}" class="flex flex-col sm:flex-row gap-3 flex-1" onsubmit="return false;">
         <select id="filterStatus" name="status" class="input-primary">
          <option value="">{{ __('messages.status') }}</option>
          <option value="active" {{ ($filters['status'] ?? '')==='active'?'selected':'' }}>{{ __('messages.selling') }}</option>
          <option value="hidden" {{ ($filters['status'] ?? '')==='hidden'?'selected':'' }}>{{ __('messages.hidden') }}</option>
         </select>
         <select id="filterCategory" name="category_id" class="input-primary">
          <option value="">{{ __('messages.categories') }}</option>
          @isset($allCategories)
            @foreach($allCategories as $c)
              <option value="{{ $c->id }}" {{ (string)($filters['category_id'] ?? '')===(string)$c->id?'selected':'' }}>{{ $c->name }}</option>
            @endforeach
          @endisset
         </select>
         <select id="filterStock" name="stock" class="input-primary">
          <option value="">{{ __('messages.stock') }}</option>
          <option value="in" {{ ($filters['stock'] ?? '')==='in'?'selected':'' }}>{{ __('messages.in_stock') }}</option>
          <option value="out" {{ ($filters['stock'] ?? '')==='out'?'selected':'' }}>{{ __('messages.out_of_stock') }}</option>
         </select>
         <select id="filterFeatured" name="featured" class="input-primary">
          <option value="">{{ __('messages.all') }}</option>
          <option value="1" {{ ($filters['featured'] ?? '')==='1'?'selected':'' }}>{{ __('messages.featured_product') }}</option>
         </select>
         <div class="relative flex-1 sm:max-w-xs">
          <input id="filterSearch" name="q" type="text" placeholder="{{ __('messages.search') }}" value="{{ $filters['q'] ?? '' }}" class="input-primary pl-10">
          <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
         </div>
         <button id="filterSubmit" type="button" class="px-3 py-2 border border-gray-200 rounded-md text-sm text-gray-700 hover:bg-gray-50">{{ __('messages.filter') }}</button>
        </form>
         <a href="{{ route('admin.products.create') }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-md">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
          <span>{{ __('messages.add_product') }}</span>
         </a>
         <button id="bulkDeleteBtn" type="button" class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-md ml-2">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
          <span>{{ __('messages.delete_selected') }}</span>
         </button>
         </div>
        </div>
      </div>
      <div class="overflow-x-auto">
       <table class="w-full">
        <thead class="bg-gray-50 border-b border-gray-100">
         <tr>
          <th class="px-6 py-4 text-left w-12"><input id="checkAll" type="checkbox" class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-2 focus:ring-blue-500"></th>
          <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">{{ __('messages.product') }}</th>
          <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">{{ __('messages.categories') }}</th>
          <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">{{ __('messages.stock') }}</th>
          <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">{{ __('messages.sku') }}</th>
          <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">{{ __('messages.price') }}</th>
          <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">{{ __('messages.variants') }}</th>
          <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">{{ __('messages.status') }}</th>
          <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">{{ __('messages.actions') }}</th>
         </tr>
        </thead>
        <tbody id="productsTableBody" class="divide-y divide-gray-100">
         @forelse($products as $p)
         @php $img = ($p->images[0]->url ?? null); $v = $p->variants->first(); $stock = $p->variants->sum('inventory_quantity'); @endphp
         <tr class="hover:bg-gray-50 transition-colors">
          <td class="px-6 py-4"><input type="checkbox" class="row-check w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-2 focus:ring-blue-500" data-id="{{ $p->id }}"></td>
          <td class="px-6 py-4">
           <div class="flex items-center gap-3">
            <div class="w-12 h-12 {{ $img ? 'bg-white' : 'bg-gradient-to-br from-blue-400 to-blue-600' }} rounded-lg overflow-hidden flex items-center justify-center text-white font-bold">
             @if($img)
              <img src="{{ $img }}" alt="{{ $p->name }}" class="w-12 h-12 object-cover"/>
             @else
              {{ Str::of($p->name)->substr(0,2)->upper() }}
             @endif
            </div>
            <div>
             <p class="font-semibold text-gray-900">
               {{ $p->name }}
               @if($p->is_featured)
                 <svg class="w-4 h-4 text-yellow-400 inline-block ml-1" fill="currentColor" viewBox="0 0 20 20" title="{{ __('messages.featured_product') }}"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
               @endif
             </p>
             <p class="text-sm text-gray-500">{{ $p->material ?? __('messages.unknown') }}</p>
            </div>
           </div>
          </td>
          <td class="px-6 py-4 text-sm text-gray-700 text-center">{{ $p->category->name ?? '—' }}</td>
          <td class="px-6 py-4 text-sm text-gray-700 text-center">{{ $stock }}</td>
          <td class="px-6 py-4 text-sm text-gray-700 text-center">{{ $p->product_sku ?? '—' }}</td>
          <td class="px-6 py-4 text-sm text-gray-700 text-center">{{ isset($v->price) ? number_format($v->price, 0, ',', '.') . 'đ' : '—' }}</td>
          <td class="px-6 py-4 text-sm text-gray-700 text-center">{{ $p->variants_count ?? $p->variants->count() }}</td>
          <td class="px-6 py-4 text-center">
           <span class="px-3 py-1 text-xs font-semibold rounded-full {{ ($p->is_active ?? true) ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">{{ ($p->is_active ?? true) ? __('messages.selling') : __('messages.hidden') }}</span>
          </td>
          <td class="px-6 py-4">
           <div class="flex items-center justify-center gap-2">
            <a href="{{ $p->slug ? route('sanpham.show', $p->slug) : '#' }}" class="text-blue-600 hover:text-blue-800" title="{{ __('messages.view') }}" target="_blank" rel="noopener">
             <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
            </a>
            <a href="{{ route('admin.products.edit', $p->id) }}" class="text-green-600 hover:text-green-800" title="{{ __('messages.edit') }}">
             <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            </a>
            <form method="POST" action="{{ route('admin.products.delete', $p->id) }}" onsubmit="return confirm('{{ __('messages.confirm_delete_product') }}');">
              @csrf
              @method('DELETE')
              <button type="submit" class="text-red-600 hover:text-red-800" title="{{ __('messages.delete') }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
              </button>
            </form>
           </div>
          </td>
         </tr>
         @empty
         <tr>
          <td class="px-6 py-6 text-gray-500 text-center" colspan="9">{{ __('messages.no_products_found') }}</td>
         </tr>
         @endforelse
        </tbody>
       </table>
      </div>
      <div class="p-6" id="ajaxPagination"></div>
     </div>
    </div>
   </main>
  </div>
  <script>
    function toggleSubmenu(id){const submenu=document.getElementById(id+'-submenu');const arrow=document.getElementById(id+'-arrow');if(!submenu||!arrow)return;submenu.classList.toggle('hidden');arrow.classList.toggle('rotate-180');}
    (function initAjaxFilter(){
      const statusSel=document.getElementById('filterStatus');
      const catSel=document.getElementById('filterCategory');
      const stockSel=document.getElementById('filterStock');
      const featuredSel=document.getElementById('filterFeatured');
      const searchInp=document.getElementById('filterSearch');
      const btn=document.getElementById('filterSubmit');
      const tbody=document.getElementById('productsTableBody');
      const pager=document.getElementById('ajaxPagination');
      let timer=null;
      function q(){
        const params=new URLSearchParams();
        const s=statusSel.value||''; const c=catSel.value||''; const st=stockSel.value||''; const q=searchInp.value||'';
        const f=featuredSel.value||'';
        if(s) params.set('status', s);
        if(c) params.set('category_id', c);
        if(st) params.set('stock', st);
        if(f) params.set('featured', f);
        if(q.trim()) params.set('q', q.trim());
        return params;
      }
      async function fetchPage(page=1){
        const params=q(); params.set('page', String(page));
        const url='{{ route('admin.products.json') }}'+'?'+params.toString();
        const resp=await fetch(url, {headers:{'Accept':'application/json','X-Requested-With':'XMLHttpRequest'}});
        const data=await resp.json();
        const items=Array.isArray(data.items)?data.items:[];
        tbody.innerHTML=items.map(renderRow).join('');
        renderPager(data.pagination||{current_page:1,last_page:1});
      }
      function renderRow(p){
        const imgHtml=p.image?`<img src="${p.image}" alt="${p.name}" class="w-12 h-12 object-cover"/>`:`${(p.name||'').slice(0,2).toUpperCase()}`;
        const statusBadge=p.is_active?'<span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">{{ __('messages.selling') }}</span>':'<span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-700">{{ __('messages.hidden') }}</span>';
        const priceStr=(p.price!=null)?new Intl.NumberFormat('vi-VN').format(Number(p.price))+'đ':'—';
        const viewHref=p.slug?('{{ url('/products') }}'+'/'+p.slug):'#';
        const featuredIcon=p.is_featured?'<svg class="w-4 h-4 text-yellow-400 inline-block ml-1" fill="currentColor" viewBox="0 0 20 20" title="{{ __('messages.featured_product') }}"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>':'';
        return `<tr class="hover:bg-gray-50 transition-colors">
          <td class="px-6 py-4"><input type="checkbox" class="row-check w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-2 focus:ring-blue-500" data-id="${p.id}"></td>
          <td class="px-6 py-4"><div class="flex items-center gap-3"><div class="w-12 h-12 ${p.image?'bg-white':'bg-gradient-to-br from-blue-400 to-blue-600'} rounded-lg overflow-hidden flex items-center justify-center text-white font-bold">${imgHtml}</div><div><p class="font-semibold text-gray-900">${p.name||''}${featuredIcon}</p><p class="text-sm text-gray-500">${p.material||'—'}</p></div></div></td>
          <td class="px-6 py-4 text-sm text-gray-700 text-center">${p.category||'—'}</td>
          <td class="px-6 py-4 text-sm text-gray-700 text-center">${p.stock||0}</td>
          <td class="px-6 py-4 text-sm text-gray-700 text-center">${p.sku||'—'}</td>
          <td class="px-6 py-4 text-sm text-gray-700 text-center">${priceStr}</td>
          <td class="px-6 py-4 text-sm text-gray-700 text-center">${p.variants_count||0}</td>
          <td class="px-6 py-4 text-center">${statusBadge}</td>
          <td class="px-6 py-4"><div class="flex items-center justify-center gap-2">
            <a href="${viewHref}" class="text-blue-600 hover:text-blue-800" title="{{ __('messages.view') }}" target="_blank" rel="noopener"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg></a>
            <a href="{{ url('/admin/products') }}/${p.id}/edit" class="text-green-600 hover:text-green-800" title="{{ __('messages.edit') }}"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></a>
            <form method="POST" action="{{ url('/admin/products') }}/${p.id}" onsubmit="return confirm('{{ __('messages.confirm_delete_product') }}');"><input type="hidden" name="_token" value="{{ csrf_token() }}"><input type="hidden" name="_method" value="DELETE"><button type="submit" class="text-red-600 hover:text-red-800" title="{{ __('messages.delete') }}"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button></form>
          </div></td>
        </tr>`;
      }
      function renderPager(pg){
        const cur = pg.current_page || 1;
        const last = pg.last_page || 1;
        const total = pg.total || 0;
        const from = pg.from || 0;
        const to = pg.to || 0;

        if (total === 0) {
          pager.innerHTML = '';
          return;
        }

        let html = `<div class="flex items-center justify-between w-full">
          <div class="text-sm font-medium text-gray-600">
            {{ __('messages.pagination_info') }}`.replace(':from', from).replace(':to', to).replace(':total', total) + `
          </div>`;

        if (last > 1) {
          html += `<div class="flex items-center gap-2">
            <button ${cur<=1?'disabled':''} class="px-3 py-2 border rounded-md ${cur<=1?'text-gray-400 cursor-not-allowed':'text-gray-700 hover:bg-gray-50'}" id="pgPrev">{{ __('messages.prev_page') }}</button>
            <span class="text-sm text-gray-600">{{ __('messages.page') ?? 'Page' }} ${cur} / ${last}</span>
            <button ${cur>=last?'disabled':''} class="px-3 py-2 border rounded-md ${cur>=last?'text-gray-400 cursor-not-allowed':'text-gray-700 hover:bg-gray-50'}" id="pgNext">{{ __('messages.next_page') }}</button>
          </div>`;
        } else {
           html += `<div></div>`;
        }

        html += `</div>`;
        pager.innerHTML = html;

        const prev = document.getElementById('pgPrev');
        const next = document.getElementById('pgNext');
        prev && prev.addEventListener('click',()=>{if(cur>1) fetchPage(cur-1);});
        next && next.addEventListener('click',()=>{if(cur<last) fetchPage(cur+1);});
      }
      function trigger(){ clearTimeout(timer); timer=setTimeout(()=>fetchPage(1), 250); }
      const bulkBtn = document.getElementById('bulkDeleteBtn');
      const checkAll = document.getElementById('checkAll');
      function selectedIds(){
        const ids=[]; document.querySelectorAll('#productsTableBody .row-check:checked').forEach(cb=>{const id=cb.getAttribute('data-id'); if(id) ids.push(Number(id));});
        return ids;
      }
      checkAll && checkAll.addEventListener('change', ()=> {
        const checked = checkAll.checked;
        document.querySelectorAll('#productsTableBody .row-check').forEach(cb=>{cb.checked = checked;});
      });
      bulkBtn && bulkBtn.addEventListener('click', async ()=>{
        const ids = selectedIds();
        if(ids.length===0){ alert('{{ __('messages.no_products_selected') ?? 'No products selected' }}'); return; }
        if(!confirm('{{ __('messages.confirm_delete_selected') ?? 'Delete selected products?' }}')) return;
        try{
          const resp = await fetch('{{ route('admin.products.bulk_delete') }}', {
            method: 'POST',
            headers: {'Accept':'application/json','Content-Type':'application/json','X-Requested-With':'XMLHttpRequest','X-CSRF-TOKEN':'{{ csrf_token() }}'},
            body: JSON.stringify({ids})
          });
          if(resp.ok){
            fetchPage(1);
          }else{
            alert('{{ __('messages.delete_failed') ?? 'Delete failed' }}');
          }
        }catch(_){
          alert('{{ __('messages.error_occurred') ?? 'Error occurred' }}');
        }
      });
      statusSel.addEventListener('change', trigger);
      catSel.addEventListener('change', trigger);
      stockSel.addEventListener('change', trigger);
      searchInp.addEventListener('input', trigger);
      btn.addEventListener('click', trigger);
      fetchPage(1);
    })();
    window.toggleSubmenu = function(id){
      const submenu=document.getElementById(id+'-submenu');
      const arrow=document.getElementById(id+'-arrow');
      if(!submenu||!arrow)return;
      submenu.classList.toggle('hidden');
      arrow.classList.toggle('rotate-180');
    };
  </script>
 </body>
</html>
