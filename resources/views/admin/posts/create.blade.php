<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.create_new_post') }}</title>
    <link rel="shortcut icon" type="image/x-icon" href="{{ !empty($storeSettings['favicon']) ? asset($storeSettings['favicon']) : asset('assets/images/favicon.png') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css','resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f1f5f9; }
        .custom-scrollbar::-webkit-scrollbar { width: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    </style>
</head>
<body class="text-slate-900 bg-slate-50">

    <form id="post-form" action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    
    <nav class="sticky top-0 z-50 bg-white border-b border-slate-200 px-6 py-3 flex justify-between items-center shadow-sm">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.posts.index') }}" class="text-slate-400 hover:text-indigo-600 transition-colors"><i class="fa-solid fa-chevron-left"></i></a>
            <h2 class="font-bold text-lg tracking-tight">{{ __('messages.create_new_post') }}</h2>
        </div>
        <div class="flex items-center gap-3">
            <button type="submit" name="action" value="draft" class="px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100 rounded-lg">{{ __('messages.save_draft') }}</button>
            <button type="submit" name="action" value="published" class="px-6 py-2 bg-indigo-600 text-white text-sm font-bold rounded-lg shadow-lg shadow-indigo-200 hover:bg-indigo-700 transition-all">{{ __('messages.publish_now') }}</button>
        </div>
    </nav>

    <main class="max-w-[1440px] mx-auto p-6 lg:p-8">
        @if ($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <div class="lg:col-span-8 space-y-8">
                
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8">
                    <div class="flex justify-between items-center mb-4">
                        <label class="text-xs font-bold text-slate-400 uppercase tracking-widest">{{ __('messages.title_info') }}</label>
                        <span class="text-xs text-slate-400 font-mono" id="title-char-count">0/60 {{ __('messages.characters') }}</span>
                    </div>
                    <input type="text" name="title" id="title" placeholder="{{ __('messages.enter_title') }}" required maxlength="60"
                        class="w-full text-4xl font-extrabold border-none outline-none focus:ring-0 placeholder:text-slate-200 mb-6">
                    
                    <div class="flex items-center bg-slate-50 px-4 py-2 rounded-lg border border-slate-200 group focus-within:border-indigo-400 transition-all">
                        <span class="text-slate-400 text-sm mr-2 italic">{{ request()->getHost() }}/</span>
                        <input type="text" name="slug" id="slug" placeholder="{{ __('messages.slug_auto_placeholder') }}" class="bg-transparent border-none outline-none focus:ring-0 text-sm text-indigo-600 font-medium flex-1 p-0">
                        <i class="fa-solid fa-pen text-[10px] text-slate-300"></i>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <textarea id="articleInput" name="content" required rows="16" placeholder="{{ __('messages.content_placeholder') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm font-mono" data-upload-url="{{ route('admin.products.upload_image') }}"></textarea>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8">
                    <h3 class="text-lg font-bold mb-6 flex items-center gap-2">
                        <i class="fa-solid fa-magnifying-glass-chart text-indigo-500"></i> 
                        {{ __('messages.seo_optimization') }}
                    </h3>
                    <div class="space-y-6">
                        <div class="bg-slate-50 p-6 rounded-xl border border-slate-100 max-w-2xl">
                            <div class="text-sm text-[#202124] mb-1 flex items-center gap-2">
                                <span class="bg-white w-6 h-6 rounded-full border flex items-center justify-center text-[10px]">G</span>
                                {{ request()->getHost() }} > blog > ...
                            </div>
                            <div class="text-[#1a0dab] text-xl font-medium mb-1 hover:underline cursor-pointer" id="seo-title-preview">{{ __('messages.seo_title_preview') }}</div>
                            <div class="text-[#4d5156] text-sm line-clamp-2 leading-relaxed" id="seo-desc-preview">{{ __('messages.seo_desc_preview') }}</div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4">
                            <div>
                                <label class="block text-sm font-semibold mb-2">{{ __('messages.meta_description') }} <span class="text-red-500">*</span></label>
                                <textarea name="meta_description" id="meta_description" rows="3" placeholder="{{ __('messages.enter_summary') }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none text-sm transition-all"></textarea>
                                <p class="text-[11px] text-slate-400 mt-2">{{ __('messages.meta_desc_hint') }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold mb-2">{{ __('messages.keywords') }}</label>
                                <input type="text" name="keywords" placeholder="{{ __('messages.keywords_placeholder') }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none text-sm transition-all">
                                <p class="text-[11px] text-slate-400 mt-2">{{ __('messages.keywords_help') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-4 space-y-6">
                
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                    <label class="text-sm font-bold text-slate-700 block mb-4 uppercase tracking-wider">{{ __('messages.thumbnail') }}</label>
                    <div class="aspect-[4/3] bg-slate-50 rounded-xl border-2 border-dashed border-slate-200 flex flex-col items-center justify-center relative overflow-hidden group hover:border-indigo-400 hover:bg-indigo-50/30 transition-all cursor-pointer" id="thumbnail-container" onclick="document.getElementById('thumbnail-file').click()">
                        <i class="fa-solid fa-images text-slate-300 text-4xl mb-3 group-hover:scale-110 transition-transform"></i>
                        <p class="text-xs font-semibold text-slate-500">{{ __('messages.upload_or_drag') }}</p>
                        <p class="text-[10px] text-slate-400 mt-1">{{ __('messages.image_support') }}</p>
                        <input type="file" id="thumbnail-file" name="thumbnail" class="hidden" onchange="previewThumbnail(this)">
                        <img id="thumbnail-preview" class="absolute inset-0 w-full h-full object-cover hidden">
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="p-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                        <label class="text-sm font-bold text-slate-700 uppercase tracking-wider">{{ __('messages.post_category') }}</label>
                        <a href="{{ route('admin.posts.categories') }}" target="_blank" class="text-[11px] font-bold text-indigo-600 hover:underline">{{ __('messages.add_new') }}</a>
                    </div>
                    
                    <div class="p-5 max-h-[300px] overflow-y-auto custom-scrollbar">
                        <div class="space-y-4">
                            @forelse($categories as $category)
                            <div class="space-y-3">
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <input type="checkbox" name="categories[]" value="{{ $category->id }}" class="w-4 h-4 rounded text-indigo-600 border-slate-300 focus:ring-indigo-500">
                                    <span class="text-sm font-bold text-slate-700 group-hover:text-indigo-600 transition-colors">{{ $category->name }}</span>
                                </label>
                                
                                @if($category->children && $category->children->count() > 0)
                                <div class="ml-6 space-y-3 border-l-2 border-slate-100 pl-4">
                                    @foreach($category->children as $child)
                                    <label class="flex items-center gap-3 cursor-pointer group">
                                        <input type="checkbox" name="categories[]" value="{{ $child->id }}" class="w-4 h-4 rounded text-indigo-600 border-slate-300 focus:ring-indigo-500">
                                        <span class="text-sm text-slate-600 group-hover:text-indigo-600">{{ $child->name }}</span>
                                    </label>

                                    @if($child->children && $child->children->count() > 0)
                                    <div class="ml-4 space-y-3 border-l-2 border-slate-100 pl-4">
                                        @foreach($child->children as $subChild)
                                        <label class="flex items-center gap-3 cursor-pointer group">
                                            <input type="checkbox" name="categories[]" value="{{ $subChild->id }}" class="w-3.5 h-3.5 rounded text-indigo-600 border-slate-300 focus:ring-indigo-500">
                                            <span class="text-xs text-slate-500 group-hover:text-indigo-600 italic">{{ $subChild->name }}</span>
                                        </label>
                                        @endforeach
                                    </div>
                                    @endif
                                    @endforeach
                                </div>
                                @endif
                            </div>
                            @empty
                            <p class="text-sm text-gray-500">{{ __('messages.no_categories') }}</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                    <label class="text-sm font-bold text-slate-700 block mb-3 uppercase tracking-wider">{{ __('messages.tags') }}</label>
                    <div class="flex flex-wrap gap-2 mb-3" id="tags-display">
                    </div>
                    <input type="text" id="tag-input" placeholder="{{ __('messages.add_tag') }}" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:border-indigo-400 outline-none transition-all">
                    <input type="hidden" name="tags" id="tags-hidden">
                    <div class="mt-3">
                        <div class="text-[11px] text-slate-400 mb-2">Gợi ý từ khóa</div>
                        <div id="tag-suggestions" class="flex flex-wrap gap-2"></div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-slate-500 font-medium italic"><i class="fa-solid fa-user-tie mr-2"></i> {{ __('messages.author') }}:</span>
                        <span class="text-sm font-bold">{{ auth()->user()->name ?? __('messages.admin') }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-slate-500 font-medium italic"><i class="fa-solid fa-calendar-days mr-2"></i> {{ __('messages.status') }}:</span>
                        <span class="bg-amber-100 text-amber-700 text-[10px] font-extrabold px-2 py-1 rounded uppercase">{{ __('messages.draft') }}</span>
                    </div>
                </div>

            </div>
        </div>
    </main>
    </form>

    <div id="toast" class="hidden fixed bottom-8 right-8 px-6 py-4 rounded-lg shadow-lg z-50 bg-blue-600 text-white">
        <p id="toast-message" class="font-medium text-sm"></p>
    </div>

    <script>
        document.getElementById('title').addEventListener('input', function() {
            let slug = this.value.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '').replace(/đ/g, 'd').replace(/[^a-z0-9\s-]/g, '').trim().replace(/\s+/g, '-').replace(/-+/g, '-');
            document.getElementById('slug').value = slug;
            document.getElementById('title-char-count').innerText = this.value.length + '/60 {{ __('messages.characters') }}';
            document.getElementById('seo-title-preview').innerText = this.value || '{{ __('messages.seo_title_preview') }}';
        });

        document.getElementById('meta_description').addEventListener('input', function() {
             document.getElementById('seo-desc-preview').innerText = this.value || '{{ __('messages.seo_desc_preview') }}';
        });

        function previewThumbnail(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    let img = document.getElementById('thumbnail-preview');
                    img.src = e.target.result;
                    img.classList.remove('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        const tagInput = document.getElementById('tag-input');
        const tagsDisplay = document.getElementById('tags-display');
        const tagsHidden = document.getElementById('tags-hidden');
        const tagSuggestionsEl = document.getElementById('tag-suggestions');
        const allSuggestions = @json($tagSuggestions ?? []);
        let tags = [];

        tagInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const val = this.value.trim();
                if (val && !tags.includes(val)) {
                    tags.push(val);
                    renderTags();
                }
                this.value = '';
            }
        });

        function renderTags() {
            tagsDisplay.innerHTML = tags.map(tag => `
                <span class="bg-indigo-50 text-indigo-600 text-[10px] font-bold px-2 py-1 rounded border border-indigo-100 flex items-center gap-1">
                    #${tag.toUpperCase()} <i class="fa-solid fa-xmark cursor-pointer hover:text-red-500" onclick="removeTag('${tag}')"></i>
                </span>
            `).join('');
            tagsHidden.value = tags.join(',');
            renderSuggestions(tagInput.value.trim());
        }

        window.removeTag = function(tag) {
            tags = tags.filter(t => t !== tag);
            renderTags();
        }
        function renderSuggestions(filter) {
            const f = (filter||'').toLowerCase();
            const list = allSuggestions.filter(function(s){
                if (tags.includes(s)) return false;
                if (!f) return true;
                return s.toLowerCase().includes(f);
            }).slice(0, 12);
            tagSuggestionsEl.innerHTML = list.map(function(s){
                return `<button type="button" class="px-2 py-1 text-xs rounded-full border border-slate-200 bg-slate-50 hover:bg-indigo-50 hover:border-indigo-200 hover:text-indigo-700 transition" data-tag="${s}">#${s}</button>`;
            }).join('');
            tagSuggestionsEl.querySelectorAll('button[data-tag]').forEach(function(btn){
                btn.addEventListener('click', function(){
                    const val = this.getAttribute('data-tag');
                    if (val && !tags.includes(val)) {
                        tags.push(val);
                        renderTags();
                    }
                });
            });
        }
        tagInput.addEventListener('input', function(){
            renderSuggestions(this.value.trim());
        });
        renderSuggestions('');

        window.toggleSubmenu = function(id) {
             const submenu = document.getElementById(id+'-submenu');
             const arrow = document.getElementById(id+'-arrow');
             if(submenu && arrow){
                 submenu.classList.toggle('hidden');
                 arrow.classList.toggle('rotate-180');
             }
        };
    </script>
</body>
</html>
