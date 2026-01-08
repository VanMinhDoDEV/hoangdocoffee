<!doctype html>
<html lang="vi">
 <head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ __('messages.product_categories') }} • Admin</title>
  <meta name="description" content="{{ __('messages.categories_description') }}">
  <link rel="shortcut icon" type="image/x-icon" href="{{ !empty($storeSettings['favicon']) ? asset($storeSettings['favicon']) : asset('assets/images/favicon.png') }}">
  <link rel="icon" type="image/png" href="{{ !empty($storeSettings['favicon']) ? asset($storeSettings['favicon']) : asset('assets/images/favicon.png') }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css','resources/js/app.js'])
  <style>
    html,body{font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,Ubuntu,Cantarell,Noto Sans,sans-serif}
    .card{border-radius:16px;box-shadow:0 10px 30px -12px rgba(0,0,0,.12)}
    .input{transition:border-color .2s, box-shadow .2s}
    .input:focus{outline:none;box-shadow:0 0 0 3px rgba(59,130,246,.25);border-color:#3b82f6}
    .nav-link{transition:all .2s ease}
    .nav-link:hover{background-color:rgba(255,255,255,.1)}
    .nav-link.active{background-color:rgba(255,255,255,.15);border-left:3px solid #3498db}
    .badge{border-radius:9999px;padding:.25rem .5rem;font-size:.75rem}
  </style>
 </head>
 <body class="bg-gray-50">
 <div class="w-full min-h-screen flex">
  @include('admin.partials.sidebar')
  <main class="flex-1 overflow-auto md:ml-64 transition-all duration-300">
    @include('admin.partials.topbar', ['title' => __('messages.product_categories')])
    <div class="p-8">
     @if(session('status'))
      <div class="mb-4 p-3 rounded-lg bg-green-50 text-green-700">{{ session('status') }}</div>
     @endif
     
     <div id="createCategoryModal" class="fixed inset-0 bg-black/40 hidden z-50">
      <div class="min-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-2xl bg-white rounded-2xl shadow-xl max-h-[85vh] overflow-y-auto">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200">
          <h3 class="text-lg font-semibold">{{ __('messages.create_update_category') }}</h3>
          <button type="button" id="m_close" class="p-2 rounded-lg hover:bg-slate-100">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
          </button>
        </div>
        <form id="m_form" method="post" action="{{ route('admin.products.categories.store') }}" class="space-y-5 p-6" novalidate>
          @csrf
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.name') }}</label>
              <input id="m_name" name="name" type="text" class="input-primary" required>
              @error('name')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
              <div class="flex items-center justify-between">
                <label class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                <button type="button" id="m_genSlug" class="text-xs text-blue-600">{{ __('messages.generate_from_name') }}</button>
              </div>
              <input id="m_slug" name="slug" type="text" class="input-primary">
              <p class="text-gray-500 text-xs mt-1">{{ __('messages.slug_auto_help') }}</p>
              @error('slug')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.seo_title') }}</label>
              <input id="m_meta_title" name="meta_title" type="text" class="input-primary" maxlength="255" placeholder="{{ __('messages.seo_title') }}">
              @error('meta_title')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.sort_order') }}</label>
              <input id="m_sort_order" name="sort_order" type="number" min="0" class="input-primary" value="0">
              @error('sort_order')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.parent_category') }}</label>
              <select id="m_parent_id" name="parent_id" class="input-primary">
                <option value="">{{ __('messages.none') }}</option>
                @isset($allCategories)
                @foreach($allCategories as $pc)
                  <option value="{{ $pc->id }}">{{ $pc->name }}</option>
                @endforeach
                @endisset
              </select>
              @error('parent_id')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="md:col-span-2">
              <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.seo_description') }}</label>
              <textarea id="m_meta_description" name="meta_description" rows="3" class="input-primary" maxlength="500" placeholder="{{ __('messages.seo_description') }}"></textarea>
              @error('meta_description')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="md:col-span-2">
              <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                <input id="m_is_active" type="checkbox" name="is_active" value="1" class="rounded border-gray-300"> {{ __('messages.active') }}
              </label>
            </div>
          </div>
          <div class="space-y-3">
            <div class="flex items-center gap-2">
              <button type="button" class="px-3 py-2 bg-blue-600 text-white text-sm rounded" id="m_add_url">{{ __('messages.add_url') }}</button>
              <input type="file" accept="image/*" class="hidden" id="m_file_input">
              <button type="button" class="px-3 py-2 bg-white border border-gray-300 rounded text-sm" id="m_upload_btn">{{ __('messages.upload_file') }}</button>
              <p class="text-xs text-gray-500 ml-2">{{ __('messages.select_one_image') }}</p>
            </div>
            <div class="m_upload_zone upload-zone rounded-lg p-4 text-center">
              <div class="space-y-2">
                <svg class="mx-auto h-10 w-10 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48"><path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                <div class="text-sm text-gray-600"><span class="font-medium">{{ __('messages.drag_drop_image') }}</span></div>
                <label class="inline-block"><span class="px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 cursor-pointer">{{ __('messages.choose_file') }}</span><input type="file" accept="image/*" class="hidden" id="m_zone_file"></label>
              </div>
            </div>
            <div id="m_images_preview" class="grid grid-cols-6 gap-2"></div>
            <input type="hidden" id="m_primary_image_url" name="primary_image_url">
            <div class="flex justify-end gap-3 pt-2 border-t">
              <button type="button" id="m_close2" class="px-3 py-2 rounded-lg border border-slate-300 text-slate-700">{{ __('messages.cancel') }}</button>
              <button type="submit" class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">{{ __('messages.save_category') }}</button>
            </div>
          </div>
        </form>
        </div>
      </div>
     </div>

     <div class="bg-white rounded-xl shadow-sm border border-gray-100">
      <div class="flex items-center justify-between p-6 border-b border-gray-100">
       <div class="flex items-center justify-between mb-4">
         <h3 class="text-xl font-semibold text-slate-900">{{ __('messages.category_list') }}</h3>
         <div class="flex items-center gap-3">
           <input id="q" type="search" placeholder="{{ __('messages.search_by_name') }}" class="input-primary w-48">
           <button type="button" id="openCreateCategoryBtn" class="px-3 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">{{ __('messages.add_category') }}</button>
         </div>
       </div>
     </div>
      <div class="overflow-hidden rounded-lg border border-slate-200">
       <table class="min-w-full text-sm">
        <thead class="bg-slate-50 text-slate-700">
         <tr>
          <th class="px-4 py-2">{{ __('messages.image') }}</th>
          <th class="text-left px-4 py-2">{{ __('messages.name') }}</th>
          <th class="text-left px-4 py-2">Slug</th>
          <th class="text-left px-4 py-2">{{ __('messages.seo_title') }}</th>
          <th class="text-left px-4 py-2">{{ __('messages.sort_order') }}</th>
          <th class="text-left px-4 py-2">{{ __('messages.status') }}</th>
          <th class="text-left px-4 py-2">{{ __('messages.actions') }}</th>
         </tr>
        </thead>
        <tbody id="listBody" class="divide-y divide-slate-200">
         @forelse($categories as $c)
         <tr class="table-row border-b border-gray-50">
          <td class="px-6 py-3">
            <div class="w-14 h-14 rounded-lg overflow-hidden bg-slate-100 border border-slate-200">
              @php $primary = $c->image_url ?? null; @endphp
              @if($primary)
                <img src="{{ $primary }}" alt="" class="w-full h-full object-cover">
              @else
                <div class="w-full h-full flex items-center justify-center text-slate-400">{{ __('messages.not_available') }}</div>
              @endif
            </div>
          </td>
          <td class="px-6 py-3 text-gray-900 font-medium">{{ $c->name }}</td>
          <td class="px-6 py-3 text-gray-600">{{ $c->slug }}</td>
          <td class="px-6 py-3 text-gray-600">{{ $c->meta_title }}</td>
          <td class="px-6 py-3 text-gray-600">{{ $c->sort_order ?? 0 }}</td>
          <td class="px-6 py-3">
           <span class="badge {{ ($c->is_active ?? true) ? 'bg-green-50 text-green-700' : 'bg-gray-100 text-gray-700' }}">{{ ($c->is_active ?? true) ? __('messages.active') : __('messages.hidden') }}</span>
          </td>
          <td class="px-6 py-3">
           <div class="flex items-center gap-2">
            <button type="button" class="px-3 py-1.5 bg-gray-100 text-gray-800 rounded-lg open-edit"
              data-id="{{ $c->id }}"
              data-name="{{ $c->name }}"
              data-slug="{{ $c->slug }}"
              data-parent_id="{{ $c->parent_id }}"
              data-sort_order="{{ $c->sort_order }}"
              data-is_active="{{ (int)($c->is_active ?? 1) }}"
              data-meta_title="{{ $c->meta_title }}"
              data-meta_description="{{ $c->meta_description }}"
              data-image="{{ $c->image_url ?? '' }}"
            >{{ __('messages.edit') }}</button>
            <form method="post" action="{{ route('admin.products.categories.destroy', $c->id) }}" onsubmit="return confirm('{{ __('messages.confirm_delete_category') }}');" class="inline">
              @csrf
              @method('DELETE')
              <button type="submit" class="px-3 py-1.5 bg-red-100 text-red-700 rounded-lg">{{ __('messages.delete') }}</button>
            </form>
           </div>
          </td>
         </tr>
         @empty
         <tr>
          <td class="px-6 py-6 text-gray-500" colspan="7">{{ __('messages.no_categories_found') }}</td>
         </tr>
        @endforelse
       </tbody>
       </table>
      </div>
      <div class="mt-4">@include('admin.partials.pagination', ['paginator' => $categories, 'itemsPerPage' => $categories->perPage()])</div>
     </div>
    </div>
   </main>
  </div>
  <script>
    window.toggleSubmenu = function(id){
      const submenu=document.getElementById(id+'-submenu');
      const arrow=document.getElementById(id+'-arrow');
      if(!submenu||!arrow)return;
      submenu.classList.toggle('hidden');
      arrow.classList.toggle('rotate-180');
    };
    const modal=document.getElementById('createCategoryModal');
    const openBtn=document.getElementById('openCreateCategoryBtn');
    const closeBtn=document.getElementById('m_close');
    const closeBtn2=document.getElementById('m_close2');
    const form=document.getElementById('m_form');
    function showModal(){modal.classList.remove('hidden');}
    function closeModal(){modal.classList.add('hidden');}
    function slugify(str){return (str||'').toString().normalize('NFD').replace(/[\u0300-\u036f]/g,'').replace(/đ/g,'d').replace(/Đ/g,'D').toLowerCase().replace(/[^a-z0-9\s-]/g,'').trim().replace(/\s+/g,'-').replace(/-+/g,'-');}
    const m_name=document.getElementById('m_name');
    const m_slug=document.getElementById('m_slug');
    const m_genSlug=document.getElementById('m_genSlug');
    const m_sort=document.getElementById('m_sort_order');
    const m_parent=document.getElementById('m_parent_id');
    const m_active=document.getElementById('m_is_active');
    const m_img=document.getElementById('m_primary_image_url');
    const m_prev=document.getElementById('m_images_preview');
    const m_add_url=document.getElementById('m_add_url');
    const m_file_input=document.getElementById('m_file_input');
    const m_zone_file=document.getElementById('m_zone_file');
    const m_upload_btn=document.getElementById('m_upload_btn');
    const m_zone=document.querySelector('.m_upload_zone');
    if(openBtn){openBtn.addEventListener('click',()=>{form.setAttribute('action','{{ route('admin.products.categories.store') }}');const old=form.querySelector('input[name=\"_method\"]');if(old)old.remove();m_name.value='';m_slug.value='';m_sort.value=0;m_parent.value='';m_active.checked=true;const mt=document.getElementById('m_meta_title');const md=document.getElementById('m_meta_description');if(mt)mt.value='';if(md)md.value='';m_img.value='';m_prev.innerHTML='';showModal();});}
    if(closeBtn){closeBtn.addEventListener('click',closeModal);}if(closeBtn2){closeBtn2.addEventListener('click',closeModal);}modal&&modal.addEventListener('click',e=>{if(e.target===modal)closeModal();});
    if(m_genSlug){m_genSlug.addEventListener('click',()=>{m_slug.value=slugify(m_name.value||m_slug.value);});}
    function renderPreview(url){
      m_prev.innerHTML='';
      if(!url) return;
      const card=document.createElement('div');
      card.className='relative group rounded-lg overflow-hidden border border-gray-200 bg-gray-50';
      const box=document.createElement('div');
      box.className='aspect-square relative';
      const img=document.createElement('img');
      img.src=url;
      img.className='w-full h-full object-cover';
      img.onerror=function(){
        img.remove();
        box.innerHTML='<div class="flex items-center justify-center h-full bg-gray-200 text-gray-400">{{ __('messages.failed') }}</div>';
      };
      const rmBtn=document.createElement('button');
      rmBtn.type='button';
      rmBtn.className='absolute top-2 right-2 p-1.5 bg-red-600 text-white rounded-full opacity-0 group-hover:opacity-100 transition-opacity';
      rmBtn.innerHTML='<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>';
      rmBtn.addEventListener('click',()=>{m_prev.innerHTML='';m_img.value='';});
      box.appendChild(img);
      box.appendChild(rmBtn);
      card.appendChild(box);
      m_prev.appendChild(card);
    }
    async function uploadFile(file){const fd=new FormData();fd.append('file',file);try{const resp=await fetch('{{ route('admin.products.categories.upload_image') }}',{method:'POST',credentials:'same-origin',headers:{'Accept':'application/json','X-Requested-With':'XMLHttpRequest','X-CSRF-TOKEN':'{{ csrf_token() }}'},body:fd});const data=await resp.json();if(data&&data.url){m_img.value=data.url;renderPreview(data.url);}}catch(e){alert('Upload ảnh thất bại');}}
    async function addImageFromUrl(url){try{const resp=await fetch('{{ route('admin.products.categories.upload_image') }}',{method:'POST',credentials:'same-origin',headers:{'Accept':'application/json','Content-Type':'application/json','X-Requested-With':'XMLHttpRequest','X-CSRF-TOKEN':'{{ csrf_token() }}'},body:JSON.stringify({url})});const data=await resp.json();if(data&&data.url){m_img.value=data.url;renderPreview(data.url);}}catch(e){alert('Tải ảnh từ URL thất bại');}}
    if(m_add_url){m_add_url.addEventListener('click',()=>{const u=prompt('Nhập URL ảnh');if(u&&u.trim()){addImageFromUrl(u.trim());}});}
    if(m_upload_btn){m_upload_btn.addEventListener('click',()=>{m_file_input.click();});}
    if(m_file_input){m_file_input.addEventListener('change',e=>{if(e.target.files[0])uploadFile(e.target.files[0]);});}
    if(m_zone_file){m_zone_file.addEventListener('change',e=>{if(e.target.files[0])uploadFile(e.target.files[0]);});}
    
    document.querySelectorAll('.open-edit').forEach(btn=>{
        btn.addEventListener('click', ()=>{
            const id=btn.dataset.id;
            form.setAttribute('action', '{{ url('/admin/products/categories') }}/'+id);
            
            const old=form.querySelector('input[name="_method"]');
            if(old) old.remove();
            const m = document.createElement('input');
            m.type='hidden'; m.name='_method'; m.value='PUT';
            form.appendChild(m);

            m_name.value=btn.dataset.name;
            m_slug.value=btn.dataset.slug;
            m_parent.value=btn.dataset.parent_id||'';
            m_sort.value=btn.dataset.sort_order;
            m_active.checked=btn.dataset.is_active==='1';
            
            const mt=document.getElementById('m_meta_title');
            const md=document.getElementById('m_meta_description');
            if(mt) mt.value=btn.dataset.meta_title||'';
            if(md) md.value=btn.dataset.meta_description||'';

            const img=btn.dataset.image;
            m_img.value=img;
            renderPreview(img);

            showModal();
        });
    });
  </script>
 </body>
</html>
