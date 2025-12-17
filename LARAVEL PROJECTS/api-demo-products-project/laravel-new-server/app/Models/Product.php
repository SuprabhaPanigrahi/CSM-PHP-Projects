<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'quantity',
        'category',
        'status',
        'features',
        'color',
        'manufacturing_date',
        'image',
        'gallery_images',
        'is_featured',
        'in_stock'
    ];

    protected $casts = [
        'features' => 'array',
        'gallery_images' => 'array',
        'is_featured' => 'boolean',
        'in_stock' => 'boolean',
        'price' => 'decimal:2',
        'manufacturing_date' => 'date'
    ];
}

?>