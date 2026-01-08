<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'name', 'type', 'sort_order', 'is_active',
    ];

    public function values()
    {
        return $this->hasMany(OptionValue::class);
    }
}

