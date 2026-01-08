<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollectionImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'collection_id', 'url', 'is_primary', 'position',
    ];

    public function collection()
    {
        return $this->belongsTo(Collection::class);
    }
}

