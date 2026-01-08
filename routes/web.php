<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SizeGuideController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\LocationController;

Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'vi'])) {
        session(['locale' => $locale]);
    }
    return back();
})->name('lang.switch');

Route::get('/', [\App\Http\Controllers\Client\HomeController::class, 'index'])->name('home');

Route::get('/favicon.ico', function () {
    try {
        $raw = \Illuminate\Support\Facades\Storage::disk('local')->exists('settings.json')
            ? \Illuminate\Support\Facades\Storage::disk('local')->get('settings.json')
            : null;
        $data = $raw ? json_decode($raw, true) : [];
        $favicon = $data['store']['favicon'] ?? null;
        $path = $favicon ? public_path($favicon) : public_path('assets/images/favicon.png');
        if (!is_file($path)) {
            $path = public_path('assets/images/favicon.png');
        }
        return response()->file($path)->header('Content-Type', 'image/png');
    } catch (\Throwable $e) {
        return response()->file(public_path('assets/images/favicon.png'))->header('Content-Type', 'image/png');
    }
});

Route::get('/api/provinces', [LocationController::class, 'provinces'])->name('api.provinces');
Route::get('/api/provinces/{code}/communes', [LocationController::class, 'communes'])->name('api.communes');

// Auth
Route::get('/admin/login', [AuthController::class, 'showAdminLogin'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'adminLogin'])->name('admin.login.post');

Route::get('/client/dashboard', [\App\Http\Controllers\ClientDashboardController::class, 'index'])->name('client.dashboard');
Route::post('/client/wishlist/add', [\App\Http\Controllers\ClientDashboardController::class, 'wishlistAdd'])->name('client.wishlist.add');
Route::post('/client/wishlist/remove', [\App\Http\Controllers\ClientDashboardController::class, 'wishlistRemove'])->name('client.wishlist.remove');
Route::post('/client/addresses', [\App\Http\Controllers\ClientDashboardController::class, 'addressStore'])->name('client.addresses.store');
Route::post('/client/profile/avatar', [\App\Http\Controllers\ClientDashboardController::class, 'uploadAvatar'])->name('client.profile.avatar');
Route::post('/client/addresses/{id}/default', [\App\Http\Controllers\ClientDashboardController::class, 'addressDefault'])->whereNumber('id')->name('client.addresses.default');
Route::delete('/client/addresses/{id}', [\App\Http\Controllers\ClientDashboardController::class, 'addressDestroy'])->whereNumber('id')->name('client.addresses.delete');
Route::get('/client/wishlist', [\App\Http\Controllers\ClientDashboardController::class, 'wishlist'])->name('client.wishlist');
Route::get('/yeu-thich', [\App\Http\Controllers\ClientDashboardController::class, 'wishlist'])->name('client.wishlist.public');
Route::get('/client/addresses', [\App\Http\Controllers\ClientDashboardController::class, 'addresses'])->name('client.addresses');
Route::get('/client/dashboard/orders', [\App\Http\Controllers\ClientDashboardController::class, 'orders'])->name('client.dashboard.orders');
Route::get('/client/measurements', [\App\Http\Controllers\ClientDashboardController::class, 'measurements'])->name('client.measurements');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showLogin'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/san-pham', [ProductController::class, 'index'])->name('sanpham.index');
Route::get('/products/search', [ProductController::class, 'search'])->name('products.search');
Route::get('/products/ajax-search', [ProductController::class, 'ajaxSearch'])->name('products.ajax_search');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/products/{slug}/quick-view', [ProductController::class, 'quickView'])->name('products.quick_view');
Route::get('/san-pham/{slug}', [ProductController::class, 'show'])->name('sanpham.show');
Route::post('/products/{slug}/reviews', [ProductController::class, 'storeReview'])->name('products.reviews.store');
Route::post('/san-pham/{slug}/reviews', [ProductController::class, 'storeReview'])->name('sanpham.reviews.store');
Route::get('/categories/{slug}', [ProductController::class, 'category'])->name('categories.show');
Route::get('/collections/{slug}', [ProductController::class, 'collection'])->name('collections.show');
Route::get('/danh-muc/{slug}', [ProductController::class, 'category'])->name('danhmuc.show');
Route::get('/bo-suu-tap', [ProductController::class, 'collections'])->name('collections.index');
Route::get('/bo-suu-tap/{slug}', [ProductController::class, 'collection'])->name('bosuutap.show');
Route::get('/categories/{path}', [ProductController::class, 'categoryPath'])->where('path', '.*');
Route::get('/danh-muc/{path}', [ProductController::class, 'categoryPath'])->where('path', '.*');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/update-all', [CartController::class, 'updateAll'])->name('cart.update_all');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
Route::post('/cart/apply-promo', [CartController::class, 'applyPromo'])->name('cart.apply_promo');

Route::post('/checkout/buy-now', [CheckoutController::class, 'buyNow'])->name('checkout.buy_now');
Route::match(['get', 'post'], '/checkout/cart', [CheckoutController::class, 'fromCart'])->name('checkout.from_cart');
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');

Route::post('/order', [OrderController::class, 'store'])->name('orders.store');
Route::get('/order/{orderId}', [OrderController::class, 'show'])->name('orders.show');
Route::get('/order/ajax/{orderId}', [OrderController::class, 'getOrderDetailHtml'])->name('orders.ajax_show');

// Blog Frontend
Route::get('/blog', [\App\Http\Controllers\PostController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [\App\Http\Controllers\PostController::class, 'show'])->name('blog.show');
Route::post('/blog/{slug}/comments', [\App\Http\Controllers\PostController::class, 'storeComment'])->name('blog.comments.store');

// Blog category aliases without /blog prefix
Route::get('/chuyen-muc/{slug}', [\App\Http\Controllers\PostController::class, 'category'])->name('blog.chuyenmuc.show');

Route::get('/blog/tag/{slug}', [\App\Http\Controllers\PostController::class, 'tag'])->name('blog.tag');

Route::get('/chuyen-muc/{path}', [\App\Http\Controllers\PostController::class, 'resolveBlogPath'])->where('path', '.*')->name('blog.chuyenmuc.path');
Route::get('/{path}/{slug}', [\App\Http\Controllers\PostController::class, 'resolveBlogPath'])->where('path', '^(?!admin).+')->name('blog.show.path');



// Admin (yêu cầu đăng nhập và là admin)
Route::middleware(['web','auth','admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/global-search', [AdminController::class, 'globalSearch'])->name('admin.global_search');
    Route::get('/admin/products', [AdminController::class, 'products'])->name('admin.products');
    Route::get('/admin/products/create', [AdminController::class, 'productCreate'])->name('admin.products.create');
    Route::post('/admin/products', [AdminController::class, 'productStore'])->name('admin.products.store');
    Route::get('/admin/products/json', [AdminController::class, 'productsJson'])->name('admin.products.json');
    Route::get('/admin/products/{id}', [AdminController::class, 'productShow'])->whereNumber('id')->name('admin.products.view');
    Route::get('/admin/products/{id}/edit', [AdminController::class, 'productEdit'])->whereNumber('id')->name('admin.products.edit');
    Route::put('/admin/products/{id}', [AdminController::class, 'productUpdate'])->whereNumber('id')->name('admin.products.update');
    Route::delete('/admin/products/{id}', [AdminController::class, 'productDestroy'])->whereNumber('id')->name('admin.products.delete');
    Route::post('/admin/products/bulk-delete', [AdminController::class, 'productsBulkDelete'])->name('admin.products.bulk_delete');
    Route::post('/admin/products/upload-image', [AdminController::class, 'uploadProductImage'])->name('admin.products.upload_image');
    Route::get('/admin/products/categories', [AdminController::class, 'categories'])->name('admin.products.categories');
    Route::post('/admin/products/categories', [AdminController::class, 'categoryStore'])->name('admin.products.categories.store');
    Route::put('/admin/products/categories/{id}', [AdminController::class, 'categoryUpdate'])->whereNumber('id')->name('admin.products.categories.update');
    Route::delete('/admin/products/categories/{id}', [AdminController::class, 'categoryDestroy'])->whereNumber('id')->name('admin.products.categories.destroy');
    Route::post('/admin/products/categories/upload-image', [AdminController::class, 'uploadCategoryImage'])->name('admin.products.categories.upload_image');
    Route::get('/admin/products/collections', [AdminController::class, 'collections'])->name('admin.products.collections');
    Route::post('/admin/products/collections', [AdminController::class, 'collectionStore'])->name('admin.products.collections.store');
    Route::post('/admin/products/collections/upload-image', [AdminController::class, 'uploadCollectionImage'])->name('admin.products.collections.upload_image');
    Route::put('/admin/products/collections/{id}', [AdminController::class, 'collectionUpdate'])->whereNumber('id')->name('admin.products.collections.update');
    Route::delete('/admin/products/collections/{id}', [AdminController::class, 'collectionDestroy'])->whereNumber('id')->name('admin.products.collections.destroy');
    // Quản lý thuộc tính sản phẩm
    Route::get('/admin/products/attributes', [\App\Http\Controllers\SkuController::class, 'index'])->name('admin.products.attributes');
    Route::post('/admin/products/attributes', [\App\Http\Controllers\SkuController::class, 'store'])->name('admin.products.attributes.store');
    Route::get('/admin/orders', [AdminController::class, 'orders'])->name('admin.orders');
    Route::get('/admin/orders/export', [AdminController::class, 'exportOrders'])->name('admin.orders.export');
    Route::get('/admin/orders/{id}/invoice', [AdminController::class, 'orderInvoice'])->whereNumber('id')->name('admin.orders.invoice');
    Route::get('/admin/orders/{id}', [AdminController::class, 'orderShow'])->whereNumber('id')->name('admin.orders.show');
    Route::put('/admin/orders/{id}', [AdminController::class, 'orderUpdate'])->whereNumber('id')->name('admin.orders.update');
    Route::delete('/admin/orders/{id}', [AdminController::class, 'orderDestroy'])->whereNumber('id')->name('admin.orders.destroy');
    Route::get('/admin/orders/ajax/{id}', [AdminController::class, 'getOrderDetailHtml'])->whereNumber('id')->name('admin.orders.ajax_show');
    Route::get('/admin/customers', [AdminController::class, 'customers'])->name('admin.customers');
    Route::post('/admin/customers', [AdminController::class, 'userStore'])->name('admin.customers.store');
    Route::get('/admin/customers/{id}', [AdminController::class, 'userShow'])->whereNumber('id')->name('admin.customers.show');
    Route::put('/admin/customers/{id}', [AdminController::class, 'userUpdate'])->whereNumber('id')->name('admin.customers.update');
    Route::delete('/admin/customers/{id}', [AdminController::class, 'userDestroy'])->whereNumber('id')->name('admin.customers.destroy');
    Route::post('/admin/customers/{id}/addresses', [AdminController::class, 'customerAddressStore'])->whereNumber('id')->name('admin.customers.addresses.store');
    Route::put('/admin/customers/{id}/addresses/{addressId}', [AdminController::class, 'customerAddressUpdate'])->whereNumber('id')->whereNumber('addressId')->name('admin.customers.addresses.update');
    Route::post('/admin/customers/{id}/addresses/{addressId}/default', [AdminController::class, 'customerAddressDefault'])->whereNumber('id')->whereNumber('addressId')->name('admin.customers.addresses.default');
    Route::delete('/admin/customers/{id}/addresses/{addressId}', [AdminController::class, 'customerAddressDestroy'])->whereNumber('id')->whereNumber('addressId')->name('admin.customers.addresses.destroy');
    Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
    Route::get('/admin/users/{id}', [AdminController::class, 'userShow'])->whereNumber('id')->name('admin.users.show');
    Route::post('/admin/users', [AdminController::class, 'userStore'])->name('admin.users.store');
    Route::put('/admin/users/{id}', [AdminController::class, 'userUpdate'])->whereNumber('id')->name('admin.users.update');
    Route::delete('/admin/users/{id}', [AdminController::class, 'userDestroy'])->whereNumber('id')->name('admin.users.destroy');
    Route::post('/admin/users/{id}/verify-email', [AdminController::class, 'userVerifyEmail'])->whereNumber('id')->name('admin.users.verify_email');
    Route::post('/admin/users/{id}/toggle-role', [AdminController::class, 'userToggleRole'])->whereNumber('id')->name('admin.users.toggle_role');
    Route::get('/admin/products/attributes/json', [AdminController::class, 'productAttributesJson'])->name('admin.products.attributes_json');
    Route::get('/admin/products/attributes/{attributeId}/values/json', [AdminController::class, 'productAttributeValuesJson'])->name('admin.products.attribute_values_json');
    Route::get('/admin/products/collections/json', [AdminController::class, 'productCollectionsJson'])->name('admin.products.collections_json');
    Route::get('/admin/products/materials/json', [AdminController::class, 'productMaterialsJson'])->name('admin.products.materials_json');
    Route::post('/admin/products/materials/delete', [AdminController::class, 'productMaterialDelete'])->name('admin.products.materials.delete');
    Route::get('/admin/warehouses', [AdminController::class, 'warehouses'])->name('admin.warehouses');
    Route::post('/admin/warehouses', [AdminController::class, 'warehouseStore'])->name('admin.warehouses.store');
    Route::put('/admin/warehouses/{id}', [AdminController::class, 'warehouseUpdate'])->whereNumber('id')->name('admin.warehouses.update');
    Route::delete('/admin/warehouses/{id}', [AdminController::class, 'warehouseDestroy'])->whereNumber('id')->name('admin.warehouses.destroy');
    Route::get('/admin/inventory', [AdminController::class, 'inventory'])->name('admin.inventory');
    Route::get('/admin/inventory/variants/search', [AdminController::class, 'inventoryVariantsSearch'])->name('admin.inventory.search_variant');
    Route::get('/admin/inventory/movements', [AdminController::class, 'stockMovements'])->name('admin.inventory.movements');
    Route::get('/admin/inventory/movements/json', [AdminController::class, 'stockMovementsJson'])->name('admin.inventory.movements_json');
    Route::post('/admin/inventory/receipt', [AdminController::class, 'inventoryReceipt'])->name('admin.inventory.receipt');
    Route::post('/admin/inventory/adjustment', [AdminController::class, 'inventoryAdjustment'])->name('admin.inventory.adjustment');
    Route::post('/admin/inventory/transfer', [AdminController::class, 'inventoryTransfer'])->name('admin.inventory.transfer');

    // Proxy API for Address
    Route::get('/admin/api/provinces', [AdminController::class, 'proxyProvinces'])->name('admin.api.provinces');
    Route::get('/admin/api/provinces/{code}/communes', [AdminController::class, 'proxyCommunes'])->name('admin.api.communes');

    // Reports
    Route::get('/admin/reports', [\App\Http\Controllers\ReportController::class, 'index'])->name('admin.reports.index');
    Route::get('/admin/reports/revenue', [\App\Http\Controllers\ReportController::class, 'revenue'])->name('admin.reports.revenue');
    Route::get('/admin/reports/products', [\App\Http\Controllers\ReportController::class, 'products'])->name('admin.reports.products');
    Route::get('/admin/reports/customers', [\App\Http\Controllers\ReportController::class, 'customers'])->name('admin.reports.customers');

    Route::get('/admin/settings', [AdminController::class, 'settingsGeneral'])->name('admin.settings.general');
    Route::get('/admin/settings/store', [AdminController::class, 'settingsStore'])->name('admin.settings.store');
    Route::post('/admin/settings/store', [AdminController::class, 'settingsStoreSave'])->name('admin.settings.store.save');
    Route::get('/admin/settings/payment', [AdminController::class, 'settingsPayment'])->name('admin.settings.payment');
    Route::post('/admin/settings/payment', [AdminController::class, 'settingsPaymentSave'])->name('admin.settings.payment.save');
    Route::get('/admin/settings/shipping', [AdminController::class, 'settingsShipping'])->name('admin.settings.shipping');
    Route::post('/admin/settings/shipping', [AdminController::class, 'settingsShippingSave'])->name('admin.settings.shipping.save');
    Route::get('/admin/settings/profile', [AdminController::class, 'settingsProfile'])->name('admin.settings.profile');
    Route::post('/admin/settings/profile', [AdminController::class, 'settingsProfileSave'])->name('admin.settings.profile.save');
    Route::post('/admin/settings/profile/avatar', [AdminController::class, 'uploadAvatar'])->name('admin.settings.profile.avatar');
    Route::post('/admin/settings/general/favicon', [AdminController::class, 'uploadFavicon'])->name('admin.settings.general.favicon');
    Route::post('/admin/settings/general/social-image', [AdminController::class, 'uploadSocialImage'])->name('admin.settings.general.social_image');
    Route::post('/admin/settings/menus', [AdminController::class, 'settingsMenusSave'])->name('admin.settings.menus.save');
    
    // Blog Posts Views
    Route::get('/admin/posts', [AdminController::class, 'posts'])->name('admin.posts.index');
    Route::get('/admin/posts/create', [AdminController::class, 'postCreate'])->name('admin.posts.create');
    Route::post('/admin/posts', [AdminController::class, 'postStore'])->name('admin.posts.store');
    Route::get('/admin/posts/{id}/edit', [AdminController::class, 'postEdit'])->whereNumber('id')->name('admin.posts.edit');
    Route::put('/admin/posts/{id}', [AdminController::class, 'postUpdate'])->whereNumber('id')->name('admin.posts.update');
    Route::delete('/admin/posts/{id}', [AdminController::class, 'postDestroy'])->whereNumber('id')->name('admin.posts.destroy');

    // Blog Categories
    Route::get('/admin/posts/categories', [\App\Http\Controllers\PostCategoryController::class, 'index'])->name('admin.posts.categories');
    Route::post('/admin/posts/categories', [\App\Http\Controllers\PostCategoryController::class, 'store'])->name('admin.posts.categories.store');
    Route::put('/admin/posts/categories/{id}', [\App\Http\Controllers\PostCategoryController::class, 'update'])->whereNumber('id')->name('admin.posts.categories.update');
    Route::delete('/admin/posts/categories/{id}', [\App\Http\Controllers\PostCategoryController::class, 'destroy'])->whereNumber('id')->name('admin.posts.categories.destroy');

    // Post Comments
    Route::get('/admin/posts/comments', [AdminController::class, 'postComments'])->name('admin.posts.comments');
    Route::put('/admin/posts/comments/{id}', [AdminController::class, 'postCommentUpdate'])->whereNumber('id')->name('admin.posts.comments.update');
    Route::post('/admin/posts/comments/{id}/reply', [AdminController::class, 'postCommentReply'])->whereNumber('id')->name('admin.posts.comments.reply');
    Route::delete('/admin/posts/comments/{id}', [AdminController::class, 'postCommentDestroy'])->whereNumber('id')->name('admin.posts.comments.destroy');

    // Blog Tags
    Route::get('/admin/posts/tags', [\App\Http\Controllers\PostTagController::class, 'index'])->name('admin.posts.tags');
    Route::post('/admin/posts/tags', [\App\Http\Controllers\PostTagController::class, 'store'])->name('admin.posts.tags.store');
    Route::put('/admin/posts/tags/{id}', [\App\Http\Controllers\PostTagController::class, 'update'])->whereNumber('id')->name('admin.posts.tags.update');
    Route::delete('/admin/posts/tags/{id}', [\App\Http\Controllers\PostTagController::class, 'destroy'])->whereNumber('id')->name('admin.posts.tags.destroy');

    // Product Reviews
    Route::get('/admin/reviews', [\App\Http\Controllers\Admin\ReviewController::class, 'index'])->name('admin.reviews.index');
    Route::put('/admin/reviews/{id}', [\App\Http\Controllers\Admin\ReviewController::class, 'update'])->whereNumber('id')->name('admin.reviews.update');

    // Combos
    // Promotion Overview
    Route::get('/admin/promotions-overview', [AdminController::class, 'promotionsOverview'])->name('admin.promotions.overview');

    Route::get('/admin/combos', [AdminController::class, 'combos'])->name('admin.combos.index');
    Route::post('/admin/combos', [AdminController::class, 'comboStore'])->name('admin.combos.store');
    Route::get('/admin/combos/{id}/edit', [AdminController::class, 'comboEdit'])->whereNumber('id')->name('admin.combos.edit');
    Route::put('/admin/combos/{id}', [AdminController::class, 'comboUpdate'])->whereNumber('id')->name('admin.combos.update');
    Route::delete('/admin/combos/{id}', [AdminController::class, 'comboDestroy'])->whereNumber('id')->name('admin.combos.destroy');
    Route::post('/admin/combos/{id}/lines', [AdminController::class, 'comboLineStore'])->whereNumber('id')->name('admin.combos.lines.store');
    Route::put('/admin/combos/{id}/lines/{lineId}', [AdminController::class, 'comboLineUpdate'])->whereNumber('id')->whereNumber('lineId')->name('admin.combos.lines.update');
    Route::delete('/admin/combos/{id}/lines/{lineId}', [AdminController::class, 'comboLineDestroy'])->whereNumber('id')->whereNumber('lineId')->name('admin.combos.lines.destroy');
    Route::get('/admin/combos/products/search', [AdminController::class, 'combosProductsSearch'])->name('admin.combos.products.search');

    // Volume Pricing
    Route::get('/admin/volume-pricing', [AdminController::class, 'volumePricing'])->name('admin.volume_pricing.index');
    Route::post('/admin/volume-pricing', [AdminController::class, 'volumePricingStore'])->name('admin.volume_pricing.store');
    Route::put('/admin/volume-pricing/{id}', [AdminController::class, 'volumePricingUpdate'])->whereNumber('id')->name('admin.volume_pricing.update');
    Route::delete('/admin/volume-pricing/{id}', [AdminController::class, 'volumePricingDestroy'])->whereNumber('id')->name('admin.volume_pricing.destroy');

    // Promotion Rules (Mix & Match)
    Route::get('/admin/promotion-rules', [AdminController::class, 'promotionRules'])->name('admin.promotion_rules.index');
    Route::post('/admin/promotion-rules', [AdminController::class, 'promotionRuleStore'])->name('admin.promotion_rules.store');
    Route::put('/admin/promotion-rules/{id}', [AdminController::class, 'promotionRuleUpdate'])->whereNumber('id')->name('admin.promotion_rules.update');
    Route::delete('/admin/promotion-rules/{id}', [AdminController::class, 'promotionRuleDestroy'])->whereNumber('id')->name('admin.promotion_rules.destroy');
    
    // Vietnamese Aliases
    // Products
    Route::get('/admin/san-pham', [AdminController::class, 'products'])->name('admin.products.vi');
    Route::get('/admin/san-pham/them-moi', [AdminController::class, 'productCreate'])->name('admin.products.create.vi');
    Route::get('/admin/san-pham/danh-muc', [AdminController::class, 'categories'])->name('admin.products.categories.vi');
    Route::get('/admin/san-pham/bo-suu-tap', [AdminController::class, 'collections'])->name('admin.products.collections.vi');
    Route::get('/admin/san-pham/thuoc-tinh', [\App\Http\Controllers\SkuController::class, 'index'])->name('admin.products.attributes.vi');
    Route::get('/admin/kho-hang', [AdminController::class, 'inventory'])->name('admin.inventory.vi');

    // Combos
    Route::get('/admin/khuyen-mai', [AdminController::class, 'combos'])->name('admin.combos.index.vi');
    Route::get('/admin/gia-theo-so-luong', [AdminController::class, 'volumePricing'])->name('admin.volume_pricing.index.vi');
    Route::get('/admin/luat-mix-match', [AdminController::class, 'promotionRules'])->name('admin.promotion_rules.index.vi');

    Route::delete('/admin/reviews/{id}', [\App\Http\Controllers\Admin\ReviewController::class, 'destroy'])->whereNumber('id')->name('admin.reviews.destroy');
    Route::post('/admin/reviews/bulk-action', [\App\Http\Controllers\Admin\ReviewController::class, 'bulkAction'])->name('admin.reviews.bulk_action');
});
