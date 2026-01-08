<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
 <head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $storeSettings['name'] ?? __('messages.admin_login_title') }} - {{ __('messages.login') }}</title>
  @if(isset($storeSettings['favicon']) && $storeSettings['favicon'])
      <link rel="icon" href="{{ $storeSettings['favicon'] }}">
  @endif
  @vite(['resources/css/app.css','resources/js/app.js'])
  <style>
    body { box-sizing: border-box; }
    html, body { height: 100%; overflow-y: scroll; }
    body { scrollbar-gutter: stable; }
    .form-transition { transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); }
  </style>
  <style>@view-transition { navigation: auto; }</style>
 </head>
 <body class="w-full min-h-full">
  <div class="absolute top-4 right-4 z-10 flex gap-2">
      <a href="{{ route('lang.switch', 'vi') }}" class="px-3 py-1 rounded {{ app()->getLocale() == 'vi' ? 'bg-blue-100 text-blue-700 font-bold' : 'text-gray-600 hover:bg-gray-100' }}">VI</a>
      <a href="{{ route('lang.switch', 'en') }}" class="px-3 py-1 rounded {{ app()->getLocale() == 'en' ? 'bg-blue-100 text-blue-700 font-bold' : 'text-gray-600 hover:bg-gray-100' }}">EN</a>
  </div>
  <main class="w-full min-h-full flex items-center justify-center p-4 bg-white">
   <div class="w-full max-w-md">
    <div class="bg-white rounded-2xl shadow-2xl p-8 border border-gray-200">
     <header class="text-center mb-8">
       @if(isset($storeSettings['logo_url']) && $storeSettings['logo_url'])
           <img src="{{ $storeSettings['logo_url'] }}" alt="{{ $storeSettings['name'] ?? 'Logo' }}" class="h-16 mx-auto mb-4 object-contain">
       @elseif(isset($storeSettings['favicon']) && $storeSettings['favicon'])
           <img src="{{ $storeSettings['favicon'] }}" alt="Favicon" class="h-16 w-16 mx-auto mb-4 object-contain rounded-full">
       @else
           <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-full mb-4">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
           </div>
       @endif
       <h1 class="text-2xl font-bold text-gray-800">
           {{ $storeSettings['name'] ?? __('messages.admin_login_title') }}
       </h1>
      </header>
     
     <section id="login-panel" class="form-transition" role="tabpanel">
      <form id="login-form" method="post" action="{{ route('admin.login.post') }}">
       @csrf
       <div class="mb-4"><label for="login-email" class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.email') }}</label><input type="email" id="login-email" name="email" value="{{ old('email') }}" class="input-primary" placeholder="email@example.com" required></div>
       <div class="mb-6"><label for="login-password" class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.password') }}</label><input type="password" id="login-password" name="password" class="input-primary" placeholder="••••••••" required></div>
       <div class="flex items-center justify-between mb-6"><label class="flex items-center"><input type="checkbox" name="remember" class="w-4 h-4 text-blue-500 border-gray-300 rounded focus:ring-blue-500"><span class="ml-2 text-sm text-gray-600">{{ __('messages.remember_me') }}</span></label></div>
       <button type="submit" class="w-full bg-gradient-to-r from-blue-500 to-cyan-500 text-white py-3 px-4 rounded-lg font-medium hover:from-blue-600 hover:to-cyan-600">{{ __('messages.login') }}</button>
       @error('email')<div class="mt-4 p-3 rounded-lg bg-red-100 text-red-700">{{ $message }}</div>@enderror
      </form>
     </section>

     <footer class="mt-8 text-center">
        <a href="/" class="text-sm text-gray-500 hover:text-blue-500 flex items-center justify-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            {{ __('messages.back_to_shop') }}
        </a>
     </footer>
    </div>
   </div>
  </main>
 </body>
</html>