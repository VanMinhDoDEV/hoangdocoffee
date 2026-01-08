@extends('client.layouts.master')

@section('title', 'Bộ Sưu Tập | Hoang Do Coffee')

@section('content')
    <!-- Breadcrumb Start -->
    @include('client.components.breadcrumb', [
        'title' => 'Bộ Sưu Tập',
        'items' => [
            ['label' => 'Trang chủ', 'url' => route('home')],
            ['label' => 'Bộ Sưu Tập', 'url' => '']
        ]
    ])
    <!-- Breadcrumb End -->

    <!-- Collections Section Start -->
    <div class="section collection section-padding">
        <div class="container-fluid px-lg-5">
            @foreach($collections as $collection)
            <div class="row mb-5 align-items-stretch">
                <!-- Column 1: Collection Info (25% on PC) -->
                <div class="col-xl-3 col-lg-3 col-md-12 mb-4 mb-lg-0">
                    <div class="collection-banner h-100 d-flex flex-column">
                        <a href="{{ route('bosuutap.show', $collection->slug) }}" class="flex-grow-1 overflow-hidden">
                            <img loading="lazy" src="{{ $collection->image_url ? asset($collection->image_url) : asset('assets/images/banner/banner-1.jpg') }}" alt="{{ $collection->name }}" class="w-100 h-100 object-fit-cover" style="min-height: 300px; object-fit: cover;">
                        </a>
                        <div class="collection-info">
                            <h3 class="title font-weight-bold mb-2" style="font-size: 24px;">
                                <a href="{{ route('bosuutap.show', $collection->slug) }}">{{ $collection->name }}</a>
                            </h3>
                        </div>
                    </div>
                </div>

                <!-- Column 2,3,4: Products Slider (75% on PC) -->
                <div class="col-xl-9 col-lg-9 col-md-12">
                    <div class="product-slider swiper">
                        <div class="swiper-wrapper">
                            @foreach($collection->products as $product)
                            <div class="swiper-slide">
                                @include('client.components.product-item', ['product' => $product])
                            </div>
                            @endforeach
                        </div>
                        <!-- Add Arrows -->
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                    </div>
                </div>
            </div>
            @if(!$loop->last)
                <hr class="mb-5">
            @endif
            @endforeach
        </div>
    </div>
    <!-- Collections Section End -->
@endsection

@push('scripts')
<script>
    var swiper = new Swiper(".product-slider", {
        slidesPerView: 1,
        spaceBetween: 20,
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
        breakpoints: {
            576: {
                slidesPerView: 2,
                spaceBetween: 20,
            },
            768: {
                slidesPerView: 3,
                spaceBetween: 20,
            },
            992: {
                slidesPerView: 3,
                spaceBetween: 20,
            },
            1200: {
                slidesPerView: 4,
                spaceBetween: 20,
            },
            1600: {
                slidesPerView: 4, // Ensure 4 items on very large screens too
                spaceBetween: 30,
            },
        },
    });
</script>
@endpush
