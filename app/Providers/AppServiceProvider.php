<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Storage;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Blade::anonymousComponentPath(resource_path('views/client/components'), 'client');

        $storeSettings = [];
        try {
            if (Storage::disk('local')->exists('settings.json')) {
                $settings = json_decode(Storage::disk('local')->get('settings.json'), true);
                $storeSettings = $settings['store'] ?? [];
            }
        } catch (\Exception $e) {
            // Fail silently if settings file is corrupted or not accessible
        }

        View::share('storeSettings', $storeSettings);

        View::composer(['client.*'], function ($view) {
            static $wishlistProductIds = null;
            if ($wishlistProductIds === null) {
                $wishlistProductIds = auth()->check() 
                    ? auth()->user()->wishlist()->pluck('product_id')->toArray() 
                    : [];
            }
            $view->with('wishlistProductIds', $wishlistProductIds);
        });
    }
}
