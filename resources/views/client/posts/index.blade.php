@extends('client.layouts.master')

@section('title', isset($category) ? $category->name : (isset($tag) ? 'Tag: ' . $tag->name : (request('q') ? 'Tìm kiếm: ' . request('q') : 'Blog')))

@section('content')
    @php
        $robots = request('q') ? 'noindex, follow' : 'index, follow';
        $canonical = route('blog.index');
        if (isset($category) && $category) {
            $cats=[];$curr=$category;while($curr){$cats[]=$curr;$curr=$curr->parent;} $cats=array_reverse($cats);
            $catPath = implode('/', array_map(function($c){ return $c->slug; }, $cats));
            $canonical = route('blog.chuyenmuc.path', ['path' => $catPath]);
        } elseif (isset($tag)) {
            $canonical = route('blog.tag', $tag->slug);
        }
        $recentPosts = \App\Models\Post::where('status','published')->orderBy('published_at','desc')->take(3)->get();
        $postCategories = \App\Models\PostCategory::with('parent')->orderBy('name')->get();
        $tags = \App\Models\PostTag::withCount('posts')->orderByDesc('posts_count')->take(10)->get();
    @endphp
    @section('robots', $robots)
    @section('description', isset($category) && $category && !empty($category->meta_description) ? $category->meta_description : (isset($tag) ? ('Bài viết theo thẻ ' . $tag->name) : 'Blog'))
    @push('head')
        <link rel="canonical" href="{{ $canonical }}" />
        @if(isset($posts) && method_exists($posts, 'currentPage'))
            @php
                $current = $posts->currentPage();
                $last = $posts->lastPage();
            @endphp
            @if($current > 1)
                <link rel="prev" href="{{ $posts->previousPageUrl() }}" />
            @endif
            @if($current < $last)
                <link rel="next" href="{{ $posts->nextPageUrl() }}" />
            @endif
        @endif
    @endpush
    <div class="page-banner-section section">
        <div class="container">
            <ul class="breadcrumb">
                <li><a href="{{ route('home') }}">Trang chủ</a></li>
                <li><a href="{{ route('blog.index') }}">Blog</a></li>
                @if(isset($category) && $category)
                    @php
                        $cats=[];$curr=$category;while($curr){$cats[]=$curr;$curr=$curr->parent;} $cats=array_reverse($cats);
                        $path = '';
                    @endphp
                    @foreach($cats as $cat)
                        @php $path = $path ? ($path . '/' . $cat->slug) : $cat->slug; @endphp
                        @if($cat->id === $category->id)
                            <li class="active">{{ $cat->name }}</li>
                        @else
                            <li><a href="{{ route('blog.chuyenmuc.path', ['path' => $path]) }}">{{ $cat->name }}</a></li>
                        @endif
                    @endforeach
                @elseif(isset($tag))
                    <li class="active">Tag: {{ $tag->name }}</li>
                @elseif(request('q'))
                    <li class="active">Tìm kiếm: {{ request('q') }}</li>
                @else
                    <li class="active">Danh mục</li>
                @endif
            </ul>
        </div>
    </div>
    <div class="shop-product-section section section-padding">
        <div class="container">
            <div class="row mb-n8">
                <div class="col-lg-9 col-12 mb-8">
                    <div class="row row-cols-md-2 row-cols-1 g-4">
                        @forelse($posts as $post)
                            @php
                                $thumb = $post->thumbnail ? (Str::startsWith($post->thumbnail, ['http://','https://']) ? $post->thumbnail : asset('storage/'.$post->thumbnail)) : asset('assets/images/blog/blog-1.jpg');
                                $date = $post->published_at ?: $post->created_at;
                                $excerpt = $post->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($post->content), 150);
                                $cats=[];$curr=$post->category;while($curr){$cats[]=$curr;$curr=$curr->parent;} $cats=array_reverse($cats);
                                $catPath = implode('/', array_map(function($c){ return $c->slug; }, $cats));
                                $postUrl = $catPath ? route('blog.show.path', ['path' => $catPath, 'slug' => $post->slug]) : route('blog.show', ['slug' => $post->slug]);
                            @endphp
                            <div class="col">
                                <div class="blog">
                                    <a href="{{ $postUrl }}" class="blog-thumb">
                                        <img loading="lazy" src="{{ $thumb }}" alt="{{ $post->title }}" width="348" height="232">
                                    </a>
                                    <div class="blog-content">
                                        <h4 class="blog-title"><a href="{{ $postUrl }}">{{ $post->title }}</a></h4>
                                        <ul class="blog-meta">
                                            <li>{{ $date ? $date->format('d/m/Y') : '' }}</li>
                                        </ul>
                                        <p>{{ $excerpt }}</p>
                                        <a href="{{ $postUrl }}" class="btn">Xem thêm</a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center py-5">
                                <p>Chưa có bài viết nào trong chuyên mục này.</p>
                            </div>
                        @endforelse
                    </div>
                    @if($posts->hasPages())
                        {{ $posts->links('client.components.pagination') }}
                    @endif
                </div>
                <div class="col-lg-3 col-12 mb-8">
                    <div class="blog-sidebar-item">
                        <h4 class="blog-sidebar-title">Tìm kiếm</h4>
                        <div class="blog-sidebar-body">
                            <div class="blog-sidebar-search">
                                <form action="{{ route('blog.index') }}" method="get">
                                    <input class="form-field" type="text" name="q" value="{{ request('q') }}" placeholder="Tìm bài viết">
                                    <button class="btn" type="submit"><i class="sli-magnifier"></i></button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="blog-sidebar-item">
                        <h4 class="blog-sidebar-title">Bài viết mới</h4>
                        <div class="blog-sidebar-body">
                            @foreach($recentPosts as $rp)
                                @php
                                    $rthumb = $rp->thumbnail ? (Str::startsWith($rp->thumbnail, ['http://','https://']) ? $rp->thumbnail : asset('storage/'.$rp->thumbnail)) : asset('assets/images/blog/blog-1.jpg');
                                    $rdate = $rp->published_at ?: $rp->created_at;
                                    $rcats=[];$rcurr=$rp->category;while($rcurr){$rcats[]=$rcurr;$rcurr=$rcurr->parent;} $rcats=array_reverse($rcats);
                                    $rcatPath = implode('/', array_map(function($c){ return $c->slug; }, $rcats));
                                    $rurl = $rcatPath ? route('blog.show.path', ['path' => $rcatPath, 'slug' => $rp->slug]) : route('blog.show', ['slug' => $rp->slug]);
                                @endphp
                                <div class="blog-sidebar-post">
                                    <a href="{{ $rurl }}" class="blog-sidebar-post-thumb"><img src="{{ $rthumb }}" alt="{{ $rp->title }}"></a>
                                    <div class="blog-sidebar-post-content">
                                        <span class="blog-sidebar-post-date">{{ $rdate ? $rdate->format('d/m/Y') : '' }}</span>
                                        <h5 class="blog-sidebar-post-title"><a href="{{ $rurl }}">{{ $rp->title }}</a></h5>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="blog-sidebar-item">
                        <h4 class="blog-sidebar-title">Chuyên mục</h4>
                        <div class="blog-sidebar-body">
                            @php
                                $buildPath = function($cat){
                                    $cats=[];$curr=$cat;while($curr){$cats[]=$curr;$curr=$curr->parent;} $cats=array_reverse($cats);
                                    return implode('/', array_map(function($c){ return $c->slug; }, $cats));
                                };
                            @endphp
                            <ul class="blog-sidebar-archive">
                                @foreach($postCategories as $pc)
                                    <li>
                                        <a href="{{ route('blog.chuyenmuc.path', ['path' => $buildPath($pc)]) }}">{{ $pc->name }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="blog-sidebar-item">
                        <h4 class="blog-sidebar-title">Thẻ</h4>
                        <div class="blog-sidebar-body">
                            <div class="blog-sidebar-tag">
                                @foreach($tags as $t)
                                    <a href="{{ route('blog.tag', $t->slug) }}">{{ $t->name }}</a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
