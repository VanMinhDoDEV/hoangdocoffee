<header id="adminTopbarHeader" class="bg-white border-b border-gray-200 relative">
    <div class="flex items-center justify-between px-8 py-5 gap-4">
        <div class="flex items-center flex-shrink-0">
            <button id="sidebarToggle" class="md:hidden mr-4 text-gray-500 hover:text-gray-700 focus:outline-none">
                <i class="fas fa-bars text-xl"></i>
            </button>
            @hasSection('title')
                <h2 class="text-2xl font-semibold text-gray-800">@yield('title')</h2>
            @else
                <h2 class="text-2xl font-semibold text-gray-800">{{ $title ?? 'Admin' }}</h2>
            @endif
        </div>

        <!-- Global Search -->
        <div class="flex-1 max-w-xl relative hidden md:block mobile-hidden-force">
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400 group-focus-within:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text" 
                       id="globalSearchInput"
                       class="block w-full pl-10 pr-10 py-2.5 border border-gray-200 rounded-xl leading-5 bg-gray-50 text-gray-900 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-blue-100 focus:border-blue-500 sm:text-sm transition-all duration-200"
                       placeholder="{{ __('messages.search_placeholder') }}"
                       autocomplete="off">
                <div id="searchLoading" class="absolute inset-y-0 right-0 pr-3 flex items-center hidden">
                    <svg class="animate-spin h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
            </div>

            <!-- Search Results Dropdown -->
            <div id="searchResults" class="absolute left-0 right-0 top-full mt-2 bg-white rounded-xl shadow-xl border border-gray-100 hidden z-50 overflow-hidden max-h-[calc(100vh-150px)] overflow-y-auto">
                <!-- Content injected by JS -->
            </div>
        </div>

        <div class="flex items-center gap-4 flex-shrink-0">
            <div class="hidden sm:flex items-center gap-1 mr-2 bg-gray-50 rounded-lg p-1">
                <a href="{{ route('lang.switch', 'vi') }}" class="px-2 py-1 text-xs font-semibold {{ app()->getLocale() == 'vi' ? 'bg-white shadow-sm text-blue-600' : 'text-gray-500 hover:text-gray-700' }} rounded-md transition-all">VI</a>
                <a href="{{ route('lang.switch', 'en') }}" class="px-2 py-1 text-xs font-semibold {{ app()->getLocale() == 'en' ? 'bg-white shadow-sm text-blue-600' : 'text-gray-500 hover:text-gray-700' }} rounded-md transition-all">EN</a>
            </div>
            <div class="relative">
                <button id="admin-notif-btn" class="relative p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    <span id="admin-notif-badge" class="absolute top-1 right-1 w-4 h-4 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center hidden">0</span>
                </button>
                
                <!-- Notification Dropdown -->
                <div id="admin-notif-dropdown" class="absolute right-0 top-full mt-2 w-80 bg-white rounded-lg shadow-xl overflow-hidden z-[100] hidden border border-gray-100">
                    <div class="p-3 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                        <h3 class="text-sm font-semibold text-gray-800">Thông báo mới</h3>
                        <button id="admin-notif-clear" class="text-xs text-blue-600 hover:text-blue-800 font-medium">Đã xem hết</button>
                    </div>
                    <div id="admin-notif-list" class="max-h-80 overflow-y-auto">
                        <div class="p-8 text-center text-gray-400 text-sm flex flex-col items-center">
                            <i class="far fa-bell-slash text-2xl mb-2 opacity-50"></i>
                            <p>Chưa có thông báo mới</p>
                        </div>
                    </div>
                    <div class="p-2 border-t border-gray-100 text-center">
                        <a href="{{ route('admin.orders') }}" class="text-xs text-gray-500 hover:text-blue-600 font-medium block py-1">Xem tất cả đơn hàng</a>
                    </div>
                </div>
            </div>
            <div class="relative">
                <button id="topbarUserToggle" type="button" class="flex items-center gap-3 pl-4 border-l border-gray-200 cursor-pointer">
                    <div class="text-right hidden md:block">
                        <p class="text-sm font-medium text-gray-800">{{ auth()->user()->name ?? __('messages.admin') }}</p>
                        <p class="text-xs text-gray-500">{{ auth()->user()->email ?? 'admin@example.com' }}</p>
                    </div>
                    @if(auth()->user()->avatar)
                        <img id="topbar-avatar" src="{{ Storage::url(auth()->user()->avatar) }}" alt="Avatar" class="w-10 h-10 rounded-lg object-cover border border-gray-200">
                    @else
                        <div id="topbar-avatar-placeholder" class="w-10 h-10 bg-slate-700 rounded-lg flex items-center justify-center text-white font-semibold text-sm">
                            {{ substr(auth()->user()->name ?? 'A', 0, 2) }}
                        </div>
                        <img id="topbar-avatar" src="" alt="Avatar" class="w-10 h-10 rounded-lg object-cover border border-gray-200 hidden">
                    @endif
                </button>
            </div>
        </div>
    </div>
    <div id="dropdownMenu" class="dropdown-menu absolute right-8 top-full w-56 bg-white border border-gray-200 rounded-xl shadow-xl z-50 overflow-hidden hidden">
        <div class="p-4 border-b border-gray-100 bg-gradient-to-br from-slate-50 to-white">
            <p id="dropdownName" class="text-sm font-semibold text-gray-800">{{ auth()->user()->name ?? __('messages.admin') }}</p>
            <p id="dropdownEmail" class="text-xs text-gray-500 mt-1">{{ auth()->user()->email ?? 'admin@example.com' }}</p>
        </div>
        <div class="p-2 border-b border-gray-100">
            <a href="{{ route('admin.settings.profile') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 rounded-lg transition-colors">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                <span>{{ __('messages.my_profile') }}</span>
            </a>
        </div>
        <div class="p-2">
            <form method="post" action="{{ route('logout') }}" onsubmit="handleLogout(event)">
                @csrf
                <button type="submit" class="logout-button w-full px-4 py-2.5 text-left text-sm text-red-600 hover:bg-red-50 rounded-lg font-medium flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    <span id="logoutText">{{ __('messages.logout') }}</span>
                </button>
            </form>
        </div>
    </div>
</header>
<script>
    (function() {
        const searchInput = document.getElementById('globalSearchInput');
        const searchResults = document.getElementById('searchResults');
        const searchLoading = document.getElementById('searchLoading');
        let debounceTimer;

        if (!searchInput || !searchResults) return;

        searchInput.addEventListener('input', function(e) {
            const query = e.target.value.trim();
            
            clearTimeout(debounceTimer);
            
            if (query.length < 2) {
                searchResults.classList.add('hidden');
                searchResults.innerHTML = '';
                return;
            }

            searchLoading.classList.remove('hidden');

            debounceTimer = setTimeout(() => {
                fetch(`{{ route('admin.global_search') }}?q=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        renderResults(data);
                    })
                    .catch(error => {
                        console.error('Search error:', error);
                        searchResults.innerHTML = '<div class="p-4 text-center text-sm text-red-500">{{ __('messages.search_error') }}</div>';
                        searchResults.classList.remove('hidden');
                    })
                    .finally(() => {
                        searchLoading.classList.add('hidden');
                    });
            }, 300);
        });

        // Close when clicking outside
        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
                searchResults.classList.add('hidden');
            }
        });

        // Re-open if input has value and is focused
        searchInput.addEventListener('focus', function() {
            if (searchResults.children.length > 0) {
                searchResults.classList.remove('hidden');
            }
        });

        function renderResults(data) {
            if ((!data.products || data.products.length === 0) && (!data.customers || data.customers.length === 0)) {
                searchResults.innerHTML = `
                    <div class="p-8 text-center">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <p class="text-gray-500 text-sm">{{ __('messages.no_results_for') }}</p>
                    </div>
                `.replace(':query', searchInput.value);
            } else {
                let html = '';

                // Products Section
                if (data.products && data.products.length > 0) {
                    html += `
                        <div class="px-4 py-2 bg-gray-50 border-b border-gray-100 flex items-center justify-between">
                            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('messages.products') }}</h3>
                            <span class="text-xs text-gray-400">${data.products.length} {{ __('messages.results') }}</span>
                        </div>
                        <div class="divide-y divide-gray-50">
                    `;
                    data.products.forEach(product => {
                        html += `
                            <a href="${product.url}" class="block px-4 py-3 hover:bg-gray-50 transition-colors group">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-gray-100 border border-gray-200 overflow-hidden flex-shrink-0">
                                        ${product.image 
                                            ? `<img src="${product.image}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">` 
                                            : `<div class="w-full h-full flex items-center justify-center text-gray-400"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg></div>`
                                        }
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate group-hover:text-blue-600 transition-colors">${product.name}</p>
                                        <div class="flex items-center gap-2 mt-0.5">
                                            <span class="text-xs text-gray-500 font-mono bg-gray-100 px-1.5 py-0.5 rounded">${product.sku || '{{ __('messages.no_sku') }}'}</span>
                                            <span class="text-xs font-semibold text-blue-600">${product.price} ₫</span>
                                        </div>
                                    </div>
                                    <svg class="w-4 h-4 text-gray-300 group-hover:text-blue-500 transform group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                </div>
                            </a>
                        `;
                    });
                    html += '</div>';
                }

                // Customers Section
                if (data.customers && data.customers.length > 0) {
                    html += `
                        <div class="px-4 py-2 bg-gray-50 border-b border-gray-100 border-t flex items-center justify-between">
                            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('messages.customers') }}</h3>
                            <span class="text-xs text-gray-400">${data.customers.length} {{ __('messages.results') }}</span>
                        </div>
                        <div class="divide-y divide-gray-50">
                    `;
                    data.customers.forEach(customer => {
                        html += `
                            <a href="${customer.url}" class="block px-4 py-3 hover:bg-gray-50 transition-colors group">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-blue-100 border-2 border-white shadow-sm flex items-center justify-center overflow-hidden flex-shrink-0 text-blue-600 font-bold text-sm">
                                        ${customer.avatar 
                                            ? `<img src="${customer.avatar}" class="w-full h-full object-cover">` 
                                            : customer.name.charAt(0).toUpperCase()
                                        }
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate group-hover:text-blue-600 transition-colors">${customer.name}</p>
                                        <p class="text-xs text-gray-500 truncate">${customer.email || '{{ __('messages.no_email') }}'}</p>
                                    </div>
                                </div>
                            </a>
                        `;
                    });
                    html += '</div>';
                }
                
                searchResults.innerHTML = html;
            }
            searchResults.classList.remove('hidden');
        }
    })();

    (function(){
        var toggle=document.getElementById('topbarUserToggle');
        var menu=document.getElementById('dropdownMenu');
        if(toggle && menu){
            var open=false;
            function show(){menu.classList.remove('hidden');open=true;}
            function hide(){menu.classList.add('hidden');open=false;}
            toggle.addEventListener('click',function(e){e.stopPropagation();open?hide():show();});
            toggle.addEventListener('mouseenter',function(){show();});
            menu.addEventListener('click',function(e){e.stopPropagation();});
            document.addEventListener('click',function(){hide();});
        }
    })();
    function handleLogout(e){ }
</script>
