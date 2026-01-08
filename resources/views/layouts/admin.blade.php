<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title','Admin')</title>
    <link rel="shortcut icon" type="image/x-icon" href="{{ !empty($storeSettings['favicon']) ? asset($storeSettings['favicon']) : asset('assets/images/favicon.png') }}">
    <link rel="icon" type="image/png" href="{{ !empty($storeSettings['favicon']) ? asset($storeSettings['favicon']) : asset('assets/images/favicon.png') }}">
    @vite(['resources/css/app.css','resources/js/app.js'])
    @stack('styles')
    <style>
        .nav-link{transition:all .2s ease}
        .nav-link:hover{background-color:rgba(255,255,255,.1)}
        .nav-link.active{background-color:rgba(255,255,255,.15);border-left:3px solid #3498db}
        @media (max-width: 767px) {
            .mobile-hidden-force { display: none !important; }
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Sidebar Backdrop -->
    <div id="sidebarBackdrop" class="fixed inset-0 bg-black/50 z-40 hidden md:hidden glass transition-opacity duration-300"></div>

    <div class="w-full min-h-screen flex">
        @include('admin.partials.sidebar')
        <main class="flex-1 overflow-auto md:ml-64 transition-all duration-300">
            @include('admin.partials.topbar')
            <div class="p-8">
                @yield('content')
            </div>
        </main>
    </div>
    <script>
        window.toggleSubmenu = function(id){
            var submenu=document.getElementById(id+'-submenu');
            var arrow=document.getElementById(id+'-arrow');
            if(!submenu||!arrow)return;
            submenu.classList.toggle('hidden');
            arrow.classList.toggle('rotate-180');
        };

        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarBackdrop = document.getElementById('sidebarBackdrop');

            if (sidebarToggle && sidebar && sidebarBackdrop) {
                sidebarToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    console.log('Sidebar toggle clicked');
                    sidebar.classList.toggle('-translate-x-full');
                    sidebarBackdrop.classList.toggle('hidden');
                });

                sidebarBackdrop.addEventListener('click', function() {
                    console.log('Backdrop clicked');
                    sidebar.classList.add('-translate-x-full');
                    sidebarBackdrop.classList.add('hidden');
                });
            } else {
                console.error('Sidebar elements not found', { sidebar, sidebarToggle, sidebarBackdrop });
            }
        });
    </script>
    <x-toast />
    @stack('scripts')
</body>
</html>
