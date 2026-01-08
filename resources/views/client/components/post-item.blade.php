<div class="ul-blog">
    <div class="ul-blog-img">
        @php
        $cats=[];$curr=$post->category;while($curr){$cats[]=$curr;$curr=$curr->parent;} $cats=array_reverse($cats);
        $catPath = implode('/', array_map(function($c){ return $c->slug; }, $cats));
        $postUrl = $catPath ? route('blog.show.path', ['path' => $catPath, 'slug' => $post->slug]) : route('blog.show', ['slug' => $post->slug]);
    @endphp
    <a href="{{ $postUrl }}" class="d-block w-100 h-100">
        @if($post->thumbnail)
            <img src="{{ asset('storage/' . $post->thumbnail) }}" alt="{{ $post->title }}">
        @else
            <img src="{{ asset('assets/img/blog-1.jpg') }}" alt="{{ $post->title }}">
        @endif
    </a>

    @if($post->published_at)
    <div class="date">
        <span class="number">{{ $post->published_at->format('d') }}</span>
        <span class="txt">{{ $post->published_at->format('M') }}</span>
    </div>
    @elseif($post->created_at)
    <div class="date">
        <span class="number">{{ $post->created_at->format('d') }}</span>
        <span class="txt">{{ $post->created_at->format('M') }}</span>
    </div>
    @endif
</div>

<div class="ul-blog-txt">
    <div class="ul-blog-infos flex gap-x-[30px] mb-[16px]">
        <!-- single info -->
        <div class="ul-blog-info">
            <span class="icon"><i class="flaticon-user-2"></i></span>
            <span class="text font-normal text-[14px] text-etGray">By {{ $post->author->name ?? 'Admin' }}</span>
        </div>
    </div>

    <h3 class="ul-blog-title"><a href="{{ $postUrl }}">{{ $post->title }}</a></h3>
    <p class="ul-blog-descr">{{ Str::limit($post->excerpt ?? strip_tags($post->content), 100) }}</p>

    <a href="{{ $postUrl }}" class="ul-blog-btn">Xem ngay <span class="icon"><i class="flaticon-up-right-arrow"></i></span></a>
</div>
</div>
