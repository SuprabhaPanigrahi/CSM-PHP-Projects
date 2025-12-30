<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $primaryKey = 'product_id';
    protected $fillable = ['name', 'qty', 'rate', 'offer_id'];

    // Add type casting for rate and qty
    protected $casts = [
        'rate' => 'decimal:2',  // Cast rate to decimal with 2 decimal places
        'qty' => 'integer',     // Cast qty to integer
    ];

    public function offer()
    {
        return $this->belongsTo(Offer::class, 'offer_id');
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class, 'product_id', 'product_id');
    }
}