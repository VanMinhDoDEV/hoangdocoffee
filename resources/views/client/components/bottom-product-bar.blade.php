@if(isset($activeFilters))
<div class="fixed-bottom bg-white border-top shadow-lg d-lg-none product-bottom-bar" style="z-index: 1040;">
    <div class="container py-2">
        <div class="d-flex justify-content-between align-items-center mb-2">
             <button class="btn btn-dark w-100 d-flex align-items-center justify-content-center gap-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasProductFilter" aria-controls="offcanvasProductFilter">
                <i class="sli-equalizer"></i> Bộ lọc sản phẩm
            </button>
        </div>
        
        @if(!empty($activeFilters))
        <div class="d-flex flex-nowrap gap-2 overflow-auto pb-1" style="scrollbar-width: none; -ms-overflow-style: none;">
            @foreach($activeFilters as $filter)
                <span class="badge bg-light text-dark border d-flex align-items-center px-3 py-2 flex-shrink-0">
                    {{ $filter['label'] }}
                    <button type="button" class="btn-close ms-2" style="font-size: 0.6rem;" aria-label="Remove" onclick="removeFilter('{{ $filter['type'] }}', '{{ $filter['value'] }}')"></button>
                </span>
            @endforeach
            <button type="button" class="btn btn-sm btn-link text-danger text-decoration-none flex-shrink-0" onclick="clearAllFilters()">Xóa tất cả</button>
        </div>
        @endif
    </div>
</div>
<!-- Spacer to prevent content from being hidden behind fixed bottom bar -->
<div class="d-lg-none" style="height: 80px;"></div>
@endif
