<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name', 
        'price', 
        'category_id', 
        'description', 
        'image'
    ];

    // Define relationship with Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Accessor for full image URL
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            // If image is stored in storage
            return asset('storage/' . $this->image);
        }
        
        return null;
    }
}