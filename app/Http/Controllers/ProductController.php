<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with([
            'variants' => function($q){ $q->select('id','product_id','price','compare_at_price','inventory_quantity','is_active'); },
            'images'
        ])->where('is_active', true);

        $categoryId = (int)$request->query('category', (int)$request->query('search-category', 0));
        $categoryIds = collect((array)$request->query('categories', []))->map(fn($v)=>(int)$v)->filter()->values();
        
        $selectedIds = $categoryIds->isNotEmpty() ? $categoryIds->all() : ($categoryId ? [$categoryId] : []);
        
        if (!empty($selectedIds)) {
            // Get all descendants (children)
            $childIds = \App\Models\Category::whereIn('parent_id', $selectedIds)->pluck('id')->toArray();
            $allCategoryIds = array_unique(array_merge($selectedIds, $childIds));
            $query->whereIn('category_id', $allCategoryIds);
        }

        $collectionName = trim((string)$request->query('collection', ''));
        if ($collectionName !== '') {
            $query->where('collection', $collectionName);
        }

        $search = trim((string)$request->query('q', ''));
        if ($search !== '') {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                  ->orWhere('slug', 'like', '%'.$search.'%')
                  ->orWhere('material', 'like', '%'.$search.'%');
            });
        }

        $priceRanges = collect((array)$request->query('price_ranges', []))->map(fn($v)=>trim((string)$v))->filter()->values();
        if ($priceRanges->isNotEmpty()) {
            $query->where(function($outer) use ($priceRanges) {
                foreach ($priceRanges as $pr) {
                    $outer->orWhere(function($q) use ($pr) {
                        if ($pr === '0-200000') {
                            $q->where(function($qq){
                                $qq->whereBetween('discounted_price', [0, 200000])
                                   ->orWhereBetween('price', [0, 200000])
                                   ->orWhereHas('variants', function($vq){
                                       $vq->whereBetween('price', [0, 200000]);
                                   });
                            });
                        } elseif ($pr === '200000-300000') {
                            $q->where(function($qq){
                                $qq->whereBetween('discounted_price', [200000, 300000])
                                   ->orWhereBetween('price', [200000, 300000])
                                   ->orWhereHas('variants', function($vq){
                                       $vq->whereBetween('price', [200000, 300000]);
                                   });
                            });
                        } elseif ($pr === '300000-500000') {
                            $q->where(function($qq){
                                $qq->whereBetween('discounted_price', [300000, 500000])
                                   ->orWhereBetween('price', [300000, 500000])
                                   ->orWhereHas('variants', function($vq){
                                       $vq->whereBetween('price', [300000, 500000]);
                                   });
                            });
                        } elseif ($pr === '500000+') {
                            $q->where(function($qq){
                                $qq->where('discounted_price', '>', 500000)
                                   ->orWhere('price', '>', 500000)
                                   ->orWhereHas('variants', function($vq){
                                       $vq->where('price', '>', 500000);
                                   });
                            });
                        }
                    });
                }
            });
        }

        $attributeValueIds = collect((array)$request->query('attr_values', []))->map(fn($v)=>(int)$v)->filter()->values();
        if ($attributeValueIds->isNotEmpty()) {
            $query->whereHas('variants.options', function($oq) use ($attributeValueIds) {
                $oq->whereIn('attribute_value_id', $attributeValueIds->all());
            });
        }

        $ratingMin = (int)$request->query('rating', 0);
        if ($ratingMin) {
            $query->whereHas('reviews', function($rq) use ($ratingMin) {
                $rq->where('rating', '>=', $ratingMin);
            });
        }

        $statuses = collect((array)$request->query('status', []))->map(fn($v)=>trim((string)$v))->filter()->values();
        $inStock = $request->boolean('in_stock', false);
        $onSale = $request->boolean('on_sale', false);
        if ($statuses->contains('in_stock') || $inStock) {
            $query->where(function($q){
                $q->where('in_stock', true)
                  ->orWhereHas('variants', function($vq){
                      $vq->where('inventory_quantity', '>', 0);
                  });
            });
        }
        if ($statuses->contains('on_sale') || $onSale) {
            $query->where(function($q){
                $q->whereNotNull('discounted_price')
                  ->whereColumn('discounted_price', '<', 'price')
                  ->orWhereHas('variants', function($vq){
                      $vq->whereNotNull('compare_at_price')
                         ->whereColumn('price', '<', 'compare_at_price');
                  });
            });
        }

        // Sorting
        $sort = $request->query('sort', 'newest');
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        // Pagination limit
        $limit = (int)$request->query('limit', 12);
        if (!in_array($limit, [12, 24, 48])) {
            $limit = 12;
        }

        $products = $query->paginate($limit)->appends($request->query());

        $categories = \App\Models\Category::with(['children' => function($q) {
                $q->where('is_active', true)->orderBy('name');
            }])
            ->whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        // Dynamic attributes for filtering
        $attributes = \App\Models\ProductAttribute::with(['values' => function($q){
            $q->orderBy('value');
        }])->get();

        $filters = [
            'q' => $search,
            'category' => $categoryId ?: '',
            'categories' => $categoryIds->all(),
            'collection' => $collectionName ?: '',
            'price_ranges' => $priceRanges->all(),
            'attr_values' => $attributeValueIds->all(),
            'rating' => $ratingMin ?: '',
            'status' => $statuses->all(),
        ];

        $category = $categoryId ? \App\Models\Category::find($categoryId) : null;
        $collection = $collectionName ? \App\Models\Collection::where('name', $collectionName)->first() : null;

        // Prepare active filters for display
        $activeFilters = [];

        if ($search) {
            $activeFilters[] = ['type' => 'q', 'value' => $search, 'label' => 'Tìm kiếm: ' . $search];
        }

        if ($categoryId && $category) {
             // If single category selected via query param (not array)
             $activeFilters[] = ['type' => 'category', 'value' => $categoryId, 'label' => 'Danh mục: ' . $category->name];
        }

        foreach ($categoryIds as $cid) {
             $c = $categories->firstWhere('id', $cid);
             if ($c) {
                 $activeFilters[] = ['type' => 'categories', 'value' => $cid, 'label' => 'Danh mục: ' . $c->name];
             }
        }

        if ($collectionName) {
            $activeFilters[] = ['type' => 'collection', 'value' => $collectionName, 'label' => 'Bộ sưu tập: ' . $collectionName];
        }

        foreach ($priceRanges as $pr) {
             $label = $pr;
             if ($pr === '0-200000') $label = '0 - 200.000đ';
             elseif ($pr === '200000-300000') $label = '200.000đ - 300.000đ';
             elseif ($pr === '300000-500000') $label = '300.000đ - 500.000đ';
             elseif ($pr === '500000+') $label = 'Trên 500.000đ';
             $activeFilters[] = ['type' => 'price_ranges', 'value' => $pr, 'label' => 'Giá: ' . $label];
        }

        foreach ($attributeValueIds as $avId) {
             // Find value name. We have attributes loaded.
             $valName = null;
             foreach ($attributes as $attr) {
                 $val = $attr->values->firstWhere('id', $avId);
                 if ($val) {
                     $valName = $attr->name . ': ' . $val->value;
                     break;
                 }
             }
             if ($valName) {
                 $activeFilters[] = ['type' => 'attr_values', 'value' => $avId, 'label' => $valName];
             }
        }

        if ($ratingMin) {
             $activeFilters[] = ['type' => 'rating', 'value' => $ratingMin, 'label' => 'Đánh giá: ' . $ratingMin . ' sao trở lên'];
        }

        foreach ($statuses as $st) {
             $label = $st;
             if ($st === 'in_stock') $label = 'Còn hàng';
             elseif ($st === 'on_sale') $label = 'Đang giảm giá';
             $activeFilters[] = ['type' => 'status', 'value' => $st, 'label' => 'Trạng thái: ' . $label];
        }


        if ($request->ajax() || $request->query('ajax')) {
            return view('client.products.list_container', compact('products', 'activeFilters'))->render();
        }

        return view('client.products.index', compact('products','categories','attributes','filters','category','collection', 'activeFilters'));
    }

    public function show($slug)
    {
        $product = Product::where('slug', $slug)
            ->with([
                'variants.options.attribute',
                'variants.options.attributeValue',
                'variants.images',
                'images' => function($q){ $q->orderBy('position'); },
                'reviews' => function($q){ 
                    $q->where('status', 'published')
                      ->whereNull('parent_id')
                      ->with(['user', 'replies' => function($rq){
                          $rq->where('status', 'published')->with('user')->orderBy('created_at', 'asc');
                      }])
                      ->orderBy('created_at', 'desc'); 
                },
                'category'
            ])
            ->firstOrFail();

        $product->increment('view_count');

        $reviews = $product->reviews ?? collect();
        $reviewsCount = $reviews->count();
        $totalReviewsCount = \App\Models\Review::where('product_id', $product->id)->where('status', 'published')->count();
        
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->limit(4)
            ->get();
            
        $inWishlist = false;
        if (auth()->check()) {
            $inWishlist = \App\Models\Wishlist::where('user_id', auth()->id())
                ->where('product_id', $product->id)
                ->exists();
        }

        return view('client.products.show', compact('product', 'reviews', 'reviewsCount', 'totalReviewsCount', 'relatedProducts', 'inWishlist'));
    }

    public function quickView($slug)
    {
        $product = Product::where('slug', $slug)
            ->with([
                'variants.options.attribute',
                'variants.options.attributeValue',
                'variants.images',
                'images' => function($q){ $q->orderBy('position'); },
                'reviews' => function($q){ $q->where('status', 'published'); }, // Needed for rating avg
                'category'
            ])
            ->firstOrFail();

        $reviewsCount = $product->reviews->count();
        
        $inWishlist = false;
        if (auth()->check()) {
            $inWishlist = \App\Models\Wishlist::where('user_id', auth()->id())
                ->where('product_id', $product->id)
                ->exists();
        }
        
        return view('client.products.quick-view', compact('product', 'reviewsCount', 'inWishlist'));
    }
    
    public function storeReview(Request $request, string $slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();
        $data = $request->validate([
            'reviewer_name' => ['nullable','string','max:255'],
            'reviewer_email' => ['nullable','email','max:255'],
            'rating' => ['nullable','integer','min:1','max:5'], // Rating is optional for replies
            'content' => ['required','string'],
            'parent_id' => ['nullable', 'exists:reviews,id'],
        ]);

        $review = \App\Models\Review::create([
            'product_id' => $product->id,
            'parent_id' => $data['parent_id'] ?? null,
            'user_id' => auth()->id(),
            'reviewer_name' => $data['reviewer_name'] ?? (auth()->user()->name ?? null),
            'reviewer_email' => $data['reviewer_email'] ?? (auth()->user()->email ?? null),
            'rating' => isset($data['rating']) ? (int)$data['rating'] : 0, // 0 or null for replies
            'title' => null,
            'content' => $data['content'],
            'status' => 'pending', // Replies also need approval? Let's assume yes.
            'is_verified_purchase' => false,
        ]);

        // Realtime notification update
        $this->updateActivityLog('review', $review->id);
        
        $message = $request->has('parent_id') ? 'Đã gửi phản hồi, vui lòng chờ duyệt.' : 'Đã gửi đánh giá, vui lòng chờ duyệt.';
        return redirect()->route('sanpham.show', $slug)->with('success', $message);
    }

    public function category(string $slug)
    {
        $category = \App\Models\Category::where('slug', $slug)->where('is_active', true)->firstOrFail();
        $request = request();
        $query = Product::with([
            'variants' => function($q){ $q->select('id','product_id','price','compare_at_price','inventory_quantity','is_active'); },
            'images'
        ])->where('is_active', true);

        $baseCategoryId = $category->id;
        
        // Fetch children for sidebar and scoping
        $childCategories = $category->children()->where('is_active', true)->orderBy('name')->get(['id','name']);
        
        // Scope includes ONLY the current category and its DIRECT children
        // We do NOT include siblings in the scope even if we show them in the sidebar
        $scopeIds = $childCategories->pluck('id')->push($baseCategoryId)->unique()->all();

        // Sidebar logic: Show only direct children. If empty, show nothing.
        $sidebarCategories = $childCategories;

        $categoryIds = collect((array)$request->query('categories', []))->map(fn($v)=>(int)$v)->filter()->values();
        $singleCategoryId = (int)$request->query('category', 0);
        
        // Validate single category param
        if ($singleCategoryId && !in_array($singleCategoryId, $scopeIds)) {
            $singleCategoryId = 0;
        }

        $categoryId = 0;
        if ($categoryIds->isNotEmpty()) {
            // Intersect requested categories with scope
            $validRequestIds = $categoryIds->intersect($scopeIds);
            
            if ($validRequestIds->isNotEmpty()) {
                $query->whereIn('category_id', $validRequestIds->all());
            } else {
                // User requested categories but none are in scope -> 0 results
                $query->whereRaw('1 = 0');
            }
        } elseif ($singleCategoryId) {
            $query->where('category_id', $singleCategoryId);
            $categoryId = $singleCategoryId;
        } else {
            // Default: Show products in current category and its children
            $query->whereIn('category_id', $scopeIds);
            // $categoryId variable is used for active filters logic later, 
            // but strictly speaking we are showing multiple categories (scope).
            // We'll leave $categoryId as $baseCategoryId if we want to show it as "active"? 
            // The original code did: $categoryId = $baseCategoryId;
            $categoryId = $baseCategoryId; 
        }

        // Override the global categories list with sidebar categories (children or siblings)
        $categories = $sidebarCategories;

        $collectionName = trim((string)$request->query('collection', ''));
        if ($collectionName !== '') {
            $query->where('collection', $collectionName);
        }

        $search = trim((string)$request->query('q', ''));
        if ($search !== '') {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                  ->orWhere('slug', 'like', '%'.$search.'%')
                  ->orWhere('material', 'like', '%'.$search.'%');
            });
        }

        $priceRanges = collect((array)$request->query('price_ranges', []))->map(fn($v)=>trim((string)$v))->filter()->values();
        if ($priceRanges->isNotEmpty()) {
            $query->where(function($outer) use ($priceRanges) {
                foreach ($priceRanges as $pr) {
                    $outer->orWhere(function($q) use ($pr) {
                        if ($pr === '0-200000') {
                            $q->where(function($qq){
                                $qq->whereBetween('discounted_price', [0, 200000])
                                   ->orWhereBetween('price', [0, 200000])
                                   ->orWhereHas('variants', function($vq){
                                       $vq->whereBetween('price', [0, 200000]);
                                   });
                            });
                        } elseif ($pr === '200000-300000') {
                            $q->where(function($qq){
                                $qq->whereBetween('discounted_price', [200000, 300000])
                                   ->orWhereBetween('price', [200000, 300000])
                                   ->orWhereHas('variants', function($vq){
                                       $vq->whereBetween('price', [200000, 300000]);
                                   });
                            });
                        } elseif ($pr === '300000-500000') {
                            $q->where(function($qq){
                                $qq->whereBetween('discounted_price', [300000, 500000])
                                   ->orWhereBetween('price', [300000, 500000])
                                   ->orWhereHas('variants', function($vq){
                                       $vq->whereBetween('price', [300000, 500000]);
                                   });
                            });
                        } elseif ($pr === '500000+') {
                            $q->where(function($qq){
                                $qq->where('discounted_price', '>', 500000)
                                   ->orWhere('price', '>', 500000)
                                   ->orWhereHas('variants', function($vq){
                                       $vq->where('price', '>', 500000);
                                   });
                            });
                        }
                    });
                }
            });
        }

        $attributeValueIds = collect((array)$request->query('attr_values', []))->map(fn($v)=>(int)$v)->filter()->values();
        if ($attributeValueIds->isNotEmpty()) {
            $query->whereHas('variants.options', function($oq) use ($attributeValueIds) {
                $oq->whereIn('attribute_value_id', $attributeValueIds->all());
            });
        }

        $ratingMin = (int)$request->query('rating', 0);
        if ($ratingMin) {
            $query->whereHas('reviews', function($rq) use ($ratingMin) {
                $rq->where('rating', '>=', $ratingMin);
            });
        }

        $statuses = collect((array)$request->query('status', []))->map(fn($v)=>trim((string)$v))->filter()->values();
        if ($statuses->contains('in_stock')) {
            $query->where(function($q){
                $q->where('in_stock', true)
                  ->orWhereHas('variants', function($vq){
                      $vq->where('inventory_quantity', '>', 0);
                  });
            });
        }
        if ($statuses->contains('on_sale')) {
            $query->where(function($q){
                $q->whereNotNull('discounted_price')
                  ->whereColumn('discounted_price', '<', 'price')
                  ->orWhereHas('variants', function($vq){
                      $vq->whereNotNull('compare_at_price')
                         ->whereColumn('price', '<', 'compare_at_price');
                  });
            });
        }

        $products = $query->paginate(12)->appends($request->query());
        
        // Dynamic attributes for filtering
        $attributes = \App\Models\ProductAttribute::with(['values' => function($q){
            $q->orderBy('value');
        }])->get();

        // Prepare active filters
        $activeFilters = [];
        if ($search) {
            $activeFilters[] = ['type' => 'q', 'value' => $search, 'label' => 'Tìm kiếm: ' . $search];
        }
        foreach ($categoryIds as $cid) {
             $c = $categories->firstWhere('id', $cid);
             if ($c) {
                 $activeFilters[] = ['type' => 'categories', 'value' => $cid, 'label' => 'Danh mục: ' . $c->name];
             }
        }
        if ($collectionName) {
            $activeFilters[] = ['type' => 'collection', 'value' => $collectionName, 'label' => 'Bộ sưu tập: ' . $collectionName];
        }
        foreach ($priceRanges as $pr) {
             $label = $pr;
             if ($pr === '0-200000') $label = '0 - 200.000đ';
             elseif ($pr === '200000-300000') $label = '200.000đ - 300.000đ';
             elseif ($pr === '300000-500000') $label = '300.000đ - 500.000đ';
             elseif ($pr === '500000+') $label = 'Trên 500.000đ';
             $activeFilters[] = ['type' => 'price_ranges', 'value' => $pr, 'label' => 'Giá: ' . $label];
        }
        foreach ($attributeValueIds as $avId) {
             $valName = null;
             foreach ($attributes as $attr) {
                 $val = $attr->values->firstWhere('id', $avId);
                 if ($val) {
                     $valName = $attr->name . ': ' . $val->value;
                     break;
                 }
             }
             if ($valName) {
                 $activeFilters[] = ['type' => 'attr_values', 'value' => $avId, 'label' => $valName];
             }
        }
        if ($ratingMin) {
             $activeFilters[] = ['type' => 'rating', 'value' => $ratingMin, 'label' => 'Đánh giá: ' . $ratingMin . ' sao trở lên'];
        }
        foreach ($statuses as $st) {
             $label = $st;
             if ($st === 'in_stock') $label = 'Còn hàng';
             elseif ($st === 'on_sale') $label = 'Đang giảm giá';
             $activeFilters[] = ['type' => 'status', 'value' => $st, 'label' => 'Trạng thái: ' . $label];
        }

        $filters = [
            'q' => $search,
            'category' => $categoryId ?: $baseCategoryId,
            'categories' => $categoryIds->all(),
            'collection' => $collectionName ?: '',
            'price_ranges' => $priceRanges->all(),
            'attr_values' => $attributeValueIds->all(),
            'rating' => $ratingMin ?: '',
            'status' => $statuses->all(),
        ];
        
        if ($request->ajax() || $request->query('ajax')) {
            return view('client.products.list_container', compact('products', 'activeFilters'))->render();
        }

        return view('client.products.index', compact('products', 'category','categories','attributes','filters', 'activeFilters'));
    }

    public function collections()
    {
        $collections = \App\Models\Collection::where('is_active', true)
            ->orderBy('sort_order')
            ->get();
        
        foreach ($collections as $collection) {
            $collection->products = Product::where('collection', $collection->name)
                ->where('is_active', true)
                ->with([
                    'variants' => function($q){ 
                        $q->select('id','product_id','price','compare_at_price','inventory_quantity','is_active')
                          ->with(['options.attribute','options.attributeValue','images']);
                    },
                    'images' => function($q){ $q->orderBy('position'); },
                    'reviews' => function($q){ $q->where('status', 'published'); } // For rating
                ])
                ->take(10)
                ->get();
        }

        return view('client.collections.index', compact('collections'));
    }

    public function collection(string $slug)
    {
        $collection = \App\Models\Collection::where('slug', $slug)->where('is_active', true)->firstOrFail();
        $request = request();
        $query = Product::with([
            'variants' => function($q){ $q->select('id','product_id','price','compare_at_price','inventory_quantity','is_active'); },
            'images'
        ])->where('is_active', true)->where('collection', $collection->name);

        $categoryId = (int)$request->query('category', 0);
        $categoryIds = collect((array)$request->query('categories', []))->map(fn($v)=>(int)$v)->filter()->values();
        if ($categoryIds->isNotEmpty()) {
            $query->whereIn('category_id', $categoryIds->all());
        } elseif ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        $search = trim((string)$request->query('q', ''));
        if ($search !== '') {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                  ->orWhere('slug', 'like', '%'.$search.'%')
                  ->orWhere('material', 'like', '%'.$search.'%');
            });
        }

        $priceRanges = collect((array)$request->query('price_ranges', []))->map(fn($v)=>trim((string)$v))->filter()->values();
        if ($priceRanges->isNotEmpty()) {
            $query->where(function($outer) use ($priceRanges) {
                foreach ($priceRanges as $pr) {
                    $outer->orWhere(function($q) use ($pr) {
                        if ($pr === '0-200000') {
                            $q->where(function($qq){
                                $qq->whereBetween('discounted_price', [0, 200000])
                                   ->orWhereBetween('price', [0, 200000])
                                   ->orWhereHas('variants', function($vq){
                                       $vq->whereBetween('price', [0, 200000]);
                                   });
                            });
                        } elseif ($pr === '200000-300000') {
                            $q->where(function($qq){
                                $qq->whereBetween('discounted_price', [200000, 300000])
                                   ->orWhereBetween('price', [200000, 300000])
                                   ->orWhereHas('variants', function($vq){
                                       $vq->whereBetween('price', [200000, 300000]);
                                   });
                            });
                        } elseif ($pr === '300000-500000') {
                            $q->where(function($qq){
                                $qq->whereBetween('discounted_price', [300000, 500000])
                                   ->orWhereBetween('price', [300000, 500000])
                                   ->orWhereHas('variants', function($vq){
                                       $vq->whereBetween('price', [300000, 500000]);
                                   });
                            });
                        } elseif ($pr === '500000+') {
                            $q->where(function($qq){
                                $qq->where('discounted_price', '>', 500000)
                                   ->orWhere('price', '>', 500000)
                                   ->orWhereHas('variants', function($vq){
                                       $vq->where('price', '>', 500000);
                                   });
                            });
                        }
                    });
                }
            });
        }

        $attributeValueIds = collect((array)$request->query('attr_values', []))->map(fn($v)=>(int)$v)->filter()->values();
        if ($attributeValueIds->isNotEmpty()) {
            $query->whereHas('variants.options', function($oq) use ($attributeValueIds) {
                $oq->whereIn('attribute_value_id', $attributeValueIds->all());
            });
        }

        $ratingMin = (int)$request->query('rating', 0);
        if ($ratingMin) {
            $query->whereHas('reviews', function($rq) use ($ratingMin) {
                $rq->where('rating', '>=', $ratingMin);
            });
        }

        $statuses = collect((array)$request->query('status', []))->map(fn($v)=>trim((string)$v))->filter()->values();
        if ($statuses->contains('in_stock')) {
            $query->where(function($q){
                $q->where('in_stock', true)
                  ->orWhereHas('variants', function($vq){
                      $vq->where('inventory_quantity', '>', 0);
                  });
            });
        }
        if ($statuses->contains('on_sale')) {
            $query->where(function($q){
                $q->whereNotNull('discounted_price')
                  ->whereColumn('discounted_price', '<', 'price')
                  ->orWhereHas('variants', function($vq){
                      $vq->whereNotNull('compare_at_price')
                         ->whereColumn('price', '<', 'compare_at_price');
                  });
            });
        }

        $products = $query->paginate(12)->appends($request->query());

        $categories = \App\Models\Category::where('is_active', true)->orderBy('name')->get(['id','name']);

        // Dynamic attributes for filtering
        $attributes = \App\Models\ProductAttribute::with(['values' => function($q){
            $q->orderBy('value');
        }])->get();

        $filters = [
            'q' => $search,
            'category' => $categoryId ?: '',
            'categories' => $categoryIds->all(),
            'collection' => $collection->name,
            'price_ranges' => $priceRanges->all(),
            'attr_values' => $attributeValueIds->all(),
            'rating' => $ratingMin ?: '',
            'status' => $statuses->all(),
        ];

        // Prepare active filters
        $activeFilters = [];
        if ($search) {
            $activeFilters[] = ['type' => 'q', 'value' => $search, 'label' => 'Tìm kiếm: ' . $search];
        }
        foreach ($categoryIds as $cid) {
             $c = $categories->firstWhere('id', $cid);
             if ($c) {
                 $activeFilters[] = ['type' => 'categories', 'value' => $cid, 'label' => 'Danh mục: ' . $c->name];
             }
        }
        foreach ($priceRanges as $pr) {
             $label = $pr;
             if ($pr === '0-200000') $label = '0 - 200.000đ';
             elseif ($pr === '200000-300000') $label = '200.000đ - 300.000đ';
             elseif ($pr === '300000-500000') $label = '300.000đ - 500.000đ';
             elseif ($pr === '500000+') $label = 'Trên 500.000đ';
             $activeFilters[] = ['type' => 'price_ranges', 'value' => $pr, 'label' => 'Giá: ' . $label];
        }
        foreach ($attributeValueIds as $avId) {
             $valName = null;
             foreach ($attributes as $attr) {
                 $val = $attr->values->firstWhere('id', $avId);
                 if ($val) {
                     $valName = $attr->name . ': ' . $val->value;
                     break;
                 }
             }
             if ($valName) {
                 $activeFilters[] = ['type' => 'attr_values', 'value' => $avId, 'label' => $valName];
             }
        }
        if ($ratingMin) {
             $activeFilters[] = ['type' => 'rating', 'value' => $ratingMin, 'label' => 'Đánh giá: ' . $ratingMin . ' sao trở lên'];
        }
        foreach ($statuses as $st) {
             $label = $st;
             if ($st === 'in_stock') $label = 'Còn hàng';
             elseif ($st === 'on_sale') $label = 'Đang giảm giá';
             $activeFilters[] = ['type' => 'status', 'value' => $st, 'label' => 'Trạng thái: ' . $label];
        }

        if ($request->ajax() || $request->query('ajax')) {
            return view('client.products.list_container', compact('products', 'activeFilters'))->render();
        }

        return view('client.products.index', compact('products','collection','categories','attributes','filters', 'activeFilters'));
    }

    public function categoryPath(string $path)
    {
        $segments = collect(explode('/', trim($path, '/')))->filter(fn($s) => $s !== '')->values();
        if ($segments->isEmpty()) { abort(404); }
        $slug = $segments->last();
        $category = \App\Models\Category::where('slug', $slug)->where('is_active', true)->firstOrFail();
        $request = request();
        $query = Product::with([
            'variants' => function($q){ $q->select('id','product_id','price','compare_at_price','inventory_quantity','is_active'); },
            'images'
        ])->where('is_active', true);

        $baseCategoryId = $category->id;
        
        // Fetch children for sidebar and scoping
        $childCategories = $category->children()->where('is_active', true)->orderBy('name')->get(['id','name']);
        $hasChildren = $childCategories->isNotEmpty();
        
        // Scope includes ONLY the current category and its DIRECT children
        $scopeIds = $childCategories->pluck('id')->push($baseCategoryId)->unique()->all();
        
        // Sidebar logic: Show only direct children. If empty, show nothing.
        $sidebarCategories = $childCategories;

        // Override the global categories list with sidebar categories
        $categories = $sidebarCategories;

        $categoryIds = collect((array)$request->query('categories', []))->map(fn($v)=>(int)$v)->filter()->values();
        $singleCategoryId = (int)$request->query('category', 0);

        // Validate single category param
        if ($singleCategoryId && !in_array($singleCategoryId, $scopeIds)) {
            $singleCategoryId = 0;
        }

        $categoryId = 0;
        if ($categoryIds->isNotEmpty()) {
            // Intersect requested categories with scope
            $validRequestIds = $categoryIds->intersect($scopeIds);
            
            if ($validRequestIds->isNotEmpty()) {
                $query->whereIn('category_id', $validRequestIds->all());
            } else {
                 // User requested categories but none are in scope -> 0 results
                $query->whereRaw('1 = 0');
            }
        } elseif ($singleCategoryId) {
            $query->where('category_id', $singleCategoryId);
            $categoryId = $singleCategoryId;
        } else {
            // Default: Show products in current category. If it has children, include them.
            if ($hasChildren) {
                $query->whereIn('category_id', $scopeIds);
            } else {
                $query->where('category_id', $baseCategoryId);
            }
            $categoryId = $baseCategoryId;
        }

        $search = trim((string)$request->query('q', ''));
        if ($search !== '') {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                  ->orWhere('slug', 'like', '%'.$search.'%')
                  ->orWhere('material', 'like', '%'.$search.'%');
            });
        }

        $priceRanges = collect((array)$request->query('price_ranges', []))->map(fn($v)=>trim((string)$v))->filter()->values();
        if ($priceRanges->isNotEmpty()) {
            $query->where(function($outer) use ($priceRanges) {
                foreach ($priceRanges as $pr) {
                    $outer->orWhere(function($q) use ($pr) {
                        if ($pr === '0-200000') {
                            $q->where(function($qq){
                                $qq->whereBetween('discounted_price', [0, 200000])
                                   ->orWhereBetween('price', [0, 200000])
                                   ->orWhereHas('variants', function($vq){
                                       $vq->whereBetween('price', [0, 200000]);
                                   });
                            });
                        } elseif ($pr === '200000-300000') {
                            $q->where(function($qq){
                                $qq->whereBetween('discounted_price', [200000, 300000])
                                   ->orWhereBetween('price', [200000, 300000])
                                   ->orWhereHas('variants', function($vq){
                                       $vq->whereBetween('price', [200000, 300000]);
                                   });
                            });
                        } elseif ($pr === '300000-500000') {
                            $q->where(function($qq){
                                $qq->whereBetween('discounted_price', [300000, 500000])
                                   ->orWhereBetween('price', [300000, 500000])
                                   ->orWhereHas('variants', function($vq){
                                       $vq->whereBetween('price', [300000, 500000]);
                                   });
                            });
                        } elseif ($pr === '500000+') {
                            $q->where(function($qq){
                                $qq->where('discounted_price', '>', 500000)
                                   ->orWhere('price', '>', 500000)
                                   ->orWhereHas('variants', function($vq){
                                       $vq->where('price', '>', 500000);
                                   });
                            });
                        }
                    });
                }
            });
        }

        $attributeValueIds = collect((array)$request->query('attr_values', []))->map(fn($v)=>(int)$v)->filter()->values();
        if ($attributeValueIds->isNotEmpty()) {
            $query->whereHas('variants.options', function($oq) use ($attributeValueIds) {
                $oq->whereIn('attribute_value_id', $attributeValueIds->all());
            });
        }

        $ratingMin = (int)$request->query('rating', 0);
        if ($ratingMin) {
            $query->whereHas('reviews', function($rq) use ($ratingMin) {
                $rq->where('rating', '>=', $ratingMin);
            });
        }

        $statuses = collect((array)$request->query('status', []))->map(fn($v)=>trim((string)$v))->filter()->values();
        if ($statuses->contains('in_stock')) {
            $query->where(function($q){
                $q->where('in_stock', true)
                  ->orWhereHas('variants', function($vq){
                      $vq->where('inventory_quantity', '>', 0);
                  });
            });
        }
        if ($statuses->contains('on_sale')) {
            $query->where(function($q){
                $q->whereNotNull('discounted_price')
                  ->whereColumn('discounted_price', '<', 'price')
                  ->orWhereHas('variants', function($vq){
                      $vq->whereNotNull('compare_at_price')
                         ->whereColumn('price', '<', 'compare_at_price');
                  });
            });
        }

        $products = $query->paginate(12)->appends($request->query());
        
        // Dynamic attributes for filtering
        $attributes = \App\Models\ProductAttribute::with(['values' => function($q){
            $q->orderBy('value');
        }])->get();

        $filters = [
            'q' => $search,
            'category' => $categoryId ?: $baseCategoryId,
            'categories' => $categoryIds->all(),
            'collection' => '',
            'price_ranges' => $priceRanges->all(),
            'attr_values' => $attributeValueIds->all(),
            'rating' => $ratingMin ?: '',
            'status' => $statuses->all(),
        ];
        // Prepare active filters
        $activeFilters = [];
        if ($search) {
            $activeFilters[] = ['type' => 'q', 'value' => $search, 'label' => 'Tìm kiếm: ' . $search];
        }
        foreach ($categoryIds as $cid) {
             $c = $categories->firstWhere('id', $cid);
             if ($c) {
                 $activeFilters[] = ['type' => 'categories', 'value' => $cid, 'label' => 'Danh mục: ' . $c->name];
             }
        }
        foreach ($priceRanges as $pr) {
             $label = $pr;
             if ($pr === '0-200000') $label = '0 - 200.000đ';
             elseif ($pr === '200000-300000') $label = '200.000đ - 300.000đ';
             elseif ($pr === '300000-500000') $label = '300.000đ - 500.000đ';
             elseif ($pr === '500000+') $label = 'Trên 500.000đ';
             $activeFilters[] = ['type' => 'price_ranges', 'value' => $pr, 'label' => 'Giá: ' . $label];
        }
        foreach ($attributeValueIds as $avId) {
             $valName = null;
             foreach ($attributes as $attr) {
                 $val = $attr->values->firstWhere('id', $avId);
                 if ($val) {
                     $valName = $attr->name . ': ' . $val->value;
                     break;
                 }
             }
             if ($valName) {
                 $activeFilters[] = ['type' => 'attr_values', 'value' => $avId, 'label' => $valName];
             }
        }
        if ($ratingMin) {
             $activeFilters[] = ['type' => 'rating', 'value' => $ratingMin, 'label' => 'Đánh giá: ' . $ratingMin . ' sao trở lên'];
        }
        foreach ($statuses as $st) {
             $label = $st;
             if ($st === 'in_stock') $label = 'Còn hàng';
             elseif ($st === 'on_sale') $label = 'Đang giảm giá';
             $activeFilters[] = ['type' => 'status', 'value' => $st, 'label' => 'Trạng thái: ' . $label];
        }

        if ($request->ajax() || $request->query('ajax')) {
            return view('client.products.list_container', compact('products', 'activeFilters'))->render();
        }

        return view('client.products.index', compact('products','category','categories','attributes','filters', 'activeFilters'));
    }

    public function search(Request $request)
    {
        $q = trim((string)$request->query('q', ''));
        $categoryId = (int)$request->query('category_id', 0);
        if ($q === '' && !$categoryId) {
            return response()->json([]);
        }
        $query = Product::with(['images'])->where('is_active', true);
        if ($q !== '') {
            $query->where(function($qq) use ($q) {
                $qq->where('name', 'like', '%'.$q.'%')
                   ->orWhere('slug', 'like', '%'.$q.'%')
                   ->orWhere('material', 'like', '%'.$q.'%');
            });
        }
        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }
        $results = $query->limit(10)->get()->map(function($p){
            $image = null;
            if ($p->relationLoaded('images')) {
                $image = optional($p->images->sortBy('position')->first())->url;
            }
            return [
                'id' => $p->id,
                'name' => $p->name,
                'slug' => $p->slug,
                'price' => $p->price ?? null,
                'image' => $image,
                'url' => $p->slug ? route('products.show', $p->slug) : null,
            ];
        })->values()->all();
        return response()->json($results);
    }

    public function ajaxSearch(Request $request)
    {
        $q = trim((string)$request->query('q', ''));
        if ($q === '') {
            return '';
        }
        
        $products = Product::with(['images', 'variants'])
            ->where('is_active', true)
            ->where(function($qq) use ($q) {
                $qq->where('name', 'like', '%'.$q.'%')
                   ->orWhere('slug', 'like', '%'.$q.'%')
                   ->orWhere('material', 'like', '%'.$q.'%');
            })
            ->limit(12)
            ->get();
            
        $wishlistProductIds = [];
        if (auth()->check()) {
            $wishlistProductIds = \App\Models\Wishlist::where('user_id', auth()->id())->pluck('product_id')->toArray();
        }
        
        $html = '';
        if ($products->isEmpty()) {
            $html = '<div class="col-12 text-center text-white mt-4">Không tìm thấy sản phẩm nào.</div>';
        } else {
            foreach ($products as $product) {
                $html .= '<div class="col-lg-4 col-md-6 col-12 mb-4">';
                $html .= view('client.components.product-item-small', compact('product', 'wishlistProductIds'))->render();
                $html .= '</div>';
            }
        }
        
        return $html;
    }
}
