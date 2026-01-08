@extends('client.layouts.master')

@section('title', ($storeSettings['name'] ?? 'Kofi') . (isset($storeSettings['tagline']) && $storeSettings['tagline'] ? ' - ' . $storeSettings['tagline'] : ''))

@section('content')
<!-- Slider Section Start -->
<div class="h3-hero-section section">
    <div class="container-fluid">
        <div class="row mb-n6">

            <div class="col-xl-9 mb-6">
                <div class="hero-slider hero-slider-3 swiper">

                    <div class="swiper-wrapper">

                        <div class="swiper-slide hero-slide-3 "
                            style="background-image: url({{ asset('assets/images/hero-slider/home-3/slide-1.jpg') }});">
                            <div class="container">
                                <div class="hero-slide-3-content">
                                    <h2 class="hero-slide-3-title">CÀ PHÊ RANG XAY</h2>
                                    <p class="hero-slide-3-text">
                                        Cà phê rang mới mỗi ngày, nguyên chất, phù hợp nhiều gu pha.
                                    </p>
                                    <div class="hero-slide-3-button">
                                        <a href="{{ route('products.index') }}"
                                            class="btn btn-outline-dark btn-primary-hover">Tìm Hiểu Ngay <i
                                                class="sli-basket-loaded"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="swiper-slide hero-slide-3 "
                            style="background-image: url({{ asset('assets/images/hero-slider/home-3/slide-2.jpg') }});">
                            <div class="container">
                                <div class="hero-slide-3-content">
                                    <h2 class="hero-slide-3-title">HƯƠNG VỊ CÀ PHÊ</h2>
                                    <p class="hero-slide-3-text">
                                        Mỗi mẻ rang là sự cân bằng giữa hương thơm và vị đậm tự nhiên.
                                    </p>
                                    <div class="hero-slide-3-button">
                                        <a href="{{ route('products.index') }}"
                                            class="btn btn-outline-dark btn-primary-hover">Tìm Hiểu Ngay <i
                                                class="sli-basket-loaded"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="swiper-slide hero-slide-3 "
                            style="background-image: url({{ asset('assets/images/hero-slider/home-3/slide-3.jpg') }});">
                            <div class="container">
                                <div class="hero-slide-3-content text-center mx-auto">
                                    <h2 class="hero-slide-3-title">CÀ PHÊ NGUYÊN CHẤT</h2>
                                    <p class="hero-slide-3-text">
                                        Rang xay đúng cách giúp giữ trọn hương vị thật của hạt cà phê.
                                    </p>
                                    <div class="hero-slide-3-button">
                                        <a href="{{ route('products.index') }}"
                                            class="btn btn-outline-light btn-primary-hover">Tìm Hiểu Ngay <i
                                                class="sli-basket-loaded"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="swiper-pagination"></div>

                    <div class="swiper-button-prev d-none d-md-flex"></div>
                    <div class="swiper-button-next d-none d-md-flex"></div>
                </div>
            </div>

            <div class="col-xl-3 mb-6 d-flex">
                <div
                    class="row row-cols-xl-1 row-cols-sm-3 row-cols-1 flex-xl-column justify-content-between h-100 mb-n6">
                    <div class="col mb-6"><a href="{{ route('products.index') }}" class="banner"><img
                                src="{{ asset('assets/images/banner/hero-3-banner-1.jpg') }}" width="401" height="228"
                                alt="Banner One"></a></div>
                    <div class="col mb-6"><a href="{{ route('products.index') }}" class="banner"><img
                                src="{{ asset('assets/images/banner/hero-3-banner-2.jpg') }}" width="401" height="228"
                                alt="Banner Two"></a></div>
                    <div class="col mb-6"><a href="{{ route('products.index') }}" class="banner"><img
                                src="{{ asset('assets/images/banner/hero-3-banner-3.jpg') }}" width="401" height="228"
                                alt="Banner Three"></a></div>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- Slider Section End -->

<!-- Feature Section Start -->
<div class="h3-feature-section section section-padding">
    <div class="container">
        <div class="section-title section-title-center">
            <p class="title">Chúng tôi mang đến điều gì</p>
            <h2 class="sub-title">KHÁM PHÁ DỊCH VỤ CÀ PHÊ</h2>

        </div>
        <div class="row row-cols-lg-3 row-cols-sm-2 row-cols-1 mb-n6">

            <div class="col mb-4">
                <div class="feature-2">
                    <div class="feature-icon">
                        <img loading="lazy" src="{{ asset('assets/images/feature/two/feature-1.png') }}"
                            alt="Các loại cà phê" width="80" height="80">
                    </div>
                    <div class="feature-content">
                        <h3 class="feature-title">Đa dạng hạt cà phê</h3>
                        <p class="feature-text">
                            Tuyển chọn nhiều loại hạt phù hợp từng gu thưởng thức.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col mb-4">
                <div class="feature-2">
                    <div class="feature-icon">
                        <img loading="lazy" src="{{ asset('assets/images/feature/two/feature-2.png') }}"
                            alt="Rang cà phê" width="80" height="80">
                    </div>
                    <div class="feature-content">
                        <h3 class="feature-title">Rang mới mỗi ngày</h3>
                        <p class="feature-text">
                            Rang theo mẻ nhỏ để giữ trọn hương thơm tự nhiên.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col mb-4">
                <div class="feature-2">
                    <div class="feature-icon">
                        <img loading="lazy" src="{{ asset('assets/images/feature/two/feature-3.png') }}"
                            alt="Xay theo yêu cầu" width="80" height="80">
                    </div>
                    <div class="feature-content">
                        <h3 class="feature-title">Xay theo yêu cầu</h3>
                        <p class="feature-text">
                            Xay đúng cỡ cho phin, máy pha hoặc cold brew.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col mb-4">
                <div class="feature-2">
                    <div class="feature-icon">
                        <img loading="lazy" src="{{ asset('assets/images/feature/two/feature-4.png') }}"
                            alt="Cà phê nguyên chất" width="80" height="80">
                    </div>
                    <div class="feature-content">
                        <h3 class="feature-title">Nguyên chất 100%</h3>
                        <p class="feature-text">
                            Không pha tạp, không hương liệu, đúng vị cà phê thật.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col mb-4">
                <div class="feature-2">
                    <div class="feature-icon">
                        <img loading="lazy" src="{{ asset('assets/images/feature/two/feature-5.png') }}"
                            alt="Chất lượng ổn định" width="80" height="80">
                    </div>
                    <div class="feature-content">
                        <h3 class="feature-title">Chất lượng ổn định</h3>
                        <p class="feature-text">
                            Kiểm soát hương vị đồng đều qua từng mẻ rang.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col mb-4">
                <div class="feature-2">
                    <div class="feature-icon">
                        <img loading="lazy" src="{{ asset('assets/images/feature/two/feature-6.png') }}"
                            alt="Tư vấn cà phê" width="80" height="80">
                    </div>
                    <div class="feature-content">
                        <h3 class="feature-title">Tư vấn đúng gu</h3>
                        <p class="feature-text">
                            Hỗ trợ chọn loại cà phê phù hợp với khẩu vị của bạn.
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Feature Section End -->

<!-- About Section Start -->
<div class="h3-about-section section section-padding">
    <div class="container">
        <div class="row row-cols-lg-2 row-cols-1 flex-lg-row-reverse align-items-center mb-n6">

            <div class="col mb-6">
                <img class="about-us-img" loading="lazy" src="{{ asset('assets/images/others/thanh-vien-hoang-do-coffee.png') }}" alt="about us image"
                    width="560" height="472">
            </div>

            <div class="col mb-6">
                <div class="about-us-content">
                    <span class="sub-title">#về chúng tôi</span>
                    <h3 class="about-title">Chúng tôi là doanh nghiệp cung ứng cà phê <span class="accent-color">số 1 thị trường</span> Thanh Hóa</h3>
                    <div class="accent-color about-title-text">
                        <p>The coffee shop was a popular place to hang out and meet up with friends. It was a
                            typical coffee shop with wood paneled walls, and tables set up in the middle of the
                            room. The smell of coffee hung in the air, enticing the customers to come in. </p>
                    </div>
                    <div class="d-flex align-items-start mt-4 mb-4">
                        <div class="me-3">
                            <img class="w-80 h-80" src="{{ asset('assets/images/feature/two/feature-4.png') }}" alt="icon"
                                class="coffee-icon">
                        </div>
                        <div>
                            <h5 class="fw-bold reason-title">Lý do bạn nên lựa chọn sản phẩm của chúng tôi</h5>
                            <p class="small text-muted mb-0">
                                Reasonable. The generated Lorem Ipsum is therefore always free from repetition,
                                injected humour, or non-characteristic words etc.
                            </p>
                        </div>
                    </div>
                    <div class="row align-items-center">
                        <div class="col-4">
                            <div class="number-year">10.</div>
                            <p class="accent-color text-center fw-bold mt-2 mb-0">Năm thăng trầm với hạt cafe</p>
                        </div>
                        <div class="col-8">
                            <p class="small text-muted">
                                It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English
                            </p>
                            <div class="action-links">
                                <a href="#" class="link"><i class="sli-arrow-right"></i>Tìm hiểu thêm</a>
                                <a href="#" class="link"><i class="sli-arrow-right"></i>Xem hồ sơ</a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
<!-- About Section End -->

<!-- Featured Products Section Start -->
<div class="section section-padding pt-0">
    <div class="container">
        <div class="section-title section-title-center">
            <h2 class="sub-title">Sản phẩm nổi bật</h2>
        </div>
        <div class="row row-cols-lg-3 row-cols-md-2 row-cols-1 gy-4">
            @if(isset($featuredProducts) && $featuredProducts->count() > 0)
            @foreach($featuredProducts as $product)
            <div class="col">
                @include('client.components.product-item', ['product' => $product])
            </div>
            @endforeach
            @endif
        </div>
    </div>
</div>
<!-- Featured Products Section End -->

<!-- Product Section Start -->
<!-- <div class="h3-product-section section section-padding pt-0">
        <div class="container">
            <div class="section-title section-title-center">
                <p class="title">Best Collections</p>
                <h2 class="sub-title">FEATURED COLLECTION</h2>

            </div>

            <div class="nav product-tab-nav justify-content-center">
                @foreach($collections as $index => $collection)
                    <button class="{{ $index === 0 ? 'active' : '' }}" data-bs-toggle="tab" data-bs-target="#collection-{{ $collection->id }}">{{ $collection->name }}</button>
                @endforeach
            </div>
            <div class="tab-content">
                @foreach($collections as $index => $collection)
                    <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}" id="collection-{{ $collection->id }}">
                        <div class="product-carousel swiper">
                            <div class="swiper-wrapper">
                                @if($collection->products->isNotEmpty())
                                    @foreach($collection->products as $product)
                                        <div class="swiper-slide">
                                            @include('client.components.product-item', ['product' => $product])
                                        </div>
                                    @endforeach
                                @else
                                    <div class="col-12 text-center">
                                        <p>No products found in this collection.</p>
                                    </div>
                                @endif
                            </div>

                            <div class="swiper-pagination d-md-none"></div>
                            <div class="swiper-button-prev d-none d-md-flex"></div>
                            <div class="swiper-button-next d-none d-md-flex"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div> -->
<!-- Product Section End -->

<!-- Product Section Start (Hot Sale, Best Rating, Banner) -->
<div class="h1-product-section section section-padding pt-0">
    <div class="container">
        <div class="row row-cols-lg-3 row-cols-md-2 row-cols-1 align-items-start gy-4">

            <div class="col mb-8">

                <div class="block-title-2">
                    <h4 class="title">Giảm giá</h4>
                    <div id="group-product-1" class="swiper-outer-nav">
                        <div class="swiper-button-prev"></div>
                        <div class="swiper-button-next"></div>
                    </div>
                </div>

                <div class="group-product-slider swiper" data-nav-target="group-product-1">

                    <div class="swiper-wrapper">
                        @foreach($hotSaleProducts as $product)
                        <div class="swiper-slide">
                            @include('client.components.product-item-small', ['product' => $product])
                        </div>
                        @endforeach
                    </div>

                    <div class="swiper-pagination d-none"></div>
                </div>
            </div>

            <div class="col mb-8">

                <div class="block-title-2">
                    <h4 class="title">Đánh giá cao</h4>
                    <div id="group-product-2" class="swiper-outer-nav">
                        <div class="swiper-button-prev"></div>
                        <div class="swiper-button-next"></div>
                    </div>
                </div>

                <div class="group-product-slider swiper" data-nav-target="group-product-2">

                    <div class="swiper-wrapper">
                        @foreach($bestRatingProducts as $product)
                        <div class="swiper-slide">
                            @include('client.components.product-item-small', ['product' => $product])
                        </div>
                        @endforeach
                    </div>

                    <div class="swiper-pagination d-none"></div>
                </div>
            </div>

            <div class="col mb-8">
                <a href="{{ route('products.index') }}" class="banner"><img src="{{ asset('assets/images/banner/hero-4-banner-1.png') }}"
                        alt="Banner One"></a>
            </div>

        </div>
    </div>
</div>
<!-- Product Section End -->


@if($testimonials->isNotEmpty())
<!-- Testimonial Section Start -->
<div class="h3-testimonial-section section section-padding ">
    <div class="container">
        <div class="section-title section-title-center">
            <p class="title">What Client Says</p>
            <h2 class="sub-title">Testimonials</h2>

        </div>

        <div class="testimonial-slider swiper">
            <div class="swiper-wrapper">
                @foreach($testimonials as $testimonial)
                <div class="swiper-slide">
                    <div class="testimonial">
                        <div class="testimonial-client-thumb">
                            @php
                            $tAvatar = $testimonial->avatar ? (Str::startsWith($testimonial->avatar, ['http://', 'https://']) ? $testimonial->avatar : asset('storage/' . $testimonial->avatar)) : asset('assets/images/testimonial/testimonial-1.png');
                            @endphp
                            <img loading="lazy" src="{{ $tAvatar }}" alt="{{ $testimonial->name }}" width="100" height="100">
                        </div>
                        <div class="testimonial-text">
                            <p>{{ $testimonial->content }}</p>
                        </div>
                        <div class="testimonial-client-info">
                            <h5 class="testimonial-client-name">{{ $testimonial->name }}</h5>
                            @if($testimonial->position)
                            <p class="testimonial-client-position">{{ $testimonial->position }}</p>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="swiper-pagination"></div>
            <div class="swiper-button-prev d-none d-md-flex"></div>
            <div class="swiper-button-next d-none d-md-flex"></div>
        </div>

    </div>
</div>
<!-- Testimonial Section End -->
@endif

<!-- Blog Section Start -->
<div class="h3-blog-section section section-padding">
    <div class="container">
        <div class="section-title section-title-center">
            <p class="title">Blog Area</p>
            <h2 class="sub-title">EXPLORE LATEST BLOG</h2>

        </div>

        <div class="blog-carousel swiper">

            <div class="swiper-wrapper">
                @foreach($latestPosts as $post)
                <div class="swiper-slide">
                    <div class="blog">
                        @php
                        $cats=[];$curr=$post->category;while($curr){$cats[]=$curr;$curr=$curr->parent;} $cats=array_reverse($cats);
                        $catPath = implode('/', array_map(function($c){ return $c->slug; }, $cats));
                        $postUrl = $catPath ? route('blog.show.path', ['path' => $catPath, 'slug' => $post->slug]) : route('blog.show', ['slug' => $post->slug]);
                        @endphp
                        <a href="{{ $postUrl }}" class="blog-thumb">
                            @php
                            $pThumb = $post->thumbnail ? (Str::startsWith($post->thumbnail, ['http://', 'https://']) ? $post->thumbnail : asset('storage/' . $post->thumbnail)) : asset('assets/images/blog/blog-1.jpg');
                            @endphp
                            <img loading="lazy" src="{{ $pThumb }}" alt="{{ $post->title }}" width="348" height="232">
                        </a>
                        <div class="blog-content">
                            <h4 class="blog-title"><a href="{{ $postUrl }}">{{ $post->title }}</a></h4>
                            <ul class="blog-meta">
                                <li>{{ $post->created_at->format('d F, Y') }}</li>
                                <li><a href="{{ $postUrl }}">{{ $post->comments_count ?? 0 }} Comments</a></li>
                            </ul>
                            <p>{{ Str::limit(strip_tags($post->content), 100) }}</p>
                            <a href="{{ $postUrl }}" class="btn">Tìm Hiểu Ngay</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="swiper-pagination d-md-none"></div>
        </div>

    </div>
</div>
<!-- Blog Section End -->
@endsection