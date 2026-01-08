@extends('client.layouts.master')
@section('title', 'Cửa hàng')
@section('content')

    <!-- Page Banner Section Start -->
    @php
        $breadcrumbItems = [['label' => 'Trang chủ', 'url' => route('home')]];
        
        // Add "Sản phẩm" as the parent for categories
        if (isset($category) && $category) {
            $breadcrumbItems[] = ['label' => 'Sản phẩm', 'url' => route('sanpham.index')];
        } else if (!isset($collection)) {
             // If it's just the main shop page
             $breadcrumbItems[] = ['label' => 'Sản phẩm', 'url' => ''];
        }

        $pageTitle = 'Cửa hàng';

        if (isset($collection) && $collection) {
            $pageTitle = $collection->name;
            $breadcrumbItems[] = ['label' => 'Bộ sưu tập', 'url' => route('collections.index')];
            $breadcrumbItems[] = ['label' => $collection->name, 'url' => ''];
        } elseif (isset($category) && $category) {
            $pageTitle = $category->name;
            // Build parent hierarchy
            $cats = [];
            $curr = $category;
            while($curr) {
                $cats[] = $curr;
                $curr = $curr->parent;
            }
            $cats = array_reverse($cats);
            
            foreach($cats as $cat) {
                 $url = ($cat->id === $category->id) ? '' : route('danhmuc.show', $cat->slug);
                 $breadcrumbItems[] = ['label' => $cat->name, 'url' => $url];
            }
        } 
        // If neither, "Sản phẩm" is already added as current above if needed, but wait:
        // If !isset($collection) && !isset($category), it's the main shop page.
        // In that case line 9 added "Sản phẩm" with empty URL.
        // If $category is set, line 7 added "Sản phẩm" with URL.
    @endphp

    @include('client.components.breadcrumb', [
        'title' => $pageTitle,
        'items' => $breadcrumbItems
    ])
    <!-- Page Banner Section End -->

    <!-- Product Section Start -->
    <div class="shop-product-section section section-padding">
        <div class="container">
            <div class="row flex-lg-row-reverse gy-4">

                <div class="col-lg-9 col-12 mb-8">

                    <!-- Product List Container -->
                    <div id="product-list-container">
                        @include('client.products.list_container')
                    </div>
                    <!-- Product List Container End -->
                </div>

                <div class="col-lg-3 col-12 mb-8 d-none d-lg-block">
                     @include('client.partials.sidebar-product', [
                        'categories' => $categories ?? collect(),
                        'attributes' => $attributes ?? collect(),
                        'filters' => $filters ?? [],
                        'idSuffix' => ''
                    ])
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Filter Offcanvas -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasProductFilter" aria-labelledby="offcanvasProductFilterLabel">
       <div class="offcanvas-header">
         <h5 class="offcanvas-title" id="offcanvasProductFilterLabel">Bộ lọc sản phẩm</h5>
         <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
       </div>
       <div class="offcanvas-body">
          @include('client.partials.sidebar-product', [
             'categories' => $categories ?? collect(),
             'attributes' => $attributes ?? collect(),
             'filters' => $filters ?? [],
             'idSuffix' => '-mobile'
         ])
       </div>
     </div>

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Handle sidebar filter changes (delegated for both desktop and mobile)
        $(document).on('change', '.accordion-sidebar input[type="checkbox"], .accordion-sidebar input[type="radio"]', function() {
            // Optional: Sync other sidebar
            let name = $(this).attr('name');
            let value = $(this).val();
            let isChecked = $(this).is(':checked');
            
            // Find same input in other sidebars and sync state
            $(`.accordion-sidebar input[name="${name}"][value="${value}"]`).not(this).prop('checked', isChecked);
            
            applyFilters();
        });

        // Handle search form submit
        $('#filterSearchForm').on('submit', function(e) {
            e.preventDefault();
            applyFilters();
        });
        
        // Also handle mobile search form if it exists (it's inside sidebar-product so it has class .filter-search-form)
        $(document).on('submit', '.filter-search-form', function(e) {
            e.preventDefault();
            applyFilters();
        });

        // Handle pagination clicks
        $(document).on('click', '.pagination-container a', function(e) {
            e.preventDefault();
            let url = $(this).attr('href');
            if (url) {
                fetchProducts(url);
            }
        });
    });

    function applyFilters(sortValue = null, limitValue = null) {
        let params = new URLSearchParams(window.location.search);
        
        // Clear existing array params
        params.delete('price_ranges[]');
        params.delete('categories[]');
        params.delete('attr_values[]');
        params.delete('status[]');
        params.delete('rating');
        params.delete('q');
        params.delete('sort');
        params.delete('limit');

        // Collect Search (from visible or first found)
        let q = $('.product-search-field').filter(function() { return this.value; }).first().val();
        if (q) params.set('q', q);

        // Helper to collect unique values
        function collectUnique(name) {
            let values = new Set();
            $(`.accordion-sidebar input[name="${name}"]:checked`).each(function() {
                values.add($(this).val());
            });
            return Array.from(values);
        }

        collectUnique('price_ranges[]').forEach(v => params.append('price_ranges[]', v));
        collectUnique('categories[]').forEach(v => params.append('categories[]', v));
        collectUnique('attr_values[]').forEach(v => params.append('attr_values[]', v));
        collectUnique('status[]').forEach(v => params.append('status[]', v));
        
         // Collect Radio (Rating) - take the first checked one
        let rating = $(`.accordion-sidebar input[name="rating"]:checked`).first().val();
        if (rating) params.set('rating', rating);

        // Collect Sort & Limit
        let currentSort = sortValue || $('#SortBy').val();
        if (currentSort && !currentSort.includes('http')) {
             params.set('sort', currentSort);
        }

        let currentLimit = limitValue || $('#paginateBy').val();
         if (currentLimit && !currentLimit.includes('http')) {
             params.set('limit', currentLimit);
        }

        // Page should reset to 1 on filter change
        params.delete('page');

        params.set('ajax', '1');

        let url = window.location.pathname + '?' + params.toString();
        fetchProducts(url);
    }

    function fetchProducts(url) {
        $('#product-list-container').css('opacity', '0.5');

        $.ajax({
            url: url,
            type: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(response) {
                $('#product-list-container').html(response).css('opacity', '1');
                
                // Update URL
                let urlObj = new URL(window.location.origin + url);
                urlObj.searchParams.delete('ajax');
                window.history.pushState(null, '', urlObj.toString());
                
                // Scroll to top
                $('html, body').animate({
                    scrollTop: $(".shop-product-section").offset().top - 100
                }, 500);
            },
            error: function(xhr) {
                console.error('Error fetching products:', xhr);
                $('#product-list-container').css('opacity', '1');
                 window.location.reload();
            }
        });
    }

    // Expose functions to global scope
    window.removeFilter = function(type, value) {
        // Uncheck sidebar inputs in ALL sidebars
        if (type === 'price_ranges') {
            $(`.accordion-sidebar input[name="price_ranges[]"][value="${value}"]`).prop('checked', false);
        } else if (type === 'categories') {
            $(`.accordion-sidebar input[name="categories[]"][value="${value}"]`).prop('checked', false);
        } else if (type === 'attr_values') {
            $(`.accordion-sidebar input[name="attr_values[]"][value="${value}"]`).prop('checked', false);
        } else if (type === 'status') {
            $(`.accordion-sidebar input[name="status[]"][value="${value}"]`).prop('checked', false);
        } else if (type === 'rating') {
            $(`.accordion-sidebar input[name="rating"][value="${value}"]`).prop('checked', false);
        } else if (type === 'q') {
            $('.product-search-field').val('');
        }

        applyFilters();
    };

    window.clearAllFilters = function() {
        $('.accordion-sidebar input[type="checkbox"]').prop('checked', false);
        $('.accordion-sidebar input[type="radio"]').prop('checked', false);
        $('.product-search-field').val('');
        applyFilters();
    };

    window.setViewType = function(type) {
        let params = new URLSearchParams(window.location.search);
        params.set('view_type', type);
        params.set('ajax', '1');
        let url = window.location.pathname + '?' + params.toString();
        fetchProducts(url);
    };
</script>
@endpush
