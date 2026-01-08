@props(['title', 'items' => [], 'backgroundImage' => null])

<!-- Breadcrumb Section Start -->
<div class="section">
    <div class="page-banner-section section" style="position: relative; {{ $backgroundImage ? 'background-image: url(' . $backgroundImage . ');' : '' }}">
        <div class="page-banner-overlay" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.4); z-index: 1;"></div>
        <div class="container" style="position: relative; z-index: 2;">
            <div class="breadcrumb-content text-center">
                <h2 class="title text-white">{{ $title }}</h2>
                <ul class="breadcrumb" itemscope itemtype="https://schema.org/BreadcrumbList">
                    @foreach($items as $key => $item)
                        <li class="{{ $loop->last ? 'active' : '' }}" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                            @if(!$loop->last && !empty($item['url']))
                                <a href="{{ $item['url'] }}" itemprop="item">
                                    <span itemprop="name">{{ $item['label'] }}</span>
                                </a>
                            @else
                                <span itemprop="name">{{ $item['label'] }}</span>
                            @endif
                            <meta itemprop="position" content="{{ $loop->iteration }}" />
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- Breadcrumb Section End -->