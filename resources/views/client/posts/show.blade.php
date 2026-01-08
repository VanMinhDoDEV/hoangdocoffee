@extends('client.layouts.master')

@section('title', $post->title . ' - ' . ($storeSettings['name'] ?? 'Shop06'))

@section('content')
    @php
        $cats=[];$curr=$post->category;while($curr){$cats[]=$curr;$curr=$curr->parent;} $cats=array_reverse($cats);
        $catPath = implode('/', array_map(function($c){ return $c->slug; }, $cats));
        $canonical = $catPath ? route('blog.show.path', ['path' => $catPath, 'slug' => $post->slug]) : route('blog.show', ['slug' => $post->slug]);
    @endphp
    @section('robots', 'index, follow')
    @section('description', Str::limit($post->excerpt ?? strip_tags($post->content), 160))
    @push('head')
        <link rel="canonical" href="{{ $canonical }}" />
        <script type="application/ld+json">
        {!! json_encode([
          '@context' => 'https://schema.org',
          '@type' => 'BlogPosting',
          'headline' => $post->title,
          'description' => Str::limit($post->excerpt ?? strip_tags($post->content), 160),
          'datePublished' => ($post->published_at ?? $post->created_at)->toIso8601String(),
          'dateModified' => ($post->updated_at ?? $post->published_at ?? $post->created_at)->toIso8601String(),
          'author' => [
            '@type' => 'Person',
            'name' => $post->author->name ?? 'Admin'
          ],
          'image' => $post->thumbnail ? (Str::startsWith($post->thumbnail, ['http://','https://']) ? $post->thumbnail : asset('storage/'.$post->thumbnail)) : asset('assets/img/blog-big-img-1.jpg'),
          'mainEntityOfPage' => [
            '@type' => 'WebPage',
            '@id' => $canonical
          ],
          'publisher' => [
            '@type' => 'Organization',
            'name' => $storeSettings['name'] ?? 'Hoang Do Coffee',
            'logo' => [
              '@type' => 'ImageObject',
              'url' => !empty($storeSettings['favicon']) ? asset($storeSettings['favicon']) : asset('assets/images/favicon.png')
            ]
          ]
        ], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) !!}
        </script>
    @endpush
    <div class="page-banner-section section">
        <div class="container">
            <ul class="breadcrumb">
                <li><a href="{{ route('home') }}">Trang chủ</a></li>
                <li><a href="{{ route('blog.index') }}">Blog</a></li>
                @if(!empty($cats))
                    @php $path = ''; @endphp
                    @foreach($cats as $cat)
                        @php $path = $path ? ($path . '/' . $cat->slug) : $cat->slug; @endphp
                        <li><a href="{{ route('blog.chuyenmuc.path', ['path' => $path]) }}">{{ $cat->name }}</a></li>
                    @endforeach
                @endif
                <li class="active">{{ $post->title }}</li>
            </ul>
        </div>
    </div>

    <div class="shop-product-section section section-padding">
        <div class="container">
            <div class="row mb-n8">
                <div class="col-lg-9 col-12 mb-8">
                    @php
                        $thumb = $post->thumbnail ? (Str::startsWith($post->thumbnail, ['http://','https://']) ? $post->thumbnail : asset('storage/'.$post->thumbnail)) : asset('assets/images/blog/blog-single-1.jpg');
                        $date = $post->published_at ?: $post->created_at;
                    @endphp
                    <div class="single-blog">
                        <div class="single-blog-image"><img src="{{ $thumb }}" alt="{{ $post->title }}"></div>
                        <div class="single-blog-content">
                            <h1 class="single-blog-title">{{ $post->title }}</h1>
                            <ul class="single-blog-meta">
                                <li>{{ $date ? $date->format('d F, Y') : '' }}</li>
                                <li>{{ $post->author->name ?? 'Admin' }}</li>
                            </ul>
                            @include('client.components.table-of-contents', ['toc' => $toc, 'class' => 'ul-blog-toc-mobile-wrapper d-block d-lg-none', 'collapsed' => true])
                            @include('client.components.table-of-contents', ['toc' => $toc, 'class' => 'ul-blog-toc-main-wrapper d-none d-lg-block', 'collapsed' => true])
                            <div class="single-blog-description">
                                {!! $parsedContent !!}
                            </div>
                            <div class="blog-sidebar-tag">
                                @foreach($post->tags as $tag)
                                    <a href="{{ route('blog.tag', $tag->slug) }}">{{ $tag->name }}</a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="ul-blog-details-nav">
                        <div class="nav-item prev">
                            @if($prev_post)
                                @php
                                    $pcats=[];$pcurr=$prev_post->category;while($pcurr){$pcats[]=$pcurr;$pcurr=$pcurr->parent;} $pcats=array_reverse($pcats);
                                    $ppath = implode('/', array_map(function($c){ return $c->slug; }, $pcats));
                                    $purl = $ppath ? route('blog.show.path', ['path' => $ppath, 'slug' => $prev_post->slug]) : route('blog.show', ['slug' => $prev_post->slug]);
                                @endphp
                                <a href="{{ $purl }}" class="icon-link"><i class="flaticon-left-arrow"></i></a>
                                <a href="{{ $purl }}" class="text-link">Bài trước</a>
                            @endif
                        </div>
                        <div class="nav-item next">
                            @if($next_post)
                                @php
                                    $ncats=[];$ncurr=$next_post->category;while($ncurr){$ncats[]=$ncurr;$ncurr=$ncurr->parent;} $ncats=array_reverse($ncats);
                                    $npath = implode('/', array_map(function($c){ return $c->slug; }, $ncats));
                                    $nurl = $npath ? route('blog.show.path', ['path' => $npath, 'slug' => $next_post->slug]) : route('blog.show', ['slug' => $next_post->slug]);
                                @endphp
                                <a href="{{ $nurl }}" class="text-link">Bài tiếp theo</a>
                                <a href="{{ $nurl }}" class="icon-link"><i class="flaticon-arrow-point-to-right"></i></a>
                            @endif
                        </div>
                    </div>
                    <div class="single-blog-comment" id="comments">
                        <div class="block-title-2">
                            <h4 class="title">Bình luận ({{ $post->comments->count() }})</h4>
                        </div>

                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <!-- Comment List Start -->
                        <ul class="comment-list">
                            @foreach($post->comments as $comment)
                                <li>
                                    <div class="comment-item">
                                        <div class="comment-thumb">
                                @php
                                    $avatar = null;
                                    if($comment->user && $comment->user->avatar) {
                                        $avatar = Str::startsWith($comment->user->avatar, ['http://','https://']) ? $comment->user->avatar : asset('storage/'.$comment->user->avatar);
                                    }
                                @endphp
                                @if($avatar)
                                    <img src="{{ $avatar }}" alt="{{ $comment->name }}">
                                @else
                                    <div class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 font-bold uppercase" style="width: 50px; height: 50px; background: #f0f0f0; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                                        {{ substr($comment->name, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                                        <div class="comment-content">
                                            <div class="comment-meta">
                                                <h5 class="comment-name">{{ $comment->name }}</h5>
                                                <span class="comment-date">{{ $comment->created_at->format('M d, Y') }}</span>
                                            </div>
                                            <p>{{ $comment->content }}</p>
                                            <a href="javascript:void(0)" class="comment-reply" onclick="replyTo({{ $comment->id }}, '{{ $comment->name }}')">Trả lời</a>
                                        </div>
                                    </div>
                                    @if($comment->replies->count() > 0)
                                        <ul class="comment-child">
                                            @foreach($comment->replies as $reply)
                                                <li>
                                                    <div class="comment-item">
                                                        <div class="comment-thumb">
                                                        @php
                                                            $replyAvatar = null;
                                                            if($reply->user && $reply->user->avatar) {
                                                                $replyAvatar = Str::startsWith($reply->user->avatar, ['http://','https://']) ? $reply->user->avatar : asset('storage/'.$reply->user->avatar);
                                                            }
                                                        @endphp
                                                        @if($replyAvatar)
                                                            <img src="{{ $replyAvatar }}" alt="{{ $reply->name }}">
                                                        @else
                                                            <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 font-bold uppercase" style="width: 40px; height: 40px; background: #e0e0e0; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                                                                {{ substr($reply->name, 0, 1) }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                        <div class="comment-content">
                                                            <div class="comment-meta">
                                                                <h5 class="comment-name">
                                                                    {{ $reply->name }}
                                                                    @if($reply->user_id)
                                                                        <span class="badge badge-primary" style="font-size: 0.7em; background: #007bff; color: white; padding: 2px 5px; border-radius: 3px;">Admin</span>
                                                                    @endif
                                                                </h5>
                                                                <span class="comment-date">{{ $reply->created_at->format('M d, Y') }}</span>
                                                            </div>
                                                            <p>{{ $reply->content }}</p>
                                                        </div>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                        <!-- Comment List End -->

                        <div class="block-title-2">
                            <h4 class="title" id="form-title">Để lại bình luận</h4>
                        </div>

                        <!-- Comment Form Start -->
                        <div class="comment-form">
                            <form action="{{ route('blog.comments.store', $post->slug) }}" method="POST">
                                @csrf
                                <input type="hidden" name="parent_id" id="parent_id_input">
                                
                                <div id="reply-alert" class="hidden mb-3 p-2 bg-blue-50 text-blue-700 rounded flex justify-between items-center" style="display: none; background: #e7f1ff; padding: 10px; margin-bottom: 15px; border-radius: 5px;">
                                    <span>Đang trả lời: <strong id="reply-to-name"></strong></span>
                                    <button type="button" onclick="cancelReply()" style="border: none; background: none; cursor: pointer;">&times;</button>
                                </div>

                                <div class="row g-4">
                                    @guest
                                    <div class="col-sm-6">
                                        <label for="name">Tên</label>
                                        <input class="form-field" id="name" name="name" type="text" placeholder="Nhập tên của bạn" value="{{ old('name') }}" required>
                                         @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="email">Email</label>
                                        <input class="form-field" id="email" name="email" type="email" placeholder="email@example.com" value="{{ old('email') }}" required>
                                         @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    @endguest
                                    
                                    @auth
                                        <input type="hidden" name="name" value="{{ auth()->user()->name }}">
                                        <input type="hidden" name="email" value="{{ auth()->user()->email }}">
                                    @endauth

                                    <div class="col-12">
                                        <label for="message">Nội dung</label>
                                        <textarea class="form-field" id="message" name="content" placeholder="Viết bình luận của bạn tại đây" required></textarea>
                                         @error('content') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-12">
                                        <input type="submit" class="btn btn-dark btn-primary-hover rounded-0" value="Gửi bình luận">
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- Comment Form End -->
                    </div>
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
                            @foreach($recent_posts as $rPost)
                                @php
                                    $rthumb = $rPost->thumbnail ? (Str::startsWith($rPost->thumbnail, ['http://','https://']) ? $rPost->thumbnail : asset('storage/'.$rPost->thumbnail)) : asset('assets/images/blog/blog-1.jpg');
                                    $rdate = $rPost->published_at ?: $rPost->created_at;
                                    $rcats=[];$rcurr=$rPost->category;while($rcurr){$rcats[]=$rcurr;$rcurr=$rcurr->parent;} $rcats=array_reverse($rcats);
                                    $rpath = implode('/', array_map(function($c){ return $c->slug; }, $rcats));
                                    $rurl = $rpath ? route('blog.show.path', ['path' => $rpath, 'slug' => $rPost->slug]) : route('blog.show', ['slug' => $rPost->slug]);
                                @endphp
                                <div class="blog-sidebar-post">
                                    <a href="{{ $rurl }}" class="blog-sidebar-post-thumb"><img src="{{ $rthumb }}" alt="{{ $rPost->title }}"></a>
                                    <div class="blog-sidebar-post-content">
                                        <span class="blog-sidebar-post-date">{{ $rdate ? $rdate->format('d F, Y') : '' }}</span>
                                        <h5 class="blog-sidebar-post-title"><a href="{{ $rurl }}">{{ $rPost->title }}</a></h5>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @if(isset($tags) && $tags->count() > 0)
                        <div class="blog-sidebar-item">
                            <h4 class="blog-sidebar-title">Chủ đề đề xuất</h4>
                            <div class="blog-sidebar-body">
                                <div class="blog-sidebar-tag">
                                    @foreach($tags as $tag)
                                        <a href="{{ route('blog.tag', $tag->slug) }}">{{ $tag->name }}</a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="blog-sidebar-item d-none d-lg-block">
                        @include('client.components.table-of-contents', ['toc' => $toc, 'class' => 'ul-blog-toc-sidebar-wrapper'])
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    /* TOC Styling */
    .ul-blog-toc {
        background: #fff;
        padding: 20px;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        margin-bottom: 30px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        transition: box-shadow 0.3s ease;
    }
    .ul-blog-toc:hover {
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.025);
    }
    .ul-blog-toc-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .ul-blog-toc-title {
        font-size: 18px;
        font-weight: 700;
        color: #111827;
        margin: 0;
    }
    .ul-blog-toc-icon {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f3f4f6;
        border-radius: 50%;
        color: #4b5563;
        font-size: 12px;
    }
    .ul-blog-toc.collapsed .ul-blog-toc-icon {
        transform: rotate(-90deg);
    }
    .ul-blog-toc.collapsed .ul-blog-toc-content {
        max-height: 0 !important;
        opacity: 0;
        margin-top: 0;
        padding-top: 0;
        border: none;
    }
    
    .ul-blog-toc-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .toc-item {
        margin-bottom: 4px;
        position: relative;
    }
    .toc-link {
        color: #4b5563;
        text-decoration: none;
        font-size: 15px;
        line-height: 1.5;
        transition: all 0.2s ease;
        display: block;
        padding: 6px 0 6px 12px;
        border-left: 3px solid #e5e7eb;
        margin-left: 2px;
    }
    .toc-link:hover {
        color: var(--ul-primary-color, #bfa37c);
        border-left-color: #d1d5db;
        background-color: #f9fafb;
    }
    .toc-link.active {
        color: var(--ul-primary-color, #bfa37c);
        font-weight: 600;
        border-left-color: var(--ul-primary-color, #bfa37c);
        background-color: #fffbeb;
    }
    
    .toc-level-3 { margin-left: 12px; }
    .toc-level-4 { margin-left: 24px; }

    /* Mobile Sticky (Main TOC) */
    @@media (max-width: 991px) {
        .ul-blog-toc-mobile-wrapper .ul-blog-toc {
            position: sticky;
            top: 10px;
            z-index: 900;
            margin: 0 -15px 30px -15px;
            border-radius: 0;
            border-left: none;
            border-right: none;
        }
        .ul-blog-toc-mobile-wrapper .ul-blog-toc-content {
            max-height: 60vh;
            overflow-y: auto;
        }
    }

    /* Desktop Sticky (Sidebar TOC) */
    @@media (min-width: 992px) {
        .ul-blog-sidebar {
            height: 100%; 
        }
        .ul-blog-toc-sidebar-wrapper {
            position: sticky;
            top: 100px;
        }
        .ul-blog-toc-sidebar-wrapper .ul-blog-toc-content {
             max-height: calc(100vh - 200px);
             overflow-y: auto;
             scrollbar-width: thin;
        }
    }
    
    /* Smooth Scroll offset */
    html {
        scroll-behavior: smooth;
    }
    :target {
        scroll-margin-top: 100px;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle Functionality
        const tocContainers = document.querySelectorAll('.js-toc-container');
        tocContainers.forEach(container => {
            const toggle = container.querySelector('.js-toc-toggle');
            const content = container.querySelector('.ul-blog-toc-content');
            
            if(toggle && content) {
                toggle.addEventListener('click', () => {
                    container.classList.toggle('collapsed');
                });
            }
        });

        // Intersection Observer for Active State
        const observerOptions = {
            root: null,
            rootMargin: '-100px 0px -60% 0px', // Highlight when heading is near top
            threshold: 0
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const id = entry.target.getAttribute('id');
                    
                    // Remove active class from all links
                    document.querySelectorAll('.toc-link').forEach(link => {
                        link.classList.remove('active');
                    });

                    // Add active class to corresponding links
                    document.querySelectorAll(`.toc-link[data-target="${id}"]`).forEach(link => {
                        link.classList.add('active');
                        
                        // Auto scroll TOC to active item if needed
                        const tocContent = link.closest('.ul-blog-toc-content');
                        if(tocContent) {
                            const linkTop = link.offsetTop;
                            const contentHeight = tocContent.clientHeight;
                            const scrollTop = tocContent.scrollTop;
                            
                            if (linkTop < scrollTop || linkTop > scrollTop + contentHeight) {
                                tocContent.scrollTop = linkTop - contentHeight / 2;
                            }
                        }
                    });
                }
            });
        }, observerOptions);

        // Observe all headings present in TOC
        document.querySelectorAll('.ul-blog-descr h2, .ul-blog-descr h3, .ul-blog-descr h4').forEach(heading => {
            observer.observe(heading);
        });

        // Smooth scroll fallback for older browsers or complex layouts
        document.querySelectorAll('.toc-link').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('data-target');
                const targetElement = document.getElementById(targetId);
                
                if (targetElement) {
                    const headerOffset = 100;
                    const elementPosition = targetElement.getBoundingClientRect().top;
                    const offsetPosition = elementPosition + window.pageYOffset - headerOffset;
      
                    window.scrollTo({
                        top: offsetPosition,
                        behavior: "smooth"
                    });
                }
            });
        });
    });
    function replyTo(id, name) {
        document.getElementById('parent_id_input').value = id;
        document.getElementById('reply-to-name').textContent = name;
        document.getElementById('reply-alert').style.display = 'flex';
        document.getElementById('form-title').textContent = 'Trả lời bình luận';
        
        // Scroll to form
        const form = document.querySelector('.comment-form');
        if (form) {
            form.scrollIntoView({ behavior: 'smooth' });
        }
    }

    function cancelReply() {
        document.getElementById('parent_id_input').value = '';
        document.getElementById('reply-alert').style.display = 'none';
        document.getElementById('form-title').textContent = 'Để lại bình luận';
    }
</script>
@endpush
