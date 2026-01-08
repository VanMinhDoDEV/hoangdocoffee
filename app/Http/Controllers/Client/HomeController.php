<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Collection;
use App\Models\Testimonial;
use App\Models\Post;

class HomeController extends Controller
{
    public function index()
    {
        // 1. Featured Collections (Tabs)
        // Assuming 'collection' in Product table matches 'name' in Collection table
        $collections = Collection::where('is_active', true)
            ->orderBy('sort_order')
            ->take(5)
            ->get();

        // Load products for each collection
        foreach ($collections as $collection) {
            $collection->products = Product::where('collection', $collection->name)
                ->where('is_active', true)
                ->take(8)
                ->get();
        }

        // 2. Hot Sale Products
        // Products with discounted_price < price
        $hotSaleProducts = Product::where('is_active', true)
            ->whereNotNull('discounted_price')
            ->whereColumn('discounted_price', '<', 'price')
            ->latest()
            ->take(8)
            ->get();

        // If no products with discounted_price, fallback to random active products
        if ($hotSaleProducts->isEmpty()) {
            $hotSaleProducts = Product::where('is_active', true)
                ->inRandomOrder()
                ->take(8)
                ->get();
        }

        // 3. Best Rating Products
        // Assuming relation 'reviews' exists
        $bestRatingProducts = Product::where('is_active', true)
            ->withAvg('reviews', 'rating')
            ->orderByDesc('reviews_avg_rating')
            ->take(8)
            ->get();
        
        // Fallback if no reviews
        if ($bestRatingProducts->isEmpty() || $bestRatingProducts->first()->reviews_avg_rating == null) {
             $bestRatingProducts = Product::where('is_active', true)
                ->orderBy('view_count', 'desc') // or featured
                ->take(8)
                ->get();
        }

        // 4. Testimonials
        $testimonials = Testimonial::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        // 5. Blog Posts
        $latestPosts = Post::where('status', 'published') // Assuming 'status' column exists, check Model
            ->latest()
            ->take(5)
            ->get();

        // 6. Featured Products
        $featuredProducts = Product::featured()
            ->where('is_active', true)
            ->take(3)
            ->get();

        return view('client.home', compact(
            'collections',
            'hotSaleProducts',
            'bestRatingProducts',
            'testimonials',
            'latestPosts',
            'featuredProducts'
        ));
    }
}
