<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComboLine extends Model
{
    protected $fillable = ['combo_id', 'product_variant_id', 'product_id', 'quantity'];

    protected $casts = [
        'combo_id' => 'integer',
        'product_variant_id' => 'integer',
        'product_id' => 'integer',
        'quantity' => 'integer',
    ];

    protected $table = 'combo_lines';

    public function combo()
    {
        return $this->belongsTo(Combo::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }
    
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
