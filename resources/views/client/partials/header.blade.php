@php
    $storeSettings = [];
    $menus = [];
    try {
        $raw = \Illuminate\Support\Facades\Storage::disk('local')->exists('settings.json')
            ? \Illuminate\Support\Facades\Storage::disk('local')->get('settings.json')
            : null;
        $data = $raw ? json_decode($raw, true) : [];
        $storeSettings = $data['store'] ?? [];
        $menus = $data['menus'] ?? [];
    } catch (\Throwable $e) {}

    $primaryMenu = $menus[0] ?? [];
    $items = $primaryMenu['items'] ?? [];
@endphp

<div class="header sticky-header section">
    <div class="container-fluid">
        <div class="row align-items-center">

            <!-- Logo Start -->
            <div class="col-lg-2 col">
                <div class="header-logo">
                    <a href="{{ route('home') }}">
                        <img src="{{ !empty($storeSettings['header_logo_url']) ? $storeSettings['header_logo_url'] : asset('assets/images/logo/logo.webp') }}" width="125" height="42" alt="{{ $storeSettings['name'] ?? 'Logo' }}">
                    </a>
                </div>
            </div>
            <!-- Logo End -->

            <!-- Menu Start -->
            <div class="col d-none d-lg-block">
                <nav class="main-menu">
                    <ul>
                        @if(count($items) > 0)
                            @foreach($items as $item)
                                <li class="{{ !empty($item['children']) ? 'has-sub-menu' : '' }}">
                                    <a href="{{ $item['url'] ?: '#' }}">{{ $item['name'] }}</a>
                                    @if(!empty($item['children']))
                                        <ul class="sub-menu">
                                            @foreach($item['children'] as $child)
                                                <li><a href="{{ $child['url'] ?: '#' }}">{{ $child['name'] }}</a></li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                            @endforeach
                        @else
                            <li class="has-sub-menu">
                                <a href="{{ route('home') }}">Home</a>
                            </li>
                            <li>
                                <a href="#">Shop</a>
                            </li>
                            <li>
                                <a href="#">About Us</a>
                            </li>
                            <li>
                                <a href="#">Contact</a>
                            </li>
                        @endif
                    </ul>
                </nav>
            </div>
            <!-- Menu End -->

            <!-- Action Start -->
            <div class="col-auto">
                <div class="header-action">
                    <div class="header-action-item">
                        <button class="header-action-toggle" type="button" data-bs-toggle="offcanvas"
                            data-bs-target="#offcanvas-search"><i class="sli-magnifier"></i></button>
                    </div>
                    <div class="header-action-item">
                        <button class="header-action-toggle" type="button" data-bs-toggle="offcanvas"
                            data-bs-target="#offcanvas-cart"><i class="sli-basket-loaded"><span
                                    class="count">@php
                                        $cartCount = 0;
                                        foreach(session('cart.items', []) as $item) {
                                            $cartCount += $item['quantity'] ?? 0;
                                        }
                                        echo $cartCount;
                                    @endphp</span></i> <span class="amount"></span></button>
                    </div>
                    <div class="header-action-item dropdown">
                        <button class="header-action-toggle" type="button" data-bs-toggle="dropdown"><i
                                class="sli-settings"></i></button>
                        <div class="dropdown-menu header-dropdown-menu">
                            <h6 class="header-dropdown-menu-title">Account</h6>
                            <ul>
                                @auth
                                    <li><a href="{{ route('client.dashboard') }}">Dashboard</a></li>
                                    <li>
                                        <form action="{{ route('logout') }}" method="POST">
                                            @csrf
                                            <button type="submit" style="background:none;border:none;padding:0;color:inherit;font:inherit;cursor:pointer;">Logout</button>
                                        </form>
                                    </li>
                                @else
                                    <li><a href="{{ route('login') }}">Login</a></li>
                                    <li><a href="{{ route('login') }}#register">Register</a></li>
                                @endauth
                            </ul>
                        </div>
                    </div>
                    <div class="header-action-item d-lg-none">
                        <button class="header-action-toggle" type="button" data-bs-toggle="offcanvas"
                            data-bs-target="#offcanvas-header"><i class="sli-menu"></i></button>
                    </div>
                </div>
            </div>
            <!-- Action End -->

        </div>
    </div>
</div>
