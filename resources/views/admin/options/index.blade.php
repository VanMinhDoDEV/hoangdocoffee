<!doctype html>
<html lang="vi">
 <head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ __('messages.product_attributes') }}</title>
  <link rel="shortcut icon" type="image/x-icon" href="{{ !empty($storeSettings['favicon']) ? asset($storeSettings['favicon']) : asset('assets/images/favicon.png') }}">
  <link rel="icon" type="image/png" href="{{ !empty($storeSettings['favicon']) ? asset($storeSettings['favicon']) : asset('assets/images/favicon.png') }}">
  @vite(['resources/css/app.css','resources/js/app.js'])
  <style>
    .nav-link{transition:all .2s ease}
    .nav-link:hover{background-color:rgba(255,255,255,.1)}
    .nav-link.active{background-color:rgba(255,255,255,.15);border-left:3px solid #3498db}
    .btn{transition:all .2s ease}
    .btn:hover{filter:brightness(1.05)}
    .help{font-size:.75rem}
    .attribute-card{transition:all .2s ease}
    .attribute-card:hover{box-shadow:0 4px 6px -1px rgba(0,0,0,.1),0 2px 4px -1px rgba(0,0,0,.06)}
    .value-tag{transition:all .2s ease}
    .value-tag:hover{transform:translateY(-1px)}
    .color-picker-wrapper{position:relative;width:42px;height:42px;cursor:pointer;border-radius:8px;overflow:hidden;border:2px solid #e5e7eb;transition:all .2s ease}
    .color-picker-wrapper:hover{border-color:#3b82f6}
    .color-picker-wrapper input[type="color"]{position:absolute;width:200%;height:200%;top:-50%;left:-50%;border:none;cursor:pointer}
    @keyframes slideIn{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:translateY(0)}}
    .animate-slide-in{animation:slideIn .3s ease-out}
  </style>
 </head>
 <body class="bg-gray-50">
  <div class="w-full min-h-screen flex">
  @include('admin.partials.sidebar')
  <main class="flex-1 overflow-auto md:ml-64 transition-all duration-300">
    @include('admin.partials.topbar', ['title' => __('messages.product_attributes')])
    <div class="p-8">
     @if(session('status'))
      <div class="mb-4 p-3 rounded-lg bg-green-50 text-green-700">{{ session('status') }}</div>
     @endif

     <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-3">
      <div class="flex items-center gap-3 p-3 rounded-lg border {{ $currentSet ? 'border-blue-500 bg-blue-50' : 'border-gray-200 bg-white' }}">
       <div class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-semibold {{ $currentSet ? 'bg-blue-600 text-white' : 'bg-gray-300 text-gray-700' }}">1</div>
       <div>
        <div class="text-sm font-semibold {{ $currentSet ? 'text-blue-700' : 'text-gray-800' }}">{{ __('messages.select_attribute_group') }}</div>
        <div class="text-xs text-gray-500">{{ __('messages.attribute_group_semantic') }}</div>
       </div>
      </div>
      <div class="flex items-center gap-3 p-3 rounded-lg border {{ $currentOption ? 'border-blue-500 bg-blue-50' : ($currentSet ? 'border-gray-200 bg-white' : 'border-gray-200 bg-gray-50') }}">
       <div class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-semibold {{ $currentOption ? 'bg-blue-600 text-white' : ($currentSet ? 'bg-gray-300 text-gray-700' : 'bg-gray-200 text-gray-600') }}">2</div>
       <div>
        <div class="text-sm font-semibold {{ $currentOption ? 'text-blue-700' : 'text-gray-800' }}">{{ __('messages.select_attribute_type') }}</div>
        <div class="text-xs text-gray-500">{{ __('messages.in_selected_group') }}</div>
       </div>
      </div>
      <div class="flex items-center gap-3 p-3 rounded-lg border {{ $currentOption ? 'border-blue-500 bg-blue-50' : 'border-gray-200 bg-white' }}">
       <div class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-semibold {{ $currentOption ? 'bg-blue-600 text-white' : 'bg-gray-300 text-gray-700' }}">3</div>
       <div>
        <div class="text-sm font-semibold {{ $currentOption ? 'text-blue-700' : 'text-gray-800' }}">{{ __('messages.manage_values') }}</div>
        <div class="text-xs text-gray-500">{{ __('messages.add_sort_status') }}</div>
       </div>
      </div>
     </div>

     <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
      <div class="xl:col-span-1 space-y-6">
       <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('messages.attribute_groups') }}</h2>
        <div class="flex items-center justify-between mb-3">
         <div class="text-sm text-gray-500">{{ __('messages.created_groups_list') }}</div>
         <button type="button" onclick="openCreateSetModal()" class="inline-flex items-center gap-1 px-2 py-1 rounded bg-blue-600 text-white text-xs"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg> {{ __('messages.add_group') }}</button>
        </div>
        <div class="space-y-2">
         @forelse($sets as $s)
          <div class="rounded-lg border {{ (isset($currentSet) && $currentSet && $currentSet->id === $s->id) ? 'border-blue-500 bg-blue-50 text-blue-700' : 'border-gray-200 bg-white text-gray-700' }}">
            <a href="{{ route('admin.products.attributes', ['set_id' => $s->id]) }}" class="block px-3 py-2">
              <div class="flex items-center justify-between">
                <span class="font-medium">{{ $s->name }}</span>
                <span class="text-xs text-gray-500">{{ $s->code }}</span>
              </div>
            </a>
            <div class="flex items-center gap-2 px-3 pb-3">
              <button type="button" class="text-xs px-2 py-1 rounded border border-gray-200" onclick="openEditSetModal({{ $s->id }}, '{{ addslashes($s->code) }}', '{{ addslashes($s->name) }}', '{{ addslashes($s->description ?? '') }}', {{ $s->is_active ? 1 : 0 }})">{{ __('messages.edit') }}</button>
              <button type="button" class="text-xs px-2 py-1 rounded bg-red-600 text-white" onclick="confirmDeleteSet({{ $s->id }})">{{ __('messages.delete') }}</button>
            </div>
          </div>
         @empty
          <p class="text-gray-500 text-sm">{{ __('messages.no_attribute_groups') }}</p>
         @endforelse
        </div>
       </div>
       
      </div>

      <div class="xl:col-span-2 space-y-6">
       <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
         <h2 class="text-lg font-semibold text-gray-900">{{ __('messages.attribute_types') }} @if($currentSet) <span class="text-gray-500">— {{ $currentSet->name }}</span> @endif</h2>
         @if($currentSet)
         <button type="button" onclick="openCreateOptionModal()" class="inline-flex items-center gap-1 px-3 py-1.5 rounded bg-blue-600 text-white text-sm"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg> {{ __('messages.add_type') }}</button>
         @endif
        </div>
        @if($currentSet)
         <div class="space-y-4">
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-3">
           @forelse($currentSet->options as $opt)
            <div class="px-3 py-2 rounded-lg border {{ (isset($currentOption) && $currentOption && $currentOption->id === $opt->id) ? 'border-blue-500 bg-blue-50 text-blue-700' : 'border-gray-200 bg-white text-gray-700' }}" data-opt-search="{{ strtolower($opt->name.' '.$opt->code) }}">
              <div class="flex items-center justify-between">
               <span class="font-medium">{{ $opt->name }}</span>
               <div class="flex items-center gap-2">
                <span class="text-xs text-gray-500">{{ $opt->code }}</span>
               </div>
              </div>
              <div class="mt-2 flex items-center gap-2">
               <button type="button" class="text-xs px-2 py-1 rounded border border-gray-200" onclick="openEditOptionModal({{ $opt->id }}, '{{ addslashes($opt->code) }}', '{{ addslashes($opt->name) }}', {{ $opt->is_active ? 1 : 0 }})">{{ __('messages.edit') }}</button>
               <button type="button" class="text-xs px-2 py-1 rounded bg-red-600 text-white" onclick="confirmDeleteOption({{ $opt->id }})">{{ __('messages.delete') }}</button>
              </div>
            </div>
           @empty
            <p class="text-gray-500 text-sm">{{ __('messages.no_attribute_types_in_group') }}</p>
           @endforelse
          </div>
         </div>
        @else
        <p class="text-gray-500 text-sm">{{ __('messages.create_group_hint') }}</p>
        @endif
       </div>

       <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hidden">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('messages.create_new_attribute') }}</h2>
        <form method="post" action="{{ route('admin.products.attributes.store') }}" class="space-y-3">
         @csrf
         <input type="hidden" name="action" value="create_option" />
         <div><label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.type_code') }}</label><input name="code" type="text" placeholder="color" class="input-primary" required></div>
         <div><label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.display_name') }}</label><input name="name" type="text" placeholder="{{ __('messages.example_size') }}" class="input-primary" required></div>
         <div class="grid grid-cols-2 gap-3">
          <div><label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.type') }}</label><select name="type" class="input-primary"><option value="select">Select</option><option value="text">Text</option></select></div>
          <div><label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.sort_order') }}</label><input name="sort_order" type="number" min="0" value="0" class="input-primary"></div>
         </div>
         <label class="inline-flex items-center"><input type="checkbox" name="is_active" value="1" checked class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"><span class="ml-2 text-sm text-gray-700">{{ __('messages.activate') }}</span></label>
         <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ __('messages.create_attribute_btn') }}</button>
        </form>
       </div>

       <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hidden">
       @if($currentOption)
         <div class="flex items-center justify-between mb-4">
           <h2 class="text-lg font-semibold text-gray-900">{{ __('messages.values_of') }} {{ $currentOption->name }} ({{ $currentOption->code }})</h2>
           <button type="button" onclick="openCreateValueModal()" class="inline-flex items-center gap-1 px-3 py-1.5 rounded bg-blue-600 text-white text-sm"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg> {{ __('messages.add_value') }}</button>
         </div>
       @else
         <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('messages.values_of') }} @if($currentOption) {{ $currentOption->name }} ({{ $currentOption->code }}) @else {{ __('messages.product_attributes') }} @endif</h2>
       @endif
       @if($currentOption)
         <div class="space-y-3">
          <div id="option-values" class="flex flex-wrap gap-2">
           @forelse($values as $val)
            @php $isColor = is_string($val->value) && \Illuminate\Support\Str::startsWith($val->value, '#'); @endphp
            @if($isColor)
             <div class="value-tag inline-flex items-center gap-2 px-3 py-1.5 bg-white border-2 rounded-lg font-medium text-gray-700 animate-slide-in" style="border-color: {{ $val->value }};">
              <div class="w-4 h-4 rounded border border-gray-200" style="background: {{ $val->value }};"></div>
              <span class="text-xs">{{ \Illuminate\Support\Str::upper($val->value) }}</span>
              <span class="ml-2 text-xs px-2 py-0.5 rounded-full {{ $val->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">{{ $val->is_active ? 'Active' : 'Inactive' }}</span>
             </div>
            @else
             <span class="value-tag inline-flex items-center gap-2 px-3 py-1.5 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium border border-gray-200 animate-slide-in">
              {{ $val->value }}
              <span class="ml-2 text-xs px-2 py-0.5 rounded-full {{ $val->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">{{ $val->is_active ? 'Active' : 'Inactive' }}</span>
             </span>
            @endif
           @empty
            <p class="text-gray-500 text-sm">{{ __('messages.no_values') }}</p>
           @endforelse
          </div>
         </div>
      @else
        <p class="text-gray-500 text-sm">{{ __('messages.select_attribute_type') }}</p>
      @endif
      </div>
      </div>
     </div>
    </div>
  </main>
 </div>
  <!-- Create Option Modal -->
  <div id="modal-create-option" class="fixed inset-0 bg-black bg-opacity-40 hidden items-center justify-center z-50">
   <div class="bg-white rounded-lg shadow-sm border border-gray-200 w-full max-w-md p-6">
    <div class="flex items-center justify-between mb-4"><h3 class="text-lg font-semibold text-gray-900">Thêm loại thuộc tính</h3><button onclick="closeModal('modal-create-option')" class="text-gray-500">✕</button></div>
    <div class="space-y-3">
     <div><label class="block text-sm font-medium text-gray-700 mb-1">Chuẩn mã</label><select id="m-semantic" class="input-primary"><option value="custom">Tự đặt</option><option value="color">color</option><option value="size">size</option><option value="material">material</option><option value="pattern">pattern</option></select></div>
     <div><label class="block text-sm font-medium text-gray-700 mb-1">Mã loại (code)</label><input id="m-code" type="text" class="input-primary" placeholder="Ví dụ: color"></div>
     <div><label class="block text-sm font-medium text-gray-700 mb-1">Tên loại</label><input id="m-name" type="text" class="input-primary" placeholder="Ví dụ: Màu sắc"></div>
     <label class="inline-flex items-center"><input id="m-active" type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded" checked><span class="ml-2 text-sm text-gray-700">Kích hoạt</span></label>
     <button onclick="submitCreateOption()" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg font-medium">Lưu</button>
    </div>
   </div>
  </div>


  <!-- Create Set Modal -->
  <div id="modal-create-set" class="fixed inset-0 bg-black bg-opacity-40 hidden items-center justify-center z-50">
   <div class="bg-white rounded-lg shadow-sm border border-gray-200 w-full max-w-lg p-6">
    <div class="flex items-center justify-between mb-4"><h3 class="text-lg font-semibold text-gray-900">Thêm nhóm thuộc tính</h3><button onclick="closeModal('modal-create-set')" class="text-gray-500">✕</button></div>
    <div class="space-y-3">
     <div><label class="block text-sm font-medium text-gray-700 mb-1">Mã nhóm</label><input id="cs-code" type="text" class="input-primary" placeholder="Ví dụ: apparel_default"></div>
     <div><label class="block text-sm font-medium text-gray-700 mb-1">Tên nhóm</label><input id="cs-name" type="text" class="input-primary" placeholder="Ví dụ: Nhóm tiêu chuẩn"></div>
     <div><label class="block text-sm font-medium text-gray-700 mb-1">Mô tả</label><textarea id="cs-desc" rows="2" class="input-primary"></textarea></div>
     <label class="inline-flex items-center"><input id="cs-active" type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded" checked><span class="ml-2 text-sm text-gray-700">Kích hoạt</span></label>
     <button onclick="submitCreateSet()" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg font-medium">Lưu</button>
    </div>
   </div>
  </div>

  <!-- Edit Set Modal -->
  <div id="modal-edit-set" class="fixed inset-0 bg-black bg-opacity-40 hidden items-center justify-center z-50">
   <div class="bg-white rounded-lg shadow-sm border border-gray-200 w-full max-w-lg p-6">
    <div class="flex items-center justify-between mb-4"><h3 class="text-lg font-semibold text-gray-900">Sửa nhóm thuộc tính</h3><button onclick="closeModal('modal-edit-set')" class="text-gray-500">✕</button></div>
    <div class="space-y-3">
     <input type="hidden" id="es-id">
     <div><label class="block text-sm font-medium text-gray-700 mb-1">Mã nhóm</label><input id="es-code" type="text" class="input-primary" placeholder="Ví dụ: apparel_default" readonly></div>
     <div><label class="block text-sm font-medium text-gray-700 mb-1">Tên nhóm</label><input id="es-name" type="text" class="input-primary" placeholder="Ví dụ: Nhóm tiêu chuẩn"></div>
     <div><label class="block text-sm font-medium text-gray-700 mb-1">Mô tả</label><textarea id="es-desc" rows="2" class="input-primary"></textarea></div>
     <label class="inline-flex items-center"><input id="es-active" type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded" checked><span class="ml-2 text-sm text-gray-700">Kích hoạt</span></label>
     <button onclick="submitUpdateSet()" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg font-medium">Lưu</button>
    </div>
   </div>
  </div>

  <!-- Edit Option Modal -->
  <div id="modal-edit-option" class="fixed inset-0 bg-black bg-opacity-40 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 w-full max-w-md p-6">
      <div class="flex items-center justify-between mb-4"><h3 class="text-lg font-semibold text-gray-900">Sửa loại thuộc tính</h3><button onclick="closeModal('modal-edit-option')" class="text-gray-500">✕</button></div>
      <div class="space-y-3">
        <input type="hidden" id="eo-id">
        <div><label class="block text-sm font-medium text-gray-700 mb-1">Mã loại (code)</label><input id="eo-code" type="text" class="input-primary" placeholder="Ví dụ: color"></div>
        <div><label class="block text-sm font-medium text-gray-700 mb-1">Tên loại</label><input id="eo-name" type="text" class="input-primary" placeholder="Ví dụ: Màu sắc"></div>
        <label class="inline-flex items-center"><input id="eo-active" type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded" checked><span class="ml-2 text-sm text-gray-700">Kích hoạt</span></label>
        <button onclick="submitUpdateOption()" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg font-medium">Lưu</button>
      </div>
    </div>
  </div>
  <!-- Create Value Modal -->
  <div id="modal-create-value" class="fixed inset-0 bg-black bg-opacity-40 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 w-full max-w-md p-6">
      <div class="flex items-center justify-between mb-4"><h3 class="text-lg font-semibold text-gray-900">Thêm giá trị</h3><button onclick="closeModal('modal-create-value')" class="text-gray-500">✕</button></div>
      <div class="space-y-3">
        <div><label class="block text-sm font-medium text-gray-700 mb-1">Tên giá trị</label><input id="mv-name" type="text" class="input-primary" placeholder="Ví dụ: Trắng"></div>
        @if($currentOption && $currentOption->code === 'color')
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Mã màu (HEX)</label>
          <div class="color-picker-wrapper"><input id="mv-color" type="color" value="#FFFFFF"></div>
          <p class="help text-gray-500 mt-1">Chọn màu, hệ thống dùng mã HEX làm giá trị</p>
        </div>
        @else
        <div><label class="block text-sm font-medium text-gray-700 mb-1">Giá trị</label><input id="mv-value" type="text" class="input-primary" placeholder="Ví dụ: Trắng"></div>
        @endif
        <label class="inline-flex items-center"><input id="mv-active" type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded" checked><span class="ml-2 text-sm text-gray-700">Kích hoạt</span></label>
        <button onclick="submitCreateValue()" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg font-medium">Lưu</button>
      </div>
    </div>
  </div>
  <script>
    function toggleSubmenu(id){const submenu=document.getElementById(id+'-submenu');const arrow=document.getElementById(id+'-arrow');if(!submenu||!arrow)return;submenu.classList.toggle('hidden');arrow.classList.toggle('rotate-180');}
    function setValueFromColor(color){var input=document.getElementById('attr-value-input');if(input){input.value=color;}}
    function clearOptionSearch(){var i=document.getElementById('option-search');if(i){i.value='';filterOptions('');}}
    function filterOptions(q){var s=(q||'').toLowerCase();var cards=document.querySelectorAll('[data-opt-search]');cards.forEach(function(el){var v=el.getAttribute('data-opt-search')||'';el.style.display=v.indexOf(s)>-1?'':'none';});}
    (function(){var i=document.getElementById('option-search');if(i){i.addEventListener('input',function(e){filterOptions(e.target.value);});}})();

    const csrfToken = '{{ csrf_token() }}';
    let currentManageOptionId = null;
    function openModal(id){var m=document.getElementById(id);if(m){m.classList.remove('hidden');m.classList.add('flex');}}
    function closeModal(id){var m=document.getElementById(id);if(m){m.classList.add('hidden');m.classList.remove('flex');}}
    function openCreateOptionModal(){openModal('modal-create-option');}
    function openCreateSetModal(){openModal('modal-create-set');}
    function openEditSetModal(id, code, name, desc, active){
      document.getElementById('es-id').value = id;
      document.getElementById('es-code').value = code;
      document.getElementById('es-name').value = name;
      document.getElementById('es-desc').value = desc;
      document.getElementById('es-active').checked = !!active;
      openModal('modal-edit-set');
    }
    function confirmDeleteSet(id){
      if(!confirm('Xóa nhóm này?')) return;
      fetch('{{ route('admin.products.attributes.store') }}', { method:'POST', headers:{ 'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN': csrfToken }, body: JSON.stringify({ action:'delete_set', set_id:id }) }).then(function(res){ if(res.ok){ window.location.href='{{ route('admin.products.attributes') }}'; } else { alert('Lỗi xóa nhóm'); } });
    }
    function openEditOptionModal(id, code, name, active){
      document.getElementById('eo-id').value = id;
      document.getElementById('eo-code').value = code;
      document.getElementById('eo-name').value = name;
      document.getElementById('eo-active').checked = !!active;
      openModal('modal-edit-option');
    }
    function confirmDeleteOption(id){
      if(!confirm('Xóa loại này?')) return;
      fetch('{{ route('admin.products.attributes.store') }}', { method:'POST', headers:{ 'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN': csrfToken }, body: JSON.stringify({ action:'delete_option', option_id:id }) }).then(function(res){ if(res.ok){ window.location.reload(); } else { alert('Lỗi xóa loại'); } });
    }
    function openCreateValueModal(){ openModal('modal-create-value'); }
    function submitCreateOption(){
      const semantic = document.getElementById('m-semantic').value || 'custom';
      const codeRaw = document.getElementById('m-code').value.trim();
      const name = document.getElementById('m-name').value.trim();
      const code = (semantic && semantic !== 'custom') ? semantic : codeRaw;
      if(!code || !name){ alert('Điền đủ mã và tên'); return; }
      fetch('{{ route('admin.products.attributes.store') }}',{ method:'POST', headers:{ 'Content-Type':'application/json', 'Accept':'application/json', 'X-CSRF-TOKEN': csrfToken }, body: JSON.stringify({ action:'create_option', code, name, sort_order:0, is_active:true, set_id: {{ $currentSet->id ?? 'null' }} }) }).then(function(res){ if(res.ok){ window.location.reload(); } else { alert('Lỗi tạo loại'); } });
    }
    function submitCreateSet(){
      const code = document.getElementById('cs-code').value.trim();
      const name = document.getElementById('cs-name').value.trim();
      const desc = document.getElementById('cs-desc').value.trim();
      const active = document.getElementById('cs-active').checked ? 1 : 0;
      if(!code || !name){ alert('Thiếu dữ liệu'); return; }
      fetch('{{ route('admin.products.attributes.store') }}', { method:'POST', headers:{ 'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN': csrfToken }, body: JSON.stringify({ action:'create_set', code, name, description:desc, is_active:active }) }).then(function(res){ if(res.ok){ window.location.reload(); } else { alert('Lỗi tạo nhóm'); } });
    }
    function submitUpdateSet(){
      const id = document.getElementById('es-id').value;
      const code = document.getElementById('es-code').value.trim();
      const name = document.getElementById('es-name').value.trim();
      const desc = document.getElementById('es-desc').value.trim();
      const active = document.getElementById('es-active').checked ? 1 : 0;
      if(!id || !code || !name){ alert('Thiếu dữ liệu'); return; }
      fetch('{{ route('admin.products.attributes.store') }}', { method:'POST', headers:{ 'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN': csrfToken }, body: JSON.stringify({ action:'update_set', set_id:id, code, name, description:desc, is_active:active }) }).then(function(res){ if(res.ok){ window.location.reload(); } else { alert('Lỗi cập nhật nhóm'); } });
    }
    function submitUpdateOption(){
      const id = document.getElementById('eo-id').value;
      const code = document.getElementById('eo-code').value.trim();
      const name = document.getElementById('eo-name').value.trim();
      const active = document.getElementById('eo-active').checked ? 1 : 0;
      if(!id || !code || !name){ alert('Thiếu dữ liệu'); return; }
      fetch('{{ route('admin.products.attributes.store') }}', { method:'POST', headers:{ 'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN': csrfToken }, body: JSON.stringify({ action:'update_option', option_id:id, code, name, is_active:active }) }).then(function(res){ if(res.ok){ window.location.reload(); } else { alert('Lỗi cập nhật loại'); } });
    }
    function submitCreateValue(){
      const name = document.getElementById('mv-name').value.trim();
      const active = document.getElementById('mv-active').checked ? 1 : 0;
      if(!name || !{{ $currentOption ? 1 : 0 }}){ alert('Chọn loại và nhập tên'); return; }
      fetch('{{ route('admin.products.attributes.store') }}', { method:'POST', headers:{ 'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN': csrfToken }, body: JSON.stringify({ action:'create_value', option_id: {{ $currentOption->id ?? 'null' }}, value: name, sort_order:0, is_active: active }) }).then(function(res){ if(res.ok){ window.location.reload(); } else { alert('Lỗi thêm giá trị'); } });
    }
  </script>
 </body>
</html>
