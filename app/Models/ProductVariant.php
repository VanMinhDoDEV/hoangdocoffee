<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 'sku', 'price', 'compare_at_price', 'cost', 'barcode', 'weight', 'inventory_quantity', 'is_default', 'is_active',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'is_active' => 'boolean',
        'price' => 'float',
        'compare_at_price' => 'float',
        'cost' => 'float',
        'weight' => 'float',
        'inventory_quantity' => 'integer',
    ];

    protected $table = 'product_variants';

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function options()
    {
        return $this->hasMany(ProductVariantOption::class, 'variant_id');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_variant_id');
    }
}
