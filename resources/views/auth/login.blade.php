<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
 <head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ __('messages.login_register') }} | {{ $storeSettings['name'] ?? 'Shop06' }}</title>
  <link rel="icon" type="image/x-icon" href="{{ !empty($storeSettings['favicon']) ? asset($storeSettings['favicon']) : asset('favicon.ico') }}">
  @vite(['resources/css/app.css','resources/js/app.js'])
  <style>
    body { box-sizing: border-box; }
    html, body { height: 100%; overflow-y: scroll; }
    body { scrollbar-gutter: stable; }
    .form-transition { transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); }
    .slide-enter { opacity: 0; transform: translateX(-20px); }
    .slide-active { opacity: 1; transform: translateX(0); }
    .tab-slider { position: absolute; top: 4px; bottom: 4px; width: calc(50% - 4px); background: linear-gradient(to right, #3b82f6, #06b6d4); border-radius: 6px; transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1); box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); z-index: 0; }
    .tab-slider.register-active { transform: translateX(100%); }
    .tab-button { position: relative; z-index: 1; transition: color 0.3s ease; }
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
      @if(isset($storeSettings['header_logo_url']) && $storeSettings['header_logo_url'])
          <img src="{{ $storeSettings['header_logo_url'] }}" alt="Logo" class="h-16 mx-auto mb-4 object-contain">
      @else
          <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-full mb-4">
           <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
          </div>
      @endif
      <h1 class="text-2xl font-bold text-gray-800">{{ isset($storeSettings['name']) && $storeSettings['name'] ? $storeSettings['name'] : 'ShopHub' }}</h1>
     </header>
     <nav class="relative flex mb-8 bg-gray-100 rounded-lg p-1" role="tablist" aria-label="{{ __('messages.login_register') }}">
      <div id="tab-slider" class="tab-slider"></div>
      <button id="login-tab" class="tab-button flex-1 py-2 px-4 text-sm font-medium rounded-md text-white" role="tab" aria-selected="true" aria-controls="login-panel">{{ __('messages.login') }}</button>
      <button id="register-tab" class="tab-button flex-1 py-2 px-4 text-sm font-medium rounded-md text-gray-600" role="tab" aria-selected="false" aria-controls="register-panel">{{ __('messages.register') }}</button>
     </nav>
     <section id="login-panel" class="form-transition slide-active" role="tabpanel" aria-labelledby="login-tab">
      <h2 class="text-xl font-semibold text-gray-800 mb-6">{{ __('messages.welcome_back') }}</h2>
      <form id="login-form" method="post" action="{{ route('login.post') }}">
       @csrf
       <div class="mb-4"><label for="login-email" class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.email') }}</label><input type="email" id="login-email" name="email" value="{{ old('email') }}" class="input-primary" placeholder="email@example.com" required></div>
       <div class="mb-6"><label for="login-password" class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.password') }}</label><input type="password" id="login-password" name="password" class="input-primary" placeholder="••••••••" required></div>
       <div class="flex items-center justify-between mb-6"><label class="flex items-center"><input type="checkbox" name="remember" class="w-4 h-4 text-blue-500 border-gray-300 rounded focus:ring-blue-500"><span class="ml-2 text-sm text-gray-600">{{ __('messages.remember_me') }}</span></label><a href="#" class="text-sm text-blue-500 hover:text-blue-600">{{ __('messages.forgot_password') }}</a></div>
       <button type="submit" class="w-full bg-gradient-to-r from-blue-500 to-cyan-500 text-white py-3 px-4 rounded-lg font-medium hover:from-blue-600 hover:to-cyan-600">{{ __('messages.login') }}</button>
       @error('email')<div class="mt-4 p-3 rounded-lg bg-red-100 text-red-700">{{ $message }}</div>@enderror
      </form>
     </section>
     <section id="register-panel" class="form-transition hidden" role="tabpanel" aria-labelledby="register-tab">
      <h2 class="text-xl font-semibold text-gray-800 mb-6">{{ __('messages.create_new_account') }}</h2>
      <form id="register-form" method="post" action="{{ route('register.post') }}">
       @csrf
       <div class="mb-4"><label for="register-name" class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.full_name') }}</label><input type="text" id="register-name" name="name" class="input-primary" placeholder="Nguyễn Văn A" required></div>
       <div class="mb-4"><label for="register-email" class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.email') }}</label><input type="email" id="register-email" name="email" class="input-primary" placeholder="email@example.com" required></div>
       <div class="mb-4"><label for="register-password" class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.password') }}</label><input type="password" id="register-password" name="password" class="input-primary" placeholder="••••••••" required></div>
       <div class="mb-6"><label for="register-confirm" class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.confirm_password') }}</label><input type="password" id="register-confirm" name="password_confirmation" class="input-primary" placeholder="••••••••" required></div>
       <div class="mb-6"><label class="flex items-start"><input type="checkbox" class="w-4 h-4 text-blue-500 border-gray-300 rounded focus:ring-blue-500 mt-1" required><span class="ml-2 text-sm text-gray-600">{{ __('messages.agree_terms') }}</span></label></div>
       <button type="submit" class="w-full bg-gradient-to-r from-blue-500 to-cyan-500 text-white py-3 px-4 rounded-lg font-medium hover:from-blue-600 hover:to-cyan-600">{{ __('messages.register') }}</button>
      </form>
     </section>
     <footer class="mt-8">
      <div class="relative"><div class="absolute inset-0 flex items-center"><div class="w-full border-t border-gray-300"></div></div><div class="relative flex justify-center text-sm"><span class="px-4 bg-white text-gray-500">{{ __('messages.or_continue_with') }}</span></div></div>
      <div class="mt-6 grid grid-cols-2 gap-3"><button class="flex items-center justify-center px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50"><svg class="w-5 h-5 mr-2" viewBox="0 0 24 24"><path fill="#EA4335" d="M5.26620003,9.76452941 C6.19878754,6.93863203 8.85444915,4.90909091 12,4.90909091 C13.6909091,4.90909091 15.2181818,5.50909091 16.4181818,6.49090909 L19.9090909,3 C17.7818182,1.14545455 15.0545455,0 12,0 C7.27006974,0 3.1977497,2.69829785 1.23999023,6.65002441 L5.26620003,9.76452941 Z" /><path fill="#34A853" d="M16.0407269,18.0125889 C14.9509167,18.7163016 13.5660892,19.0909091 12,19.0909091 C8.86648613,19.0909091 6.21911939,17.076871 5.27698177,14.2678769 L1.23746264,17.3349879 C3.19279051,21.2936293 7.26500293,24 12,24 C14.9328362,24 17.7353462,22.9573905 19.834192,20.9995801 L16.0407269,18.0125889 Z" /><path fill="#4A90E2" d="M19.834192,20.9995801 C22.0291676,18.9520994 23.4545455,15.903663 23.4545455,12 C23.4545455,11.2909091 23.3454545,10.5272727 23.1818182,9.81818182 L12,9.81818182 L12,14.4545455 L18.4363636,14.4545455 C18.1187732,16.013626 17.2662994,17.2212117 16.0407269,18.0125889 L19.834192,20.9995801 Z" /><path fill="#FBBC05" d="M5.27698177,14.2678769 C5.03832634,13.556323 4.90909091,12.7937589 4.90909091,12 C4.90909091,11.2182781 5.03443647,10.4668121 5.26620003,9.76452941 L1.23999023,6.65002441 C0.43658717,8.26043162 0,10.0753848 0,12 C0,13.9195484 0.444780743,15.7301709 1.23746264,17.3349879 L5.27698177,14.2678769 Z" /></svg><span class="text-sm font-medium text-gray-700">Google</span></button><button class="flex items-center justify-center px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50"><svg class="w-5 h-5 mr-2" fill="#1877F2" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" /></svg><span class="text-sm font-medium text-gray-700">Facebook</span></button></div>
     </footer>
    </div>
   </div>
  </main>
  <script>
    const loginTab=document.getElementById('login-tab');
    const registerTab=document.getElementById('register-tab');
    const loginPanel=document.getElementById('login-panel');
    const registerPanel=document.getElementById('register-panel');
    const tabSlider=document.getElementById('tab-slider');
    function updateTabSlider(){tabSlider.style.background='linear-gradient(to right,#3b82f6,#06b6d4)'}
    loginTab.addEventListener('click',()=>{tabSlider.classList.remove('register-active');loginTab.style.color='#ffffff';loginTab.classList.remove('text-gray-600');loginTab.setAttribute('aria-selected','true');registerTab.style.color='#4b5563';registerTab.classList.add('text-gray-600');registerTab.setAttribute('aria-selected','false');registerPanel.classList.remove('slide-active');registerPanel.classList.add('slide-enter');setTimeout(()=>{registerPanel.classList.add('hidden');loginPanel.classList.remove('hidden');loginPanel.classList.remove('slide-enter');setTimeout(()=>{loginPanel.classList.add('slide-active')},10)},150)});
    registerTab.addEventListener('click',()=>{tabSlider.classList.add('register-active');registerTab.style.color='#ffffff';registerTab.classList.remove('text-gray-600');registerTab.setAttribute('aria-selected','true');loginTab.style.color='#4b5563';loginTab.classList.add('text-gray-600');loginTab.setAttribute('aria-selected','false');loginPanel.classList.remove('slide-active');loginPanel.classList.add('slide-enter');setTimeout(()=>{loginPanel.classList.add('hidden');registerPanel.classList.remove('hidden');registerPanel.classList.remove('slide-enter');setTimeout(()=>{registerPanel.classList.add('slide-active')},10)},150)});
    updateTabSlider();

    // Check hash or path to switch tab
    if(window.location.hash === '#register' || window.location.pathname === '/register') {
        registerTab.click();
    }
  </script>
 </body>
 </html>
