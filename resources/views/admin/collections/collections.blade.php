<!doctype html>
<html lang="{{ app()->getLocale() }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ __('messages.collections_title') }} • Admin</title>
  <meta name="description" content="{{ __('messages.collections_description') }}">
  <link rel="shortcut icon" type="image/x-icon" href="{{ !empty($storeSettings['favicon']) ? asset($storeSettings['favicon']) : asset('assets/images/favicon.png') }}">
  <link rel="icon" type="image/png" href="{{ !empty($storeSettings['favicon']) ? asset($storeSettings['favicon']) : asset('assets/images/favicon.png') }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css','resources/js/app.js'])
  <style>
    html,
    body {
      font-family: Inter, system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, Cantarell, Noto Sans, sans-serif
    }

    .card {
      border-radius: 16px;
      box-shadow: 0 10px 30px -12px rgba(0, 0, 0, .12)
    }

    .input {
      transition: border-color .2s, box-shadow .2s
    }

    .input:focus {
      outline: none;
      box-shadow: 0 0 0 3px rgba(59, 130, 246, .25);
      border-color: #3b82f6
    }
  </style>
</head>

<body class="bg-gray-50">
  <div class="w-full min-h-screen flex">
    @include('admin.partials.sidebar')
    <main class="flex-1 overflow-auto md:ml-64 transition-all duration-300">
      @include('admin.partials.topbar', ['title' => __('messages.collections_title')])
      <div class="p-8">
        @if(session('status'))
        <div role="status" class="mb-6 p-4 rounded-xl bg-emerald-50 text-emerald-700">{{ session('status') }}</div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-1 gap-8">
          <div class="card bg-white p-6 hidden" id="collectionCreateCard">
            <h2 class="text-xl font-semibold text-slate-900 mb-4">{{ __('messages.create_collection') }}</h2>
            <form action="{{ url('/admin/products/collections') }}" method="post" class="space-y-5" novalidate>
              @csrf
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-1" for="name">{{ __('messages.name') }}</label>
                <input id="name" name="name" type="text" class="input-primary" required aria-required="true" placeholder="{{ __('messages.example_collection_name') }}">
                @error('name')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
              </div>
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-1" for="slug">{{ __('messages.slug') }}</label>
                <div class="flex items-center gap-2">
                  <input id="slug" name="slug" type="text" class="input-primary" placeholder="{{ __('messages.example_collection_slug') }}" aria-describedby="slug-help">
                  <button type="button" id="genSlug" class="px-3 py-2 rounded-lg bg-slate-100 text-slate-700 hover:bg-slate-200">{{ __('messages.auto_generate') }}</button>
                </div>
                <p id="slug-help" class="text-xs text-slate-500 mt-1">{{ __('messages.slug_help') }}</p>
                @error('slug')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
              </div>

              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-slate-700 mb-1" for="meta_title">{{ __('messages.seo_title') }}</label>
                  <input id="meta_title" name="meta_title" type="text" class="input-primary" maxlength="255" placeholder="{{ __('messages.seo_title') }}">
                  <p class="text-xs text-slate-500 mt-1"><span id="mt-count">0</span>/60 khuyến nghị</p>
                  @error('meta_title')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                  <label class="block text-sm font-medium text-slate-700 mb-1" for="sort_order">{{ __('messages.sort_order') }}</label>
                  <input id="sort_order" name="sort_order" type="number" min="0" value="0" class="input-primary">
                </div>
              </div>

              <div>
                <label class="block text-sm font-medium text-slate-700 mb-1" for="meta_description">{{ __('messages.seo_description') }}</label>
                <textarea id="meta_description" name="meta_description" rows="3" class="input-primary" maxlength="500" placeholder="{{ __('messages.seo_description') }}"></textarea>
                <p class="text-xs text-slate-500 mt-1"><span id="md-count">0</span>/160 {{ __('messages.recommended') }}</p>
                @error('meta_description')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
              </div>

              <div class="flex items-center gap-2">
                <input id="is_active" name="is_active" type="checkbox" value="1" class="w-4 h-4 border-slate-300 rounded" checked>
                <label for="is_active" class="text-sm text-slate-700">{{ __('messages.active') }}</label>
              </div>

              <div class="border-t border-slate-200 pt-5">
                <h3 class="text-lg font-semibold text-slate-900 mb-3">{{ __('messages.collection_image') }}</h3>
                <input type="hidden" id="primaryCollectionImageUrlInput" name="primary_image_url">
                <div class="flex items-center gap-2 mb-3">
                  <button type="button" class="px-3 py-2 bg-blue-600 text-white text-sm rounded add-collection-image-url">{{ __('messages.add_url') }}</button>
                  <input type="file" accept="image/*" class="collection-file-input hidden">
                  <button type="button" class="px-3 py-2 bg-white border border-gray-300 rounded text-sm upload-collection-file">{{ __('messages.upload_file') }}</button>
                  <p class="text-xs text-gray-500 ml-2">{{ __('messages.select_one_image') }}</p>
                </div>
                <div class="collection-upload-zone upload-zone rounded-lg p-4 text-center">
                  <div class="space-y-2">
                    <svg class="mx-auto h-10 w-10 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                      <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                    <div class="text-sm text-gray-600"><span class="font-medium">{{ __('messages.drag_drop_image') }}</span></div>
                    <!-- <p class="text-xs text-gray-500">hoặc</p> -->
                    <!-- <label class="inline-block"><span class="px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 cursor-pointer">{{ __('messages.choose_file') }}</span><input type="file" accept="image/*" class="hidden collection-zone-file"></label> -->
                  </div>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 mt-2 collection-images-preview"></div>
              </div>

              <div class="flex justify-end gap-3 pt-2">
                <button type="submit" class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">{{ __('messages.save_collection') }}</button>
              </div>
            </form>
          </div>

          <div class="card bg-white p-6">
            <div class="flex items-center justify-between mb-4">
              <h2 class="text-xl font-semibold text-slate-900">{{ __('messages.collection_list') }}</h2>
              <div class="flex items-center gap-3">
                <input id="q" type="search" placeholder="{{ __('messages.search_by_name') }}" class="input-primary w-48">
                <button type="button" id="openCreateModal" class="px-3 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">{{ __('messages.add_collection') }}</button>
              </div>
            </div>
            <div class="overflow-hidden rounded-lg border border-slate-200">
              <table class="min-w-full text-sm">
                <thead class="bg-slate-50 text-slate-700">
                  <tr>
                    <th class="px-4 py-2">{{ __('messages.image') }}</th>
                    <th class="text-left px-4 py-2">{{ __('messages.name') }}</th>
                    <th class="text-left px-4 py-2">{{ __('messages.slug') }}</th>
                    <th class="text-left px-4 py-2">{{ __('messages.seo_title') }}</th>
                    <th class="text-left px-4 py-2">{{ __('messages.status') }}</th>
                    <th class="text-left px-4 py-2">{{ __('messages.sort_order') }}</th>
                    <th class="text-left px-4 py-2">{{ __('messages.actions') }}</th>
                  </tr>
                </thead>
                <tbody id="listBody" class="divide-y divide-slate-200">
                  @foreach($collections as $c)
                  @php $primary = $c->image_url ?: ($c->images->first() ? $c->images->first()->url : null); @endphp
                  <tr>
                    <td class="px-4 py-2">
                      <div class="w-14 h-14 rounded-lg overflow-hidden bg-slate-100 border border-slate-200">
                        @if($primary)
                        <img src="{{ $primary }}" alt="" class="w-full h-full object-cover">
                        @else
                        <div class="w-full h-full flex items-center justify-center text-slate-400">N/A</div>
                        @endif
                      </div>
                    </td>
                    <td class="px-4 py-2 font-medium text-slate-900">{{ $c->name }}</td>
                    <td class="px-4 py-2 text-slate-600">{{ $c->slug }}</td>
                    <td class="px-4 py-2 text-slate-600">{{ $c->meta_title }}</td>
                    <td class="px-4 py-2">{{ $c->is_active ? __('messages.active') : __('messages.hidden') }}</td>
                    <td class="px-4 py-2">{{ $c->sort_order }}</td>
                    <td class="px-4 py-2">
                      <div class="flex items-center gap-2">
                        <button type="button" class="p-2 rounded-lg hover:bg-slate-100 btn-edit" data-id="{{ $c->id }}" data-name="{{ $c->name }}" data-slug="{{ $c->slug }}" data-meta_title="{{ $c->meta_title }}" data-meta_description="{{ $c->meta_description }}" data-sort_order="{{ $c->sort_order }}" data-is_active="{{ $c->is_active ? 1 : 0 }}" data-image="{{ $primary ?? '' }}" title="{{ __('messages.edit') }}">
                          <svg class="w-5 h-5 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5h2m-2 14h2m-9-5h16" />
                          </svg>
                        </button>
                        <form action="{{ route('admin.products.collections.destroy', $c->id) }}" method="post" onsubmit="return confirm('{{ __('messages.confirm_delete_collection') }}');">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="p-2 rounded-lg hover:bg-red-50" title="{{ __('messages.delete') }}">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22M8 7V5a2 2 0 012-2h4a2 2 0 012 2v2" />
                            </svg>
                          </button>
                        </form>
                      </div>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
            <div class="mt-4">{{ $collections->links() }}</div>
          </div>
        </div>
      </div>
    </main>
  </div>
  <!-- Modal tạo bộ sưu tập -->
  <div id="createCollectionModal" class="fixed inset-0 bg-black/40 hidden z-50">
    <div class="min-h-screen flex items-center justify-center p-4">
      <div class="w-full max-w-2xl bg-white rounded-2xl shadow-xl max-h-[85vh] overflow-y-auto">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200">
          <h3 class="text-lg font-semibold">{{ __('messages.create_collection') }}</h3>
          <button type="button" id="closeCreateModal" class="p-2 rounded-lg hover:bg-slate-100">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>
        <form action="{{ url('/admin/products/collections') }}" method="post" class="space-y-5 p-6" id="createCollectionForm" novalidate>
          @csrf
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1" for="m_name">{{ __('messages.name') }}</label>
            <input id="m_name" name="name" type="text" class="input-primary" required placeholder="{{ __('messages.example_collection_name') }}" value="{{ old('name') }}">
            @error('name')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1" for="m_slug">{{ __('messages.slug') }}</label>
            <div class="flex items-center gap-2">
              <input id="m_slug" name="slug" type="text" class="input-primary" placeholder="{{ __('messages.example_collection_slug') }}" aria-describedby="m-slug-help" value="{{ old('slug') }}">
              <button type="button" id="m_genSlug" class="px-3 py-2 rounded-lg bg-slate-100 text-slate-700 hover:bg-slate-200">{{ __('messages.auto_generate') }}</button>
            </div>
            <p id="m-slug-help" class="text-xs text-slate-500 mt-1">{{ __('messages.slug_help') }}</p>
            @error('slug')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
          </div>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1" for="m_meta_title">{{ __('messages.seo_title') }}</label>
              <input id="m_meta_title" name="meta_title" type="text" class="input-primary" maxlength="255" placeholder="{{ __('messages.seo_title') }}" value="{{ old('meta_title') }}">
              <p class="text-xs text-slate-500 mt-1"><span id="m-mt-count">0</span>/60 khuyến nghị</p>
              @error('meta_title')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1" for="m_sort_order">{{ __('messages.sort_order') }}</label>
              <input id="m_sort_order" name="sort_order" type="number" min="0" value="{{ old('sort_order', 0) }}" class="input-primary">
            </div>
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1" for="m_meta_description">{{ __('messages.seo_description') }}</label>
            <textarea id="m_meta_description" name="meta_description" rows="3" class="input-primary" maxlength="500" placeholder="{{ __('messages.seo_description') }}">{{ old('meta_description') }}</textarea>
            <p class="text-xs text-slate-500 mt-1"><span id="m-md-count">0</span>/160 {{ __('messages.recommended') }}</p>
            @error('meta_description')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
          </div>
          <div class="flex items-center gap-2">
            <input id="m_is_active" name="is_active" type="checkbox" value="1" class="w-4 h-4 border-slate-300 rounded" {{ old('is_active', 1) ? 'checked' : '' }}>
            <label for="m_is_active" class="text-sm text-slate-700">{{ __('messages.active') }}</label>
          </div>

          <div class="border-t border-slate-200 pt-5">
            <h3 class="text-lg font-semibold text-slate-900 mb-3">{{ __('messages.collection_image') }}</h3>
            <input type="hidden" id="m_primary_image_url" name="primary_image_url">
            <div class="flex items-center gap-2 mb-3">
              <button type="button" class="px-3 py-2 bg-blue-600 text-white text-sm rounded" id="m_add_url">{{ __('messages.add_url') }}</button>
              <input type="file" accept="image/*" class="hidden" id="m_file_input">
              <button type="button" class="px-3 py-2 bg-white border border-gray-300 rounded text-sm" id="m_upload_btn">{{ __('messages.upload_file') }}</button>
              <p class="text-xs text-gray-500 ml-2">{{ __('messages.select_one_image') }}</p>
            </div>
            <div class="m_upload_zone upload-zone rounded-lg p-4 text-center">
              <div class="space-y-2">
                <svg class="mx-auto h-10 w-10 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                  <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
                <div class="text-sm text-gray-600"><span class="font-medium">{{ __('messages.drag_drop_image') }}</span></div>
                <!-- <p class="text-xs text-gray-500">hoặc</p> -->
                <label class="inline-block"><span class="px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 cursor-pointer">{{ __('messages.choose_file') }}</span><input type="file" accept="image/*" class="hidden" id="m_zone_file"></label>
              </div>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 mt-2" id="m_images_preview"></div>
          </div>

          <div class="flex justify-end gap-3 pt-2">
            <button type="button" id="closeCreateModal2" class="px-3 py-2 rounded-lg border border-slate-300 text-slate-700">{{ __('messages.cancel') }}</button>
            <button type="submit" class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">{{ __('messages.save_collection') }}</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <script>
    window.toggleSubmenu = function(id){
      const submenu=document.getElementById(id+'-submenu');
      const arrow=document.getElementById(id+'-arrow');
      if(!submenu||!arrow)return;
      submenu.classList.toggle('hidden');
      arrow.classList.toggle('rotate-180');
    };

    // Modal controls
    const openBtn = document.getElementById('openCreateModal');
    const modal = document.getElementById('createCollectionModal');
    const closeBtn = document.getElementById('closeCreateModal');
    const closeBtn2 = document.getElementById('closeCreateModal2');

    function showModal() {
      modal.classList.remove('hidden');
    }

      function openModal() {
        // reset form to create mode
        const form = document.getElementById('createCollectionForm');
        if (form) {
        form.setAttribute('action', '{{ url('/admin/products/collections') }}');
        const old = form.querySelector('input[name="_method"]');
        if (old) old.remove();
        // clear fields
        const fields = ['m_name', 'm_slug', 'm_meta_title', 'm_meta_description', 'm_sort_order'];
        fields.forEach(id => {
          const el = document.getElementById(id);
          if (el) {
            el.value = id === 'm_sort_order' ? 0 : '';
          }
        });
        const active = document.getElementById('m_is_active');
        if (active) {
          active.checked = true;
        }
        const imgPrev = document.getElementById('m_images_preview');
        if (imgPrev) {
          imgPrev.innerHTML = '';
        }
        const imgUrl = document.getElementById('m_primary_image_url');
        if (imgUrl) {
          imgUrl.value = '';
        }
      }
      showModal();
    }

    function closeModal() {
      modal.classList.add('hidden');
    }
    if (openBtn) {
      openBtn.addEventListener('click', openModal);
    }
    if (closeBtn) {
      closeBtn.addEventListener('click', closeModal);
    }
    if (closeBtn2) {
      closeBtn2.addEventListener('click', closeModal);
    }
    modal && modal.addEventListener('click', e => {
      if (e.target === modal) closeModal();
    });

    // Slug & SEO counters (modal)
    const m_name = document.getElementById('m_name');
    const m_slug = document.getElementById('m_slug');
    const m_genSlug = document.getElementById('m_genSlug');
    const m_mt = document.getElementById('m_meta_title');
    const m_md = document.getElementById('m_meta_description');
    const m_mtCount = document.getElementById('m-mt-count');
    const m_mdCount = document.getElementById('m-md-count');

    function slugify(str) {
      return (str || '').toString().normalize('NFD').replace(/[\u0300-\u036f]/g, '').replace(/đ/g, 'd').replace(/Đ/g, 'D').toLowerCase().replace(/[^a-z0-9\s-]/g, '').trim().replace(/\s+/g, '-').replace(/-+/g, '-');
    }
    if (m_genSlug) {
      m_genSlug.addEventListener('click', () => {
        m_slug.value = slugify(m_name.value || m_slug.value);
      });
    }
    if (m_name) {
      m_name.addEventListener('blur', () => {
        if (!m_slug.value) {
          m_slug.value = slugify(m_name.value);
        }
      });
    }

    function m_updateCounts() {
      m_mtCount.textContent = (m_mt.value || '').length;
      m_mdCount.textContent = (m_md.value || '').length;
    }
    if (m_mt) m_mt.addEventListener('input', m_updateCounts);
    if (m_md) m_md.addEventListener('input', m_updateCounts);

    // Edit functionality
    document.querySelectorAll('.btn-edit').forEach(btn => {
      btn.addEventListener('click', () => {
        const id = btn.dataset.id;
        const name = btn.dataset.name;
        const slug = btn.dataset.slug;
        const meta_title = btn.dataset.meta_title;
        const meta_description = btn.dataset.meta_description;
        const sort_order = btn.dataset.sort_order;
        const is_active = btn.dataset.is_active;
        const image = btn.dataset.image;

        // Set form action for update
        const form = document.getElementById('createCollectionForm');
        form.setAttribute('action', '{{ url('/admin/products/collections') }}' + '/' + id);
        
        // Add hidden _method input
        let methodInput = form.querySelector('input[name="_method"]');
        if (!methodInput) {
          methodInput = document.createElement('input');
          methodInput.type = 'hidden';
          methodInput.name = '_method';
          methodInput.value = 'PUT';
          form.appendChild(methodInput);
        }

        // Fill fields
        if(document.getElementById('m_name')) document.getElementById('m_name').value = name;
        if(document.getElementById('m_slug')) document.getElementById('m_slug').value = slug;
        if(document.getElementById('m_meta_title')) document.getElementById('m_meta_title').value = meta_title;
        if(document.getElementById('m_meta_description')) document.getElementById('m_meta_description').value = meta_description;
        if(document.getElementById('m_sort_order')) document.getElementById('m_sort_order').value = sort_order;
        if(document.getElementById('m_is_active')) document.getElementById('m_is_active').checked = is_active == 1;

        // Image preview
        const imgPrev = document.getElementById('m_images_preview');
        imgPrev.innerHTML = '';
        if (image) {
          const div = document.createElement('div');
          div.className = 'relative group aspect-square rounded-lg overflow-hidden border border-gray-200';
          div.innerHTML = `
            <img src="${image}" class="w-full h-full object-cover">
            <button type="button" class="absolute top-1 right-1 p-1 bg-red-500 text-white rounded-full opacity-0 group-hover:opacity-100 transition-opacity" onclick="this.parentElement.remove(); document.getElementById('m_primary_image_url').value = ''">
              <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
          `;
          imgPrev.appendChild(div);
          document.getElementById('m_primary_image_url').value = image;
        }

        m_updateCounts();
        showModal();
      });
    });

    // Image handling (reused logic)
    // ... (This part seems to be handled by app.js or similar, but the classes suggest custom handling. 
    // The original code had placeholders for image handling logic. I will leave it as is, just ensuring the localized text is in place.)
    
    // Simple image handling for demo purposes if not present
    document.getElementById('m_add_url')?.addEventListener('click', () => {
      const url = prompt('{{ __('messages.enter_image_url') }}'); 
      if(url) {
        document.getElementById('m_primary_image_url').value = url;
        const imgPrev = document.getElementById('m_images_preview');
        imgPrev.innerHTML = `<div class="relative group aspect-square rounded-lg overflow-hidden border border-gray-200"><img src="${url}" class="w-full h-full object-cover"></div>`;
      }
    });

    // Image Upload Logic
    const mUploadBtn = document.getElementById('m_upload_btn');
    const mFileInput = document.getElementById('m_file_input');
    const mZoneFile = document.getElementById('m_zone_file');
    const mPrimaryImageUrl = document.getElementById('m_primary_image_url');
    const mImagesPreview = document.getElementById('m_images_preview');

    if (mUploadBtn && mFileInput) {
        mUploadBtn.addEventListener('click', () => mFileInput.click());
    }

    async function uploadCollectionImage(file) {
        const formData = new FormData();
        formData.append('file', file);
        
        try {
            const response = await fetch('{{ route("admin.products.collections.upload_image") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: formData
            });
            
            if (!response.ok) {
                const err = await response.json();
                throw new Error(err.message || 'Upload failed');
            }
            
            const data = await response.json();
            return data.url;
        } catch (error) {
            console.error(error);
            alert('Lỗi tải ảnh: ' + error.message);
            return null;
        }
    }

    async function handleFileSelect(e) {
        const file = e.target.files[0];
        if (!file) return;

        const url = await uploadCollectionImage(file);
        if (url) {
            mPrimaryImageUrl.value = url;
            mImagesPreview.innerHTML = '';
            
            const div = document.createElement('div');
            div.className = 'relative group aspect-square rounded-lg overflow-hidden border border-gray-200';
            div.innerHTML = `
                <img src="${url}" class="w-full h-full object-cover">
                <button type="button" class="absolute top-1 right-1 p-1 bg-red-500 text-white rounded-full opacity-0 group-hover:opacity-100 transition-opacity" onclick="this.parentElement.remove(); document.getElementById('m_primary_image_url').value = ''">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            `;
            mImagesPreview.appendChild(div);
        }
        e.target.value = ''; // Reset input
    }

    if (mFileInput) mFileInput.addEventListener('change', handleFileSelect);
    if (mZoneFile) mZoneFile.addEventListener('change', handleFileSelect);
  </script>
</body>
</html>
