<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OptionValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'option_id', 'value', 'slug', 'sort_order', 'is_active',
    ];

    public function option()
    {
        return $this->belongsTo(Option::class);
    }
}

