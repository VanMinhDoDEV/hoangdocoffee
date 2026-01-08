<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'material', 'description', 'video_url', 'article', 'is_active', 'is_featured',
        'vendor', 'collection', 'category_id', 'status', 'tax',
        'price', 'discounted_price', 'in_stock', 'quantity', 'product_sku',
        'shipping_weight', 'shipping_dimensions', 'shipping_mode', 'payment_method',
        'is_fragile', 'is_biodegradable', 'is_frozen', 'max_temp', 'expiry_date',
        'view_count',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'in_stock' => 'boolean',
        'tax' => 'boolean',
        'is_fragile' => 'boolean',
        'is_biodegradable' => 'boolean',
        'is_frozen' => 'boolean',
        'price' => 'float',
        'discounted_price' => 'float',
        'shipping_weight' => 'float',
        'expiry_date' => 'date',
    ];

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('position');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function sizeCharts()
    {
        return $this->hasMany(SizeChart::class);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function getAvgRatingAttribute()
    {
        // If loaded via withAvg
        if (array_key_exists('reviews_avg_rating', $this->attributes)) {
            return $this->attributes['reviews_avg_rating'];
        }
        
        // If reviews are eagerly loaded
        if ($this->relationLoaded('reviews')) {
            $reviews = $this->reviews->where('status', 'published');
            return $reviews->isNotEmpty() ? $reviews->avg('rating') : 0;
        }

        // Fallback: Query (Note: heavy for lists without eager loading)
        return $this->reviews()->where('status', 'published')->avg('rating') ?? 0;
    }

    public function getReviewCountAttribute()
    {
        if (array_key_exists('reviews_count', $this->attributes)) {
            return $this->attributes['reviews_count'];
        }

        if ($this->relationLoaded('reviews')) {
            return $this->reviews->where('status', 'published')->count();
        }

        return $this->reviews()->where('status', 'published')->count();
    }
}
