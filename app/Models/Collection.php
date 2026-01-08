<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    use HasFactory;

    protected $fillable = [
        'name','slug','meta_title','meta_description','image_url','sort_order','is_active',
    ];

    public function images()
    {
        return $this->hasMany(CollectionImage::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'collection', 'name');
    }
}
