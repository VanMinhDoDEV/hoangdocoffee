<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('messages.manage_tags_admin_title') }}</title>
    <link rel="shortcut icon" type="image/x-icon" href="{{ !empty($storeSettings['favicon']) ? asset($storeSettings['favicon']) : asset('assets/images/favicon.png') }}">
    <link rel="icon" type="image/png" href="{{ !empty($storeSettings['favicon']) ? asset($storeSettings['favicon']) : asset('assets/images/favicon.png') }}">
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="bg-gray-50 font-sans antialiased">
<div class="w-full min-h-screen flex">
    @include('admin.partials.sidebar')
    <main class="flex-1 overflow-auto md:ml-64 transition-all duration-300">
        @include('admin.partials.topbar', ['title' => __('messages.post_tags')])
        
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

            <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-800 tracking-tight">{{ __('messages.tags') }}</h1>
                    <p class="text-gray-500 mt-1">{{ __('messages.manage_tags_desc') }}</p>
                </div>
                <div class="flex gap-4">
                    <div class="bg-blue-50 px-4 py-2 rounded-lg border border-blue-100">
                        <span class="block text-xs text-blue-500 font-bold uppercase">{{ __('messages.total_tags') }}</span>
                        <span class="text-xl font-black text-blue-700">{{ number_format($totalTags) }}</span>
                    </div>
                    <div class="bg-green-50 px-4 py-2 rounded-lg border border-green-100">
                        <span class="block text-xs text-green-500 font-bold uppercase">{{ __('messages.most_used') }}</span>
                        <span class="text-xl font-black text-green-700">
                            @if($mostUsedTag)
                                #{{ $mostUsedTag->name }}
                            @else
                                --
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-24">
                        <h2 class="text-lg font-bold text-gray-800 mb-6 flex items-center" id="formTitle">
                            <i class="fa-solid fa-tag mr-2 text-blue-500"></i> {{ __('messages.add_new_tag') }}
                        </h2>
                        <form id="tagForm" action="{{ route('admin.posts.tags.store') }}" method="POST">
                            @csrf
                            <div id="methodField"></div>
                            
                            <div class="mb-4">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('messages.tag_name') }}</label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                    class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition-all" 
                                    placeholder="{{ __('messages.tag_name_placeholder') }}">
                            </div>
                            
                            <div class="mb-4">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('messages.slug') }}</label>
                                <input type="text" name="slug" id="slug" value="{{ old('slug') }}"
                                    class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition-all" 
                                    placeholder="{{ __('messages.slug_placeholder') }}">
                                <p class="text-[11px] text-gray-400 mt-1">{{ __('messages.slug_hint_simple') }}</p>
                            </div>

                            <div class="flex gap-2">
                                <button type="submit" id="submitBtn" class="w-full py-3 bg-slate-800 hover:bg-slate-900 text-white font-bold rounded-lg transition shadow-lg">
                                    {{ __('messages.confirm_add') }}
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
                        <div class="p-4 border-b bg-gray-50/50 flex flex-col sm:flex-row justify-between gap-4">
                            <form method="GET" action="{{ route('admin.posts.tags') }}" class="relative flex-1">
                                <input type="text" name="q" value="{{ request('q') }}" placeholder="{{ __('messages.search_tag_placeholder') }}" class="w-full pl-10 pr-4 py-2 border rounded-lg text-sm outline-none focus:ring-2 focus:ring-blue-500">
                                <button type="submit" class="absolute left-3 top-2.5 text-gray-400">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </button>
                            </form>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left">
                                <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b">
                                    <tr>
                                        <th class="px-6 py-4 font-bold">{{ __('messages.tag_name') }}</th>
                                        <th class="px-6 py-4 font-bold">{{ __('messages.slug') }}</th>
                                        <th class="px-6 py-4 font-bold text-center">{{ __('messages.frequency') }}</th>
                                        <th class="px-6 py-4 font-bold text-right">{{ __('messages.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @forelse($tags as $tag)
                                    <tr class="hover:bg-blue-50/30 transition">
                                        <td class="px-6 py-4 font-bold text-gray-700">
                                            <span class="text-blue-600">#</span> {{ $tag->name }}
                                        </td>
                                        <td class="px-6 py-4 text-gray-500">{{ $tag->slug }}</td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="font-mono font-bold">{{ $tag->posts_count }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex justify-end gap-3">
                                                <button type="button" 
                                                    onclick="editTag({{ json_encode($tag) }})"
                                                    class="text-gray-400 hover:text-blue-600 transition" title="{{ __('messages.edit') }}">
                                                    <i class="fa-solid fa-pen"></i>
                                                </button>
                                                
                                                <form action="{{ route('admin.posts.tags.destroy', $tag->id) }}" method="POST" class="inline-block" onsubmit="return confirm('{{ __('messages.delete_tag_confirm') }}')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-gray-400 hover:text-red-600 transition" title="{{ __('messages.delete') }}">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-8 text-center text-gray-500">{{ __('messages.no_tags_found') }}</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if($tags->hasPages())
                        <div class="p-4 bg-gray-50 border-t flex justify-end">
                            {{ $tags->links() }}
                        </div>
                        @endif
                    </div>

                    <div class="mt-8 bg-white p-6 rounded-xl border border-dashed border-gray-300">
                        <h3 class="text-sm font-bold text-gray-400 uppercase mb-4 tracking-widest">{{ __('messages.popular_tags') }}</h3>
                        <div class="flex flex-wrap gap-2">
                            @forelse($popularTags as $pTag)
                                <a href="{{ route('admin.posts.tags', ['q' => $pTag->name]) }}" 
                                   class="px-3 py-1 bg-gray-100 text-gray-600 rounded-full text-xs font-semibold hover:bg-blue-100 hover:text-blue-600 transition cursor-pointer">
                                   #{{ $pTag->name }} ({{ $pTag->posts_count }})
                                </a>
                            @empty
                                <span class="text-gray-500 text-xs">{{ __('messages.no_tags_found') }}</span>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
    // Include the missing toggleSubmenu function just in case
    window.toggleSubmenu = function(id){
      const submenu=document.getElementById(id+'-submenu');
      const arrow=document.getElementById(id+'-arrow');
      if(!submenu||!arrow)return;
      submenu.classList.toggle('hidden');
      arrow.classList.toggle('rotate-180');
    };

    // Auto slug generation
    document.getElementById('name').addEventListener('keyup', function() {
        // Don't auto-generate in Edit mode to preserve SEO slugs
        if (methodField.innerHTML !== '') return;

        if (!this.value) {
            document.getElementById('slug').value = '';
            return;
        }
        
        // Only auto-generate if we are in "Create" mode (no ID in form action usually, but here checking if we are editing)
        // Or simpler: just do it if the user hasn't manually edited the slug field significantly?
        // For now, simple slugify on change
        const slug = this.value
            .toLowerCase()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .replace(/[đĐ]/g, 'd')
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/[\s-]+/g, '-')
            .replace(/^-+|-+$/g, '');
            
        document.getElementById('slug').value = slug;
    });

    // Edit functionality
    const form = document.getElementById('tagForm');
    const formTitle = document.getElementById('formTitle');
    const submitBtn = document.getElementById('submitBtn');
    const cancelBtn = document.getElementById('cancelBtn');
    const methodField = document.getElementById('methodField');
    const nameInput = document.getElementById('name');
    const slugInput = document.getElementById('slug');

    function editTag(tag) {
        // Change UI to Edit Mode
        formTitle.innerHTML = '<i class="fa-solid fa-pen mr-2 text-blue-500"></i> {{ __('messages.update_tag') }}';
        submitBtn.textContent = '{{ __('messages.save_changes') }}';
        submitBtn.classList.remove('bg-slate-800', 'hover:bg-slate-900');
        submitBtn.classList.add('bg-blue-600', 'hover:bg-blue-700');
        
        cancelBtn.classList.remove('hidden');
        
        // Update action URL
        form.action = `/admin/posts/tags/${tag.id}`;
        
        // Add PUT method
        methodField.innerHTML = '<input type="hidden" name="_method" value="PUT">';
        
        // Fill values
        nameInput.value = tag.name;
        slugInput.value = tag.slug;
        
        // Focus name
        nameInput.focus();
    }
    
    cancelBtn.addEventListener('click', function() {
        resetForm();
    });
    
    function resetForm() {
        formTitle.innerHTML = '<i class="fa-solid fa-tag mr-2 text-blue-500"></i> {{ __('messages.add_new_tag') }}';
        submitBtn.textContent = '{{ __('messages.confirm_add') }}';
        
        submitBtn.classList.add('bg-slate-800', 'hover:bg-slate-900');
        submitBtn.classList.remove('bg-blue-600', 'hover:bg-blue-700');
        
        cancelBtn.classList.add('hidden');
        form.action = "{{ route('admin.posts.tags.store') }}";
        methodField.innerHTML = '';
        form.reset();
    }
</script>
</body>
</html>
