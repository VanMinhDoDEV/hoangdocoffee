<!doctype html>
<html lang="{{ app()->getLocale() }}">
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
    <div class="p-8 space-y-6">
      <div class="flex items-center justify-between">
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-50 border border-gray-200">
          <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
          <div class="text-sm text-gray-600">{{ ($attributes->count() ?? 0) }} {{ __('messages.attribute_count_suffix') }}</div>
        </div>
        <button type="button" onclick="openCreateAttributeModal()" class="px-3 py-2 rounded bg-blue-600 text-white text-sm">+ {{ __('messages.add_attribute_group') }}</button>
      </div>

      <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <div class="xl:col-span-1 space-y-6">
          <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 attribute-card animate-slide-in">
            <div class="mb-3 text-lg font-semibold text-gray-900">{{ __('messages.attribute_groups') }}</div>
            <div class="space-y-2">
              @forelse($attributes as $a)
                <a href="{{ route('admin.products.attributes', ['attribute_id' => $a->id]) }}" class="block">
                  <div class="flex items-center justify-between p-3 rounded-lg border {{ ($currentAttribute && $currentAttribute->id === $a->id) ? 'border-blue-500 bg-blue-50' : 'border-gray-200 bg-white' }}">
                    <div class="font-medium text-gray-900">{{ $a->name }}</div>
                    <div class="flex items-center gap-2">
                      <button type="button" onclick="deleteAttribute({{ $a->id }})" class="px-2 py-1 text-xs rounded bg-red-50 text-red-600 border border-red-200">{{ __('messages.delete') }}</button>
                    </div>
                  </div>
                </a>
              @empty
                <p class="text-sm text-gray-500">{{ __('messages.no_attribute_groups') }}</p>
              @endforelse
            </div>
          </div>
        </div>

        <div class="xl:col-span-2 space-y-6">
          <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 attribute-card animate-slide-in">
            <div class="flex items-center justify-between mb-4">
              <div class="flex items-center gap-3">
                <h2 class="text-lg font-semibold text-gray-900">{{ __('messages.attribute_group_values') }} @if($currentAttribute) <span class="text-gray-500">— {{ $currentAttribute->name }}</span> @endif</h2>
                @if($currentAttribute)
                  <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-gray-100 text-gray-700 text-xs font-semibold rounded-md uppercase tracking-wide">
                    {{ ($currentAttribute->type === 'color') ? __('messages.color') : __('messages.text') }}
                  </span>
                @endif
              </div>
              @if($currentAttribute)
              <div class="flex items-center gap-2">
                <select id="attr-type" onchange="updateAttributeType({{ $currentAttribute->id }}, this.value)" class="input-primary w-auto">
                  <option value="text" {{ ($currentAttribute->type === 'text') ? 'selected' : '' }}>{{ __('messages.text') }}</option>
                  <option value="color" {{ ($currentAttribute->type === 'color') ? 'selected' : '' }}>{{ __('messages.color') }}</option>
                </select>
              </div>
              @endif
            </div>
            @if($currentAttribute)
              <div class="mb-4">
                <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase">{{ __('messages.add_value') }}</label>
                <div class="flex gap-2 items-center">
                  @if($currentAttribute->type === 'color')
                  <div class="color-picker-wrapper">
                    <input type="color" value="#3b82f6" onchange="addColorValue({{ $currentAttribute->id }}, this.value)">
                  </div>
                  @endif
                  <input type="text" id="value-input" class="input-primary flex-1" placeholder="{{ ($currentAttribute->type === 'color') ? __('messages.enter_color_or_value') : __('messages.enter_value') }}">
                  <button onclick="addValue({{ $currentAttribute->id }})" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                  </button>
                </div>
              </div>
              <div class="flex flex-wrap gap-2">
                @forelse($values as $v)
                  @php $val = (string)$v->value; $isColor = ($currentAttribute->type === 'color') && (strlen($val) >= 4 && $val[0] === '#'); @endphp
                  @if($isColor)
                    <div class="value-tag inline-flex items-center gap-2 px-3 py-1.5 bg-white border-2 rounded-lg font-medium text-gray-700" style="border-color: {{ $val }};">
                      <div class="w-4 h-4 rounded border border-gray-200" style="background: {{ $val }};"></div>
                      {{ strtoupper($val) }}
                      <button onclick="deleteAttributeValue({{ $v->id }})" class="text-gray-400 hover:text-gray-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                      </button>
                    </div>
                  @else
                    <span class="value-tag inline-flex items-center gap-2 px-3 py-1.5 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium border border-gray-200">
                      {{ $val }}
                      <button onclick="deleteAttributeValue({{ $v->id }})" class="text-gray-400 hover:text-gray-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                      </button>
                    </span>
                  @endif
                @empty
                  <p class="text-sm text-gray-500">{{ __('messages.no_values') }}</p>
                @endforelse
              </div>
            @else
              <p class="text-sm text-gray-500">{{ __('messages.select_attribute_group') }}</p>
            @endif
          </div>
        </div>
      </div>
    </div>
  </main>
 </div>
  <div id="modal-create-attribute" class="fixed inset-0 bg-black bg-opacity-40 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 w-full max-w-md p-6">
      <div class="flex items-center justify-between mb-4"><h3 class="text-lg font-semibold text-gray-900">{{ __('messages.add_attribute_group') }}</h3><button onclick="closeModal('modal-create-attribute')" class="text-gray-500">✕</button></div>
      <div class="space-y-3">
        <div><label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.group_name') }}</label><input id="ca-name" type="text" class="input-primary" placeholder="{{ __('messages.example_size') }}"></div>
        <div><label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.data_type') }}</label><select id="ca-type" class="input-primary"><option value="text">{{ __('messages.text') }}</option><option value="color">{{ __('messages.color') }}</option></select></div>
        <button onclick="submitCreateAttribute()" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg font-medium">{{ __('messages.save') }}</button>
      </div>
    </div>
  </div>
  <script>
    function toggleSubmenu(id){const submenu=document.getElementById(id+'-submenu');const arrow=document.getElementById(id+'-arrow');if(!submenu||!arrow)return;submenu.classList.toggle('hidden');arrow.classList.toggle('rotate-180');}
    const csrfToken = '{{ csrf_token() }}';
    function openModal(id){ const el=document.getElementById(id); if(el){ el.classList.remove('hidden'); el.classList.add('flex'); } }
    function closeModal(id){ const el=document.getElementById(id); if(el){ el.classList.add('hidden'); el.classList.remove('flex'); } }
    function openCreateAttributeModal(){ openModal('modal-create-attribute'); }

    async function submitCreateAttribute(){
      const name = document.getElementById('ca-name').value.trim();
      if(!name){ alert('{{ __('messages.please_enter_group_name') }}'); return; }
      const type = document.getElementById('ca-type').value || 'text';
      const res = await fetch('{{ route('admin.products.attributes.store') }}', { method:'POST', headers:{ 'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN': csrfToken }, body: JSON.stringify({ action:'create_attribute', name, type }) });
      if(res.ok){ closeModal('modal-create-attribute'); window.location.href='{{ route('admin.products.attributes') }}'; } else { alert('{{ __('messages.error_creating_group') }}'); }
    }
    async function deleteAttribute(id){
      if(!confirm('{{ __('messages.confirm_delete_group') }}')) return;
      const res = await fetch('{{ route('admin.products.attributes.store') }}', { method:'POST', headers:{ 'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN': csrfToken }, body: JSON.stringify({ action:'delete_attribute', attribute_id: id }) });
      if(res.ok){ window.location.href='{{ route('admin.products.attributes') }}'; } else { alert('{{ __('messages.error_deleting_group') }}'); }
    }
    async function addValue(attribute_id){
      const input = document.getElementById('value-input');
      const value = (input && input.value || '').trim();
      if(!attribute_id || !value){ alert('{{ __('messages.please_enter_value') }}'); return; }
      const res = await fetch('{{ route('admin.products.attributes.store') }}', { method:'POST', headers:{ 'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN': csrfToken }, body: JSON.stringify({ action:'create_attribute_value', attribute_id, value }) });
      if(res.ok){ input.value=''; window.location.reload(); } else { alert('{{ __('messages.error_adding_value') }}'); }
    }
    async function addColorValue(attribute_id, color){
      const input = document.getElementById('value-input');
      const value = (color || '').trim();
      if(input){ input.value = value; input.focus(); }
    }
    async function deleteAttributeValue(id){
      if(!confirm('{{ __('messages.confirm_delete_value') }}')) return;
      const res = await fetch('{{ route('admin.products.attributes.store') }}', { method:'POST', headers:{ 'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN': csrfToken }, body: JSON.stringify({ action:'delete_attribute_value', attribute_value_id: id }) });
      if(res.ok){ window.location.reload(); } else { alert('{{ __('messages.error_deleting_value') }}'); }
    }
    async function updateAttributeType(attribute_id, type){
      const res = await fetch('{{ route('admin.products.attributes.store') }}', { method:'POST', headers:{ 'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN': csrfToken }, body: JSON.stringify({ action:'update_attribute', attribute_id, type }) });
      if(res.ok){ window.location.reload(); } else { alert('{{ __('messages.error_updating_type') }}'); }
    }
  </script>
 </body>
</html>
