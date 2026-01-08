<aside id="sidebar" class="w-64 bg-slate-800 text-white flex-shrink-0 -translate-x-full md:translate-x-0 md:fixed fixed inset-y-0 left-0 z-50 transition-transform duration-300 ease-in-out overflow-y-auto pr-2">
 <style>
  #sidebar{scrollbar-gutter:stable;scrollbar-width:thin;scrollbar-color:#1f2937 #1f2937}
  #sidebar::-webkit-scrollbar{width:8px}
  #sidebar::-webkit-scrollbar-track{background:#1f2937}
  #sidebar::-webkit-scrollbar-thumb{background:#1f2937;border-radius:8px}
  #sidebar::-webkit-scrollbar-thumb:hover{background:#1f2937}
 </style>
 @php
    $storeSettings = [];
    if (\Illuminate\Support\Facades\Storage::disk('local')->exists('settings.json')) {
        $storeSettings = json_decode(\Illuminate\Support\Facades\Storage::disk('local')->get('settings.json'), true)['store'] ?? [];
    }
    $storeName = !empty($storeSettings['name']) ? $storeSettings['name'] : __('messages.admin_panel');
    $storeTagline = !empty($storeSettings['tagline']) ? $storeSettings['tagline'] : __('messages.system_management');
    $storeFavicon = !empty($storeSettings['favicon']) ? $storeSettings['favicon'] : null;
 @endphp
 <div class="p-6 border-b border-slate-700">
    <div class="flex items-center gap-3">
        @if($storeFavicon)
            <img src="{{ $storeFavicon }}" alt="Logo" class="w-10 h-10 rounded-lg object-cover bg-white p-0.5 shadow-sm">
        @else
             <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white shadow-sm">
                <i class="fas fa-store text-lg"></i>
             </div>
        @endif
        <div class="flex-1 min-w-0">
            <h1 id="dashboard-title" class="text-base font-bold tracking-wide text-white leading-tight truncate">
                <a href="/" class="hover:text-blue-300 transition-colors" title="{{ __('messages.view_website') }}">
                    {{ $storeName }}
                </a>
            </h1>
            <p class="text-slate-400 text-xs mt-0.5 font-medium tracking-wide truncate">
                {{ $storeTagline }}
            </p>
        </div>
    </div>
 </div>
 <nav class="mt-4 px-3">
  @php
    $isDashboard = request()->routeIs('admin.dashboard');
    $isProductsGroup = request()->routeIs('admin.products*', 'admin.inventory*');
   $isCombosGroup = request()->routeIs('admin.promotions.*', 'admin.combos.*', 'admin.volume_pricing.*', 'admin.promotion_rules.*');
    $isOrdersGroup = request()->routeIs('admin.orders*');
    $isCustomers = request()->routeIs('admin.customers*');
    $isUsersGroup = request()->routeIs('admin.users*');
    $isReportsGroup = request()->routeIs('admin.reports.*');
    $isSettingsGroup = request()->routeIs('admin.settings.*');
    $isBlogGroup = request()->routeIs('admin.posts.*');
  @endphp
  <a href="{{ route('admin.dashboard') }}" class="nav-link flex items-center px-4 py-3 rounded-lg mb-1 {{ $isDashboard ? 'active' : '' }}">
   <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
   <span class="font-medium text-sm">{{ __('messages.dashboard') }}</span>
  </a>
  <div class="mb-1">
   <button onclick="toggleSubmenu('products')" class="nav-link flex items-center justify-between w-full px-4 py-3 rounded-lg {{ $isProductsGroup ? 'active' : '' }}">
    <div class="flex items-center">
     <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
     <span class="text-sm">{{ __('messages.products') }}</span>
    </div>
    <svg id="products-arrow" class="w-4 h-4 transition-transform {{ $isProductsGroup ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
   </button>
    <div id="products-submenu" class="ml-4 mt-1 space-y-1 {{ $isProductsGroup ? '' : 'hidden' }}">
        <a href="{{ route('admin.products') }}" class="flex items-center px-4 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.products') ? 'text-white bg-slate-700' : 'text-slate-300 hover:text-white hover:bg-slate-700' }}"><span class="mr-2">•</span> {{ __('messages.products') }}</a>
        <a href="{{ route('admin.products.create') }}" class="flex items-center px-4 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.products.create') ? 'text-white bg-slate-700' : 'text-slate-300 hover:text-white hover:bg-slate-700' }}"><span class="mr-2">•</span> {{ __('messages.create') }} {{ __('messages.product') }}</a>
        <a href="{{ route('admin.products.categories') }}" class="flex items-center px-4 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.products.categories') ? 'text-white bg-slate-700' : 'text-slate-300 hover:text-white hover:bg-slate-700' }}"><span class="mr-2">•</span> {{ __('messages.categories') }}</a>
        <a href="{{ route('admin.products.collections') }}" class="flex items-center px-4 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.products.collections') ? 'text-white bg-slate-700' : 'text-slate-300 hover:text-white hover:bg-slate-700' }}"><span class="mr-2">•</span> {{ __('messages.collections') }}</a>
        <a href="{{ route('admin.products.attributes') }}" class="flex items-center px-4 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.products.attributes') ? 'text-white bg-slate-700' : 'text-slate-300 hover:text-white hover:bg-slate-700' }}"><span class="mr-2">•</span> {{ __('messages.product_attributes') }}</a>
        <a href="{{ route('admin.inventory') }}" class="flex items-center px-4 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.inventory') ? 'text-white bg-slate-700' : 'text-slate-300 hover:text-white hover:bg-slate-700' }}"><span class="mr-2">•</span> {{ __('messages.inventory') }}</a>
    </div>
  </div>
  <div class="mb-1">
   <button onclick="toggleSubmenu('combos')" class="nav-link flex items-center justify-between w-full px-4 py-3 rounded-lg {{ $isCombosGroup ? 'active' : '' }}">
    <div class="flex items-center">
     <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
     <span class="text-sm">{{ __('messages.promotions') }}</span>
    </div>
    <svg id="combos-arrow" class="w-4 h-4 transition-transform {{ $isCombosGroup ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
   </button>
   <div id="combos-submenu" class="ml-4 mt-1 space-y-1 {{ $isCombosGroup ? '' : 'hidden' }}">
       <a href="{{ route('admin.promotions.overview') }}" class="flex items-center px-4 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.promotions.overview') ? 'text-white bg-slate-700' : 'text-slate-300 hover:text-white hover:bg-slate-700' }}"><span class="mr-2">•</span> {{ __('messages.overview') }}</a>
       <a href="{{ route('admin.combos.index') }}" class="flex items-center px-4 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.combos.*') ? 'text-white bg-slate-700' : 'text-slate-300 hover:text-white hover:bg-slate-700' }}"><span class="mr-2">•</span> {{ __('messages.bundles') }}</a>
       <a href="{{ route('admin.volume_pricing.index') }}" class="flex items-center px-4 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.volume_pricing.*') ? 'text-white bg-slate-700' : 'text-slate-300 hover:text-white hover:bg-slate-700' }}"><span class="mr-2">•</span> {{ __('messages.volume_pricing') }}</a>
       <a href="{{ route('admin.promotion_rules.index') }}" class="flex items-center px-4 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.promotion_rules.*') ? 'text-white bg-slate-700' : 'text-slate-300 hover:text-white hover:bg-slate-700' }}"><span class="mr-2">•</span> {{ __('messages.mix_match_rules') }}</a>
   </div>
  <a href="{{ route('admin.reviews.index') }}" class="nav-link flex items-center px-4 py-3 rounded-lg mb-1 {{ request()->routeIs('admin.reviews*') ? 'active' : '' }}">
    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
    <span class="font-medium text-sm">{{ __('messages.reviews_dashboard') }}</span>
  </a>
  <div class="mb-1">
   <button onclick="toggleSubmenu('orders')" class="nav-link flex items-center justify-between w-full px-4 py-3 rounded-lg {{ $isOrdersGroup ? 'active' : '' }}">
    <div class="flex items-center">
     <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 0 0-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 0 0 2-2M9 5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2"></path></svg>
     <span class="text-sm">{{ __('messages.orders') }}</span>
    </div>
    <svg id="orders-arrow" class="w-4 h-4 transition-transform {{ $isOrdersGroup ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
   </button>
   <div id="orders-submenu" class="ml-4 mt-1 space-y-1 {{ $isOrdersGroup ? '' : 'hidden' }}">
    <a href="{{ route('admin.orders') }}" class="flex items-center px-4 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.orders') && !request()->has('status') ? 'text-white bg-slate-700' : 'text-slate-300 hover:text-white hover:bg-slate-700' }}"><span class="mr-2">•</span> {{ __('messages.all_orders') }}</a>
    <a href="{{ route('admin.orders', ['status' => 'new']) }}" class="flex items-center px-4 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.orders') && request()->query('status') === 'new' ? 'text-white bg-slate-700' : 'text-slate-300 hover:text-white hover:bg-slate-700' }}"><span class="mr-2">•</span> {{ __('messages.processing') }}</a>
     <a href="{{ route('admin.orders', ['status' => 'shipped']) }}" class="flex items-center px-4 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.orders') && request()->query('status') === 'shipped' ? 'text-white bg-slate-700' : 'text-slate-300 hover:text-white hover:bg-slate-700' }}"><span class="mr-2">•</span> {{ __('messages.shipping') }}</a>
    <a href="{{ route('admin.orders', ['status' => 'completed']) }}" class="flex items-center px-4 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.orders') && request()->query('status') === 'completed' ? 'text-white bg-slate-700' : 'text-slate-300 hover:text-white hover:bg-slate-700' }}"><span class="mr-2">•</span> {{ __('messages.completed') }}</a>
    <a href="{{ route('admin.orders', ['status' => 'cancelled']) }}" class="flex items-center px-4 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.orders') && request()->query('status') === 'cancelled' ? 'text-white bg-slate-700' : 'text-slate-300 hover:text-white hover:bg-slate-700' }}"><span class="mr-2">•</span> {{ __('messages.cancelled') }}</a>
   </div>
  </div>
  <a href="{{ route('admin.customers') }}" class="nav-link flex items-center px-4 py-3 rounded-lg mb-1 {{ $isCustomers ? 'active' : '' }}">
   <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
   <span class="text-sm">{{ __('messages.customers') }}</span>
  </a>
  <div class="mb-1">
   <button onclick="toggleSubmenu('blog')" class="nav-link flex items-center justify-between w-full px-4 py-3 rounded-lg {{ $isBlogGroup ? 'active' : '' }}">
    <div class="flex items-center">
     <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
     <span class="text-sm">{{ __('messages.blog') }}</span>
    </div>
    <svg id="blog-arrow" class="w-4 h-4 transition-transform {{ $isBlogGroup ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
   </button>
   <div id="blog-submenu" class="ml-4 mt-1 space-y-1 {{ $isBlogGroup ? '' : 'hidden' }}">
    <a href="{{ route('admin.posts.index') }}" class="flex items-center px-4 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.posts.index') ? 'text-white bg-slate-700' : 'text-slate-300 hover:text-white hover:bg-slate-700' }}"><span class="mr-2">•</span> {{ __('messages.all_posts') }}</a>
    <a href="{{ route('admin.posts.create') }}" class="flex items-center px-4 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.posts.create') ? 'text-white bg-slate-700' : 'text-slate-300 hover:text-white hover:bg-slate-700' }}"><span class="mr-2">•</span> {{ __('messages.new_post') }}</a>
    <a href="{{ route('admin.posts.categories') }}" class="flex items-center px-4 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.posts.categories') ? 'text-white bg-slate-700' : 'text-slate-300 hover:text-white hover:bg-slate-700' }}"><span class="mr-2">•</span> {{ __('messages.categories') }}</a>
    <a href="{{ route('admin.posts.tags') }}" class="flex items-center px-4 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.posts.tags') ? 'text-white bg-slate-700' : 'text-slate-300 hover:text-white hover:bg-slate-700' }}"><span class="mr-2">•</span> {{ __('messages.tags') }}</a>
    <a href="{{ route('admin.posts.comments') }}" class="flex items-center px-4 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.posts.comments') ? 'text-white bg-slate-700' : 'text-slate-300 hover:text-white hover:bg-slate-700' }}"><span class="mr-2">•</span> {{ __('messages.comments') }}</a>
   </div>
  </div>
 <a href="{{ route('admin.users') }}" class="nav-link flex items-center px-4 py-3 rounded-lg mb-1 {{ $isUsersGroup ? 'active' : '' }}">
   <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
   <span class="text-sm">{{ __('messages.users') }}</span>
  </a>
  <div class="mb-1">
   <button onclick="toggleSubmenu('reports')" class="nav-link flex items-center justify-between w-full px-4 py-3 rounded-lg {{ $isReportsGroup ? 'active' : '' }}">
    <div class="flex items-center">
     <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
     <span class="text-sm">{{ __('messages.reports') }}</span>
    </div>
    <svg id="reports-arrow" class="w-4 h-4 transition-transform {{ $isReportsGroup ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
   </button>
   <div id="reports-submenu" class="ml-4 mt-1 space-y-1 {{ $isReportsGroup ? '' : 'hidden' }}">
    <a href="{{ route('admin.reports.index') }}" class="flex items-center px-4 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.reports.index') ? 'text-white bg-slate-700' : 'text-slate-300 hover:text-white hover:bg-slate-700' }}"><span class="mr-2">•</span> {{ __('messages.overview') }}</a>
    <a href="{{ route('admin.reports.revenue') }}" class="flex items-center px-4 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.reports.revenue') ? 'text-white bg-slate-700' : 'text-slate-300 hover:text-white hover:bg-slate-700' }}"><span class="mr-2">•</span> {{ __('messages.revenue') }}</a>
    <a href="{{ route('admin.reports.products') }}" class="flex items-center px-4 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.reports.products') ? 'text-white bg-slate-700' : 'text-slate-300 hover:text-white hover:bg-slate-700' }}"><span class="mr-2">•</span> {{ __('messages.best_selling_products') }}</a>
    <a href="{{ route('admin.reports.customers') }}" class="flex items-center px-4 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.reports.customers') ? 'text-white bg-slate-700' : 'text-slate-300 hover:text-white hover:bg-slate-700' }}"><span class="mr-2">•</span> {{ __('messages.customers') }}</a>
   </div>
  </div>
  <div class="mb-1">
   <button onclick="toggleSubmenu('settings')" class="nav-link flex items-center justify-between w-full px-4 py-3 rounded-lg {{ $isSettingsGroup ? 'active' : '' }}">
    <div class="flex items-center">
     <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
     <span class="text-sm">{{ __('messages.settings') }}</span>
    </div>
    <svg id="settings-arrow" class="w-4 h-4 transition-transform {{ $isSettingsGroup ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
   </button>
   <div id="settings-submenu" class="ml-4 mt-1 space-y-1 {{ $isSettingsGroup ? '' : 'hidden' }}">
    <a href="{{ route('admin.settings.general') }}" class="flex items-center px-4 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.settings.general') ? 'text-white bg-slate-700' : 'text-slate-300 hover:text-white hover:bg-slate-700' }}"><span class="mr-2">•</span> {{ __('messages.overview') }}</a>
    <a href="{{ route('admin.settings.store') }}" class="flex items-center px-4 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.settings.store') ? 'text-white bg-slate-700' : 'text-slate-300 hover:text-white hover:bg-slate-700' }}"><span class="mr-2">•</span> {{ __('messages.store_info') }}</a>
    <a href="{{ route('admin.settings.payment') }}" class="flex items-center px-4 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.settings.payment') ? 'text-white bg-slate-700' : 'text-slate-300 hover:text-white hover:bg-slate-700' }}"><span class="mr-2">•</span> {{ __('messages.payment') }}</a>
    <a href="{{ route('admin.settings.shipping') }}" class="flex items-center px-4 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.settings.shipping') ? 'text-white bg-slate-700' : 'text-slate-300 hover:text-white hover:bg-slate-700' }}"><span class="mr-2">•</span> {{ __('messages.shipping') }}</a>
   </div>
  </div>
 </nav>
</aside>
