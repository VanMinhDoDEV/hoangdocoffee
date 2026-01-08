<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VolumePricing extends Model
{
    protected $fillable = ['product_id', 'product_variant_id', 'min_qty', 'price', 'is_active', 'free_shipping'];

    protected $casts = [
        'product_id' => 'integer',
        'product_variant_id' => 'integer',
        'min_qty' => 'integer',
        'price' => 'float',
        'is_active' => 'boolean',
        'free_shipping' => 'boolean',
    ];

    protected $table = 'volume_pricings';

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
