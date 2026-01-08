<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', $storeSettings['name'] ?? 'Shop06')</title>
    <link rel="icon" type="image/x-icon" href="{{ !empty($storeSettings['favicon']) ? asset($storeSettings['favicon']) : asset('favicon.ico') }}">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css','resources/js/app.js'])
    @else
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        <style>body{font-family:'Instrument Sans',ui-sans-serif,system-ui,sans-serif}</style>
    @endif
</head>
<body class="min-h-screen bg-gray-50 ">
    <nav class="sticky top-0 z-40 bg-white/75 backdrop-blur border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">
            <a href="{{ route('products.index') }}" class="text-lg font-semibold">{{ $storeSettings['name'] ?? 'Shop06' }}</a>
            <div class="flex items-center gap-6 text-sm">
                <a href="{{ route('products.index') }}" class="text-gray-700 hover:text-black">Sản phẩm</a>
                <a href="{{ route('blog.index') }}" class="text-gray-700 hover:text-black">Blog</a>
                <a href="{{ route('cart.index') }}" class="text-gray-700 hover:text-black">Giỏ hàng</a>
                @auth
                    <form method="post" action="{{ route('logout') }}">
                        @csrf
                        <button class="text-gray-700 hover:text-black">Đăng xuất</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-black">Đăng nhập</a>
                @endauth
            </div>
        </div>
    </nav>
    <main class="max-w-7xl mx-auto px-4 py-8">
        @yield('content')
    </main>
</body>
</html>
