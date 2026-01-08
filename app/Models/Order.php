<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'status', 'payment_status', 'payment_method', 'subtotal', 'discount_amount', 'tax', 'shipping_cost', 'total', 'coupon_code', 'placed_at',
        'shipping_name', 'shipping_phone', 'shipping_email', 'shipping_province', 'shipping_ward', 'shipping_address', 'note',
    ];

    protected $casts = [
        'placed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function shipment()
    {
        return $this->hasOne(Shipment::class);
    }
}

