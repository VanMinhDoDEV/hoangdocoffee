<!-- SIDEBAR SECTION START -->
<div class="ul-sidebar">
    <!-- header -->
    <div class="ul-sidebar-header">
        <div class="ul-sidebar-header-logo">
            @php
                $storeSettings = [];
                try {
                    $raw = \Illuminate\Support\Facades\Storage::disk('local')->exists('settings.json')
                        ? \Illuminate\Support\Facades\Storage::disk('local')->get('settings.json')
                        : null;
                    $data = $raw ? json_decode($raw, true) : [];
                    $storeSettings = $data['store'] ?? [];
                } catch (\Throwable $e) {
                    $storeSettings = [];
                }
            @endphp
            <a href="{{ route('home') }}">
                <img src="{{ $storeSettings['header_logo_url'] ?? asset('assets/img/logo.svg') }}"
                     alt="{{ $storeSettings['name'] ?? 'Logo' }}"
                     class="logo">
            </a>
        </div>
        <!-- sidebar closer -->
        <button class="ul-sidebar-closer"><i class="flaticon-close"></i></button>
    </div>

    <div class="ul-sidebar-header-nav-wrapper d-block d-lg-none"></div>

    <!-- About Section - Chuyên nghiệp, phù hợp thương hiệu cà phê cao cấp -->
    <div class="ul-sidebar-about d-none d-lg-block">
        <span class="title">Về {{ $storeSettings['name'] ?? 'Chúng tôi' }}</span>
        <p class="mb-4">
            {{ $storeSettings['name'] ?? 'Thương hiệu' }} là biểu tượng của hương vị cà phê đích thực. 
            Chúng tôi mang đến những hạt cà phê chất lượng cao cấp, kết hợp hoàn hảo giữa truyền thống và hiện đại.
        </p>
        <p class="mb-0">
            Với tôn chỉ "Chất lượng là danh dự", chúng tôi cam kết đồng hành cùng bạn trên hành trình thưởng thức cà phê 
            qua từng sản phẩm được chế biến tỉ mỉ từ những hạt cà phê chọn lọc.
        </p>
    </div>

    <!-- Thông tin liên hệ bổ sung (tùy chọn nâng cao tính chuyên nghiệp) -->
    <div class="ul-sidebar-contact-info mt-6 d-none d-lg-block space-y-3 text-sm text-slate-600">
        <div class="flex items-center gap-3">
            <i class="flaticon-placeholder text-indigo-600"></i>
            <span>{{ $storeSettings['address'] ?? 'Hà Nội / TP. Hồ Chí Minh' }}</span>
        </div>
        <div class="flex items-center gap-3">
            <i class="flaticon-phone-call text-indigo-600"></i>
            <span>{{ $storeSettings['phone'] ?? '1900 1234' }}</span>
        </div>
        <div class="flex items-center gap-3">
            <i class="flaticon-mail text-indigo-600"></i>
            <span>{{ $storeSettings['email'] ?? 'hello@yourbrand.com' }}</span>
        </div>
    </div>

    <!-- sidebar footer -->
    <div class="ul-sidebar-footer">
        <span class="ul-sidebar-footer-title">Theo dõi chúng tôi</span>
        <div class="ul-sidebar-footer-social">
            @if(!empty($storeSettings['facebook']))
                <a href="{{ $storeSettings['facebook'] }}" target="_blank" aria-label="Facebook">
                    <i class="flaticon-facebook-app-symbol"></i>
                </a>
            @endif
            @if(!empty($storeSettings['instagram']))
                <a href="{{ $storeSettings['instagram'] }}" target="_blank" aria-label="Instagram">
                    <i class="flaticon-instagram"></i>
                </a>
            @endif
            @if(!empty($storeSettings['tiktok']))
                <a href="{{ $storeSettings['tiktok'] }}" target="_blank" aria-label="TikTok">
                    <i class="flaticon-tiktok"></i>
                </a>
            @endif
            @if(!empty($storeSettings['youtube']))
                <a href="{{ $storeSettings['youtube'] }}" target="_blank" aria-label="YouTube">
                    <i class="flaticon-youtube"></i>
                </a>
            @endif
        </div>
    </div>
</div>
<!-- SIDEBAR SECTION END -->