<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OptionSet extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'name', 'description', 'sort_order', 'is_active',
    ];

    public function setOptions()
    {
        return $this->hasMany(OptionSetOption::class);
    }

    public function options()
    {
        return $this->belongsToMany(Option::class, 'option_set_options');
    }
}

