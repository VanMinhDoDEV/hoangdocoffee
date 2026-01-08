<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
 <head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ __('messages.blog_categories_admin_title') }}</title>
  <link rel="shortcut icon" type="image/x-icon" href="{{ !empty($storeSettings['favicon']) ? asset($storeSettings['favicon']) : asset('assets/images/favicon.png') }}">
  <link rel="icon" type="image/png" href="{{ !empty($storeSettings['favicon']) ? asset($storeSettings['favicon']) : asset('assets/images/favicon.png') }}">
  @vite(['resources/css/app.css','resources/js/app.js'])
 </head>
 <body class="bg-gray-50">
 <div class="w-full min-h-screen flex">
  @include('admin.partials.sidebar')
  <main class="flex-1 overflow-auto md:ml-64 transition-all duration-300">
    @include('admin.partials.topbar', ['title' => __('messages.blog_categories')])
    
    <div class="max-w-[1400px] mx-auto px-4 py-8">
        @if(session('status'))
            <div class="mb-4 p-4 rounded-lg bg-green-50 text-green-700 border border-green-200">
                <i class="fa-solid fa-check-circle mr-2"></i> {{ session('status') }}
            </div>
        @endif
        
        @if($errors->any())
            <div class="mb-4 p-4 rounded-lg bg-red-50 text-red-700 border border-red-200">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="mb-8">
            <h1 class="text-3xl font-extrabold text-gray-800 tracking-tight">{{ __('messages.categories') }}</h1>
            <p class="text-gray-500 mt-1">{{ __('messages.manage_categories') }}</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-24">
                    <h2 class="text-lg font-bold text-gray-800 mb-6" id="formTitle">{{ __('messages.add_new_category') }}</h2>
                    <form id="categoryForm" action="{{ route('admin.posts.categories.store') }}" method="POST">
                        @csrf
                        <div id="methodField"></div>
                        
                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('messages.category_name') }}</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition-all" 
                                placeholder="{{ __('messages.category_name_placeholder') }}">
                            <p class="text-[11px] text-gray-400 mt-1">{{ __('messages.category_name_hint') }}</p>
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('messages.slug') }}</label>
                            <input type="text" name="slug" id="slug" value="{{ old('slug') }}"
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition-all" 
                                placeholder="{{ __('messages.slug_placeholder') }}">
                            <p class="text-[11px] text-gray-400 mt-1">{{ __('messages.slug_hint_simple') }}</p>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('messages.parent_category') }}</label>
                            <select name="parent_id" id="parent_id" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                                <option value="">{{ __('messages.none') }}</option>
                                @foreach($allCategories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('parent_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('messages.description') }}</label>
                            <textarea name="description" id="description" rows="4" 
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none" 
                                placeholder="{{ __('messages.description_placeholder') }}">{{ old('description') }}</textarea>
                        </div>

                        <div class="flex gap-2">
                            <button type="submit" id="submitBtn" class="flex-1 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg transition shadow-md shadow-blue-100">
                                {{ __('messages.add_category_btn') }}
                            </button>
                            <button type="button" id="cancelBtn" class="hidden px-4 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold rounded-lg transition">
                                {{ __('messages.cancel') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-4 border-b bg-gray-50/50">
                        <div class="relative w-full sm:w-72">
                            <input type="text" id="searchInput" placeholder="{{ __('messages.search_category') }}" class="w-full pl-10 pr-4 py-2 border rounded-lg text-sm outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                            <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-gray-400 text-xs"></i>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b">
                                <tr>
                                    <th class="px-6 py-4 font-bold w-12 text-center"><input type="checkbox" class="rounded"></th>
                                    <th class="px-6 py-4 font-bold">{{ __('messages.name') }}</th>
                                    <th class="px-6 py-4 font-bold">{{ __('messages.slug') }}</th>
                                    <th class="px-6 py-4 font-bold text-center">{{ __('messages.post_count') }}</th>
                                    <th class="px-6 py-4 font-bold text-right">{{ __('messages.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100" id="categoriesTableBody">
                                @forelse($allCategories as $category)
                                <tr class="hover:bg-blue-50/30 transition category-row" data-name="{{ strtolower($category->name) }}">
                                    <td class="px-6 py-4 text-center"><input type="checkbox" class="rounded"></td>
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-800 flex items-center">
                                            @if($category->parent_id)
                                                <span class="text-gray-300 mr-2">—</span>
                                            @endif
                                            {{ $category->name }}
                                        </div>
                                        <div class="text-[11px] text-gray-400">{{ __('messages.id') }}: {{ $category->id }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-gray-500">{{ $category->slug }}</td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full font-bold text-xs">{{ $category->posts_count }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex justify-end gap-2">
                                            <button type="button" 
                                                onclick="editCategory({{ json_encode($category) }})"
                                                class="p-2 text-blue-600 hover:bg-blue-100 rounded-lg transition" title="{{ __('messages.edit') }}">
                                                <i class="fa-solid fa-pen"></i>
                                            </button>
                                            <form action="{{ route('admin.posts.categories.destroy', $category->id) }}" method="POST" class="inline-block" onsubmit="return confirm('{{ __('messages.delete_category_confirm') }}')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 text-red-600 hover:bg-red-100 rounded-lg transition" title="{{ __('messages.delete') }}">
                                                    <i class="fa-solid fa-trash-can"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">{{ __('messages.no_categories_found') }}</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="bg-gray-50 p-4 border-t flex items-center space-x-2">
                        <select class="text-xs border rounded p-1.5 outline-none bg-white">
                            <option>{{ __('messages.bulk_actions') }}</option>
                            <option>{{ __('messages.delete') }}</option>
                        </select>
                        <button class="px-3 py-1.5 bg-gray-200 hover:bg-gray-300 text-gray-700 text-xs font-bold rounded transition">{{ __('messages.apply') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </main>
 </div>

 <script>
    const form = document.getElementById('categoryForm');
    const formTitle = document.getElementById('formTitle');
    const submitBtn = document.getElementById('submitBtn');
    const cancelBtn = document.getElementById('cancelBtn');
    const methodField = document.getElementById('methodField');
    const nameInput = document.getElementById('name');
    const slugInput = document.getElementById('slug');
    const parentInput = document.getElementById('parent_id');
    const descInput = document.getElementById('description');
    
    window.toggleSubmenu = function(id){
      const submenu=document.getElementById(id+'-submenu');
      const arrow=document.getElementById(id+'-arrow');
      if(!submenu||!arrow)return;
      submenu.classList.toggle('hidden');
      arrow.classList.toggle('rotate-180');
    };

    // Auto slug generation

    // Search functionality
    document.getElementById('searchInput').addEventListener('keyup', function(e) {
        const searchText = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('.category-row');
        
        rows.forEach(row => {
            const name = row.dataset.name;
            if (name.includes(searchText)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    function editCategory(category) {
        // Scroll to top
        window.scrollTo({ top: 0, behavior: 'smooth' });
        
        // Update form state
        formTitle.textContent = '{{ __('messages.update_category') }}' + category.name;
        submitBtn.textContent = '{{ __('messages.update_btn') }}';
        cancelBtn.classList.remove('hidden');
        
        // Update action URL
        form.action = `/admin/posts/categories/${category.id}`;
        
        // Add PUT method
        methodField.innerHTML = '<input type="hidden" name="_method" value="PUT">';
        
        // Fill values
        nameInput.value = category.name;
        slugInput.value = category.slug;
        parentInput.value = category.parent_id || '';
        descInput.value = category.description || '';
    }
    
    cancelBtn.addEventListener('click', function() {
        resetForm();
    });
    
    function resetForm() {
        formTitle.textContent = '{{ __('messages.add_new_category') }}';
        submitBtn.textContent = '{{ __('messages.add_category_btn') }}';
        cancelBtn.classList.add('hidden');
        form.action = "{{ route('admin.posts.categories.store') }}";
        methodField.innerHTML = '';
        form.reset();
    }
 </script>
 </body>
</html>
