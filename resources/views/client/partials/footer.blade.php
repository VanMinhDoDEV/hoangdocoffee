@php
    $storeSettings = [];
    $footerMenus = [];
    try {
        $raw = \Illuminate\Support\Facades\Storage::disk('local')->exists('settings.json')
            ? \Illuminate\Support\Facades\Storage::disk('local')->get('settings.json')
            : null;
        $data = $raw ? json_decode($raw, true) : [];
        $storeSettings = $data['store'] ?? [];
        $menus = $data['menus'] ?? [];
        $footerMenus = array_filter($menus, function($m) {
            return isset($m['type']) && $m['type'] === 'normal';
        });
    } catch (\Throwable $e) {}
@endphp

    <!-- Subscribe Section Start -->
    <div class="h3-subscribe-section section section-padding pt-0">
        <div class="container">
            <div class="section-title section-title-center">
                <p class="title">Newsletter Area</p>
                <h2 class="sub-title">SUBSCRIBE NEWSLETTER</h2>
            </div>

            <div id="mc_embed_signup" class="subscribe-newsletter mx-auto">
                <form id="mc-embedded-subscribe-form" class="validate" novalidate="" target="_blank"
                    name="mc-embedded-subscribe-form" method="post"
                    action="#">
                    <div id="mc_embed_signup_scroll" class="mc-form">
                        <input class="email form-field" type="email" required=""
                            placeholder="Enter your email address..." name="EMAIL" value="">
                        <input id="mc-embedded-subscribe" class="button" type="submit" name="subscribe"
                            value="Subscribe">
                    </div>
                </form>
            </div>

        </div>
    </div>
    <!-- Subscribe Section End -->

    <!-- Footer Section Start -->
    <div class="footer-3-section section ">
        <!-- Footer Top Section Start -->
        <div class="footer-top section">
            <div class="container">
                <div class="row mb-n8 gy-lg-0 gy-4">

                    <!-- Footer Widget Start -->
                    <div class="col-lg-6 col-sm-6 col-12 mb-8">
                        <div class="footer-widget">
                            <a href="{{ route('home') }}">
                                <img loading="lazy" src="{{ !empty($storeSettings['header_logo_url']) ? $storeSettings['header_logo_url'] : asset('assets/images/logo/logo.webp') }}" alt="{{ $storeSettings['name'] ?? 'Logo' }}" width="198" height="70">
                            </a>
                            <ul class="footer-widget-list mb-6">
                                <li><b>Address: {{ $storeSettings['address'] ?? '123 Pall Mall, London England' }}</b></li>
                                <li><b>Email: {{ $storeSettings['email'] ?? 'hello@example.com' }}</b></li>
                                <li><b>Phone: {{ $storeSettings['phone'] ?? '(012) 345 6789' }}</b></li>
                            </ul>
                            <div class="footer-widget-social">
                                <a href="{{ $storeSettings['social_facebook'] ?? '#' }}"><i class="sli-social-facebook"></i></a>
                                <a href="{{ $storeSettings['social_twitter'] ?? '#' }}"><i class="sli-social-twitter"></i></a>
                                <a href="{{ $storeSettings['social_instagram'] ?? '#' }}"><i class="sli-social-instagram"></i></a>
                                <a href="{{ $storeSettings['social_youtube'] ?? '#' }}"><i class="sli-social-youtube"></i></a>
                            </div>
                        </div>
                    </div>
                    <!-- Footer Widget End -->

                    @if(count($footerMenus) > 0)
                        @foreach($footerMenus as $menu)
                        <!-- Footer Widget Start -->
                        <div class="col-lg-2 col-sm-6 col-12 mb-8">
                            <div class="footer-widget">
                                <h5 class="footer-widget-title">{{ $menu['name'] ?? 'Menu' }}</h5>
                                <ul class="footer-widget-list">
                                    @foreach($menu['items'] ?? [] as $item)
                                        <li><a href="{{ $item['url'] ?? '#' }}">{{ $item['name'] }}</a></li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <!-- Footer Widget End -->
                        @endforeach
                    @else
                        <!-- Fallback if no menus are defined -->
                        <div class="col-lg-2 col-sm-6 col-12 mb-8">
                            <div class="footer-widget">
                                <h5 class="footer-widget-title">Information</h5>
                                <ul class="footer-widget-list">
                                    <li><a href="#">Returns Policy</a></li>
                                    <li><a href="#">Support Policy</a></li>
                                    <li><a href="#">Size Guide</a></li>
                                    <li><a href="#">FAQs</a></li>
                                    <li><a href="#">Privacy Policy</a></li>
                                </ul>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
        <!-- Footer Top Section End -->

        <!-- Footer Bottom Section Start -->
        <div class="footer-bottom section">
            <div class="container">
                <div class="row justify-content-between align-items-center mb-n2">

                    <!-- Footer Widget Start -->
                    <div class="col-md-auto col-12 mb-2">
                        <p class="footer-copyright text-center">Copyright <b class="text-primary">{{ $storeSettings['name'] ?? 'Kofi' }}</b> &copy;{{ date('Y') }}
                        </p>
                    </div>
                    <!-- Footer Widget End -->

                    <!-- Footer Widget Start -->
                    <div class="col-md-auto col-12 mb-2">
                        <div class="footer-payment text-center"><img loading="lazy"
                                src="{{ asset('assets/images/footer/footer-payment.png') }}" alt="footer payment" width="342"
                                height="30"></div>
                    </div>
                    <!-- Footer Widget End -->

                </div>
            </div>
        </div>
        <!-- Footer Bottom Section End -->

    </div>
    <!-- Footer Section End -->
