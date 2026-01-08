<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Combo extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'price', 'is_active', 'free_shipping'];

    protected $casts = [
        'price' => 'float',
        'is_active' => 'boolean',
        'free_shipping' => 'boolean',
    ];

    protected $table = 'combos';

    public function lines()
    {
        return $this->hasMany(ComboLine::class);
    }
}
