@php
    $selectedRanges = collect($filters['price_ranges'] ?? []);
    $selectedCats = collect($filters['categories'] ?? []);
    $currentCat = (int)($filters['category'] ?? 0);
    $selectedStatus = collect($filters['status'] ?? []);
    $rating = (int)($filters['rating'] ?? 0);
    $idSuffix = $idSuffix ?? '';
@endphp

<div class="shop-sidebar-search mb-8">
    <form class="ul-products-search-form position-relative filter-search-form" method="get">
        <input type="text" class="form-control form-field mb-4 pe-5 product-search-field" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Tên sản phẩm...">
        <button class="position-absolute top-50 end-0 translate-middle-y me-3 bg-transparent border-0" type="submit"><i class="sli-magnifier"></i></button>
    </form>
</div>

<div class="accordion accordion-sidebar" id="accordionSidebar{{ $idSuffix }}">

    <!-- Price Filter -->
    <div class="accordion-item shop-sidebar-item">
        <button class="shop-sidebar-toggle accordion-button {{ $selectedRanges->isEmpty() ? 'collapsed' : '' }}" data-bs-toggle="collapse" data-bs-target="#sidebarPrice{{ $idSuffix }}">Lọc theo giá</button>
        <div id="sidebarPrice{{ $idSuffix }}" class="accordion-collapse collapse {{ $selectedRanges->isNotEmpty() ? 'show' : '' }}" data-bs-parent="#accordionSidebar{{ $idSuffix }}">
            <div class="shop-sidebar-body accordion-body">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="price_ranges[]" id="price-1{{ $idSuffix }}" value="0-200000" {{ $selectedRanges->contains('0-200000') ? 'checked' : '' }}>
                    <label class="form-check-label d-flex w-100" for="price-1{{ $idSuffix }}">0 - 200.000đ</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="price_ranges[]" id="price-2{{ $idSuffix }}" value="200000-300000" {{ $selectedRanges->contains('200000-300000') ? 'checked' : '' }}>
                    <label class="form-check-label d-flex w-100" for="price-2{{ $idSuffix }}">200.000đ - 300.000đ</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="price_ranges[]" id="price-3{{ $idSuffix }}" value="300000-500000" {{ $selectedRanges->contains('300000-500000') ? 'checked' : '' }}>
                    <label class="form-check-label d-flex w-100" for="price-3{{ $idSuffix }}">300.000đ - 500.000đ</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="price_ranges[]" id="price-4{{ $idSuffix }}" value="500000+" {{ $selectedRanges->contains('500000+') ? 'checked' : '' }}>
                    <label class="form-check-label d-flex w-100" for="price-4{{ $idSuffix }}">Trên 500.000đ</label>
                </div>
            </div>
        </div>
    </div>

    <!-- Categories -->
    @if($categories->isNotEmpty())
    <div class="accordion-item shop-sidebar-item">
        <button class="shop-sidebar-toggle accordion-button {{ ($selectedCats->isEmpty() && $currentCat == 0) ? 'collapsed' : '' }}" data-bs-toggle="collapse" data-bs-target="#sidebarCategories{{ $idSuffix }}">Danh mục</button>
        <div id="sidebarCategories{{ $idSuffix }}" class="accordion-collapse collapse {{ ($selectedCats->isNotEmpty() || $currentCat != 0) ? 'show' : '' }}" data-bs-parent="#accordionSidebar{{ $idSuffix }}">
            <div class="shop-sidebar-body accordion-body">
                @foreach($categories as $c)
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="categories[]" id="cat-{{ $c->id }}{{ $idSuffix }}" value="{{ $c->id }}" {{ ($selectedCats->contains($c->id) || (!$selectedCats->isNotEmpty() && $currentCat === $c->id)) ? 'checked' : '' }}>
                        <label class="form-check-label" for="cat-{{ $c->id }}{{ $idSuffix }}">{{ $c->name }}</label>
                    </div>
                    @if($c->children->isNotEmpty())
                        <div class="ms-4">
                            @foreach($c->children as $child)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="categories[]" id="cat-{{ $child->id }}{{ $idSuffix }}" value="{{ $child->id }}" {{ ($selectedCats->contains($child->id) || (!$selectedCats->isNotEmpty() && $currentCat === $child->id)) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="cat-{{ $child->id }}{{ $idSuffix }}">{{ $child->name }}</label>
                                </div>
                            @endforeach
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Dynamic Attributes -->
    @foreach($attributes ?? [] as $attr)
        @if($attr->values->isNotEmpty())
            @php 
                $selectedAttrValues = collect($filters['attr_values'] ?? []); 
                $hasSelection = $attr->values->pluck('id')->intersect($selectedAttrValues)->isNotEmpty();
            @endphp
            <div class="accordion-item shop-sidebar-item">
                <button class="shop-sidebar-toggle accordion-button {{ $hasSelection ? '' : 'collapsed' }}" data-bs-toggle="collapse" data-bs-target="#sidebarAttr{{ $attr->id }}{{ $idSuffix }}">{{ $attr->name }}</button>
                <div id="sidebarAttr{{ $attr->id }}{{ $idSuffix }}" class="accordion-collapse collapse {{ $hasSelection ? 'show' : '' }}" data-bs-parent="#accordionSidebar{{ $idSuffix }}">
                    <div class="shop-sidebar-body accordion-body">
                        @foreach($attr->values as $val)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="attr_values[]" id="attr-val-{{ $val->id }}{{ $idSuffix }}" value="{{ $val->id }}" {{ $selectedAttrValues->contains($val->id) ? 'checked' : '' }}>
                                <label class="form-check-label" for="attr-val-{{ $val->id }}{{ $idSuffix }}">{{ $val->value }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    @endforeach

    <!-- Status -->
    <div class="accordion-item shop-sidebar-item">
        <button class="shop-sidebar-toggle accordion-button {{ $selectedStatus->isEmpty() ? 'collapsed' : '' }}" data-bs-toggle="collapse" data-bs-target="#sidebarStatus{{ $idSuffix }}">Trạng thái</button>
        <div id="sidebarStatus{{ $idSuffix }}" class="accordion-collapse collapse {{ $selectedStatus->isNotEmpty() ? 'show' : '' }}" data-bs-parent="#accordionSidebar{{ $idSuffix }}">
            <div class="shop-sidebar-body accordion-body">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="status[]" id="status-instock{{ $idSuffix }}" value="in_stock" {{ $selectedStatus->contains('in_stock') ? 'checked' : '' }}>
                    <label class="form-check-label" for="status-instock{{ $idSuffix }}">Còn Hàng</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="status[]" id="status-onsale{{ $idSuffix }}" value="on_sale" {{ $selectedStatus->contains('on_sale') ? 'checked' : '' }}>
                    <label class="form-check-label" for="status-onsale{{ $idSuffix }}">Đang giảm giá</label>
                </div>
            </div>
        </div>
    </div>

    <!-- Rating -->
    <div class="accordion-item shop-sidebar-item">
        <button class="shop-sidebar-toggle accordion-button {{ $rating == 0 ? 'collapsed' : '' }}" data-bs-toggle="collapse" data-bs-target="#sidebarRating{{ $idSuffix }}">Đánh giá</button>
        <div id="sidebarRating{{ $idSuffix }}" class="accordion-collapse collapse {{ $rating != 0 ? 'show' : '' }}" data-bs-parent="#accordionSidebar{{ $idSuffix }}">
            <div class="shop-sidebar-body accordion-body">
                @for($r = 5; $r >= 1; $r--)
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="rating" id="rating-{{ $r }}{{ $idSuffix }}" value="{{ $r }}" {{ $rating == $r ? 'checked' : '' }}>
                    <label class="form-check-label ms-2" for="rating-{{ $r }}{{ $idSuffix }}">
                         @for($i=1;$i<=$r;$i++) <i class="sli-star text-warning"></i> @endfor
                         @if($r < 5) <span class="small">Trở lên</span> @endif
                    </label>
                </div>
                @endfor
            </div>
        </div>
    </div>

</div>
