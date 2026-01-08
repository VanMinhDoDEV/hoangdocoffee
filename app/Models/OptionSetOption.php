<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OptionSetOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'option_set_id', 'option_id', 'sort_order',
    ];

    public function optionSet()
    {
        return $this->belongsTo(OptionSet::class);
    }

    public function option()
    {
        return $this->belongsTo(Option::class);
    }
}

