@php
    $viewType = request('view_type', 'grid');
@endphp
<div class="shop-top-bar">

    <div class="shop-top-bar-item">
        <label for="SortBy">Sort by :</label>
        <select name="SortBy" id="SortBy" onchange="applyFilters(this.value)">
            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Latest</option>
            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price, low to high</option>
            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price, high to low</option>
            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Alphabetically, A-Z</option>
            <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Alphabetically, Z-A</option>
        </select>
    </div>

    <div class="shop-top-bar-item">
        <p>Showing {{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }} of {{ $products->total() }} result</p>
    </div>

    <div class="shop-top-bar-item">
        <label for="paginateBy">Show :</label>
        <select name="paginateBy" id="paginateBy" onchange="applyFilters(null, this.value)">
            <option value="12" {{ request('limit') == 12 ? 'selected' : '' }}>12</option>
            <option value="24" {{ request('limit') == 24 ? 'selected' : '' }}>24</option>
            <option value="48" {{ request('limit') == 48 ? 'selected' : '' }}>48</option>
        </select>
    </div>

    <div class="shop-top-bar-item">
        <div class="nav list-grid-toggle">
            <button class="{{ $viewType == 'grid' ? 'active' : '' }}" onclick="setViewType('grid')"><i class="sli-grid"></i></button>
            <button class="{{ $viewType == 'list' ? 'active' : '' }}" onclick="setViewType('list')"><i class="sli-menu"></i></button>
        </div>
    </div>

</div>

@if(!empty($activeFilters))
<div class="active-filters mb-4 d-none d-lg-block">
    <div class="d-flex flex-wrap align-items-center gap-2">
        <span class="fw-bold me-2">Đang lọc:</span>
        @foreach($activeFilters as $filter)
            <span class="badge bg-light text-dark border d-flex align-items-center px-3 py-2">
                {{ $filter['label'] }}
                <button type="button" class="btn-close ms-2" style="font-size: 0.6rem;" aria-label="Remove" onclick="removeFilter('{{ $filter['type'] }}', '{{ $filter['value'] }}')"></button>
            </span>
        @endforeach
        <button type="button" class="btn btn-sm btn-link text-danger text-decoration-none" onclick="clearAllFilters()">Xóa tất cả</button>
    </div>
</div>
@endif

<!-- Shop Top Bar End -->

<!-- Product Tab Start -->
<div class="tab-content" id="shopProductTabContent">
    <div class="tab-pane fade {{ $viewType == 'grid' ? 'show active' : '' }}" id="product-grid">
        @if($viewType == 'grid')
        <div class="row row-cols-xl-3 row-cols-sm-2 row-cols-1 gy-4">
            @forelse($products as $product)
                <div class="col mb-6">
                    @include('client.components.product-item', ['product' => $product])
                </div>
            @empty
                <div class="col-12">
                    <div class="p-4 text-center text-slate-600">Không có sản phẩm phù hợp.</div>
                </div>
            @endforelse
        </div>
        <div class="mt-4 pagination-container">
            {{ $products->appends(request()->query())->links('client.components.pagination') }}
        </div>
        @endif
    </div>
    <div class="tab-pane fade {{ $viewType == 'list' ? 'show active' : '' }}" id="product-list">
        @if($viewType == 'list')
        <div class="row row-cols-1 gy-4">
            @forelse($products as $product)
                <div class="col mb-6">
                    @include('client.components.product-item-list', ['product' => $product])
                </div>
            @empty
                <div class="col-12">
                    <div class="p-4 text-center text-slate-600">Không có sản phẩm phù hợp.</div>
                </div>
            @endforelse
        </div>
        <div class="mt-4 pagination-container">
            {{ $products->appends(request()->query())->links('client.components.pagination') }}
        </div>
        @endif
    </div>
</div>

@include('client.components.bottom-product-bar')
