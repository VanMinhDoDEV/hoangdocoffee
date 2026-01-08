<!DOCTYPE html>
<html class="no-js" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>@yield('title', $storeSettings['name'] ?? 'Hoang Do Coffee')</title>
    <meta name="robots" content="@yield('robots', 'index, follow')" />
    <meta name="description" content="@yield('description', $storeSettings['description'] ?? 'Hoang Do Coffee')">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ !empty($storeSettings['favicon']) ? asset($storeSettings['favicon']) : asset('assets/images/favicon.png') }}">
    <link rel="icon" type="image/png" href="{{ !empty($storeSettings['favicon']) ? asset($storeSettings['favicon']) : asset('assets/images/favicon.png') }}">

    @include('client.partials.styles')
    @stack('head')
</head>

<body>

    @include('client.partials.header')

    @yield('content')

    @include('client.partials.footer')

    @include('client.partials.offcanvas')

    @include('client.components.alert-modal')
    @include('components.toast')

    <button class="scroll-to-top"><i class="sli-arrow-up"></i></button>

    <!-- Quick View Modal (Placeholder) -->
    <div class="quickview-product-modal modal fade" id="exampleProductModal">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="modal-body">
                    <!-- Dynamic Content -->
                </div>
            </div>
        </div>
    </div>

    @include('client.partials.scripts')
</body>

</html>
