<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;

    protected $fillable = ['percentage', 'details'];

    public function products()
    {
        return $this->hasMany(Product::class, 'offer_id');
    }
}