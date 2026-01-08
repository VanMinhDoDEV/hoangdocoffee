@php
    /** @var \Illuminate\Pagination\LengthAwarePaginator|\Illuminate\Contracts\Pagination\Paginator $paginator */
    $paginator = $paginator ?? null;
    $showCounter = $showCounter ?? true;
    function buildPages($current, $last){
        $pages = [];
        if ($last <= 7) {
            for ($i = 1; $i <= $last; $i++) $pages[] = $i;
            return $pages;
        }
        $pages = [1,2];
        $start = max(3, $current - 1);
        $end = min($last - 2, $current + 1);
        if ($start > 3) $pages[] = '...';
        for ($i = $start; $i <= $end; $i++) $pages[] = $i;
        if ($end < $last - 2) $pages[] = '...';
        $pages[] = $last - 1;
        $pages[] = $last;
        return $pages;
    }
@endphp
@if($paginator && $paginator->lastPage() > 1)
<div class="flex items-center justify-between">
    @if($showCounter)
    <div class="text-sm font-medium" aria-live="polite">
        Showing <span class="font-bold">{{ $paginator->firstItem() }}</span> to <span class="font-bold">{{ $paginator->lastItem() }}</span> of <span class="font-bold">{{ $paginator->total() }}</span> entries
    </div>
    @else
    <div></div>
    @endif
    <nav aria-label="Pagination Navigation">
        <ul class="flex items-center gap-2">
            <li>
                @if($paginator->onFirstPage())
                    <span class="page-btn px-4 py-2 rounded-lg font-medium transition-all duration-300 text-gray-400 cursor-not-allowed" aria-disabled="true" aria-label="Previous page">
                        <span class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                            <span>Previous</span>
                        </span>
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" class="page-btn px-4 py-2 rounded-lg font-medium transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2" aria-label="Previous page">
                        <span class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                            <span>Previous</span>
                        </span>
                    </a>
                @endif
            </li>
            @php
                $current = $paginator->currentPage();
                $last = $paginator->lastPage();
                $pages = buildPages($current, $last);
            @endphp
            @foreach($pages as $p)
                @if($p === '...')
                    <li><span class="px-2 py-2 font-bold">...</span></li>
                @else
                    @if($p == $current)
                        <li><span class="page-btn active px-4 py-2 rounded-lg font-medium transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 bg-indigo-600 text-white" aria-current="page" aria-label="Current page, page {{ $p }}">{{ $p }}</span></li>
                    @else
                        <li><a href="{{ $paginator->url($p) }}" class="page-btn px-4 py-2 rounded-lg font-medium transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2" aria-label="Go to page {{ $p }}">{{ $p }}</a></li>
                    @endif
                @endif
            @endforeach
            <li>
                @if($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" class="page-btn px-4 py-2 rounded-lg font-medium transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2" aria-label="Next page">
                        <span class="flex items-center gap-2">
                            <span>Next</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                        </span>
                    </a>
                @else
                    <span class="page-btn px-4 py-2 rounded-lg font-medium transition-all duration-300 text-gray-400 cursor-not-allowed" aria-disabled="true" aria-label="Next page">
                        <span class="flex items-center gap-2">
                            <span>Next</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                        </span>
                    </span>
                @endif
            </li>
        </ul>
    </nav>
</div>
@endif

