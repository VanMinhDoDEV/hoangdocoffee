<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromotionRule extends Model
{
    protected $fillable = [
        'name',
        'type',
        'condition_json',
        'min_total_qty',
        'discount_type',
        'discount_value',
        'requires_code',
        'promo_code',
        'starts_at',
        'ends_at',
        'is_active',
        'free_shipping',
    ];

    protected $casts = [
        'min_total_qty' => 'integer',
        'discount_value' => 'float',
        'requires_code' => 'boolean',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'is_active' => 'boolean',
        'free_shipping' => 'boolean',
    ];

    protected $table = 'promotion_rules';
}
