<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Panchayat extends Model
{
    use HasFactory;

    protected $table = 'panchayats';
    protected $primaryKey = 'PanchayatId';
    
    protected $fillable = [
        'BlockId',
        'PanchayatName'
    ];

    protected $with = ['block.state']; // Eager load relationships

    public function block()
    {
        return $this->belongsTo(Block::class, 'BlockId', 'BlockId');
    }

    public function villages()
    {
        return $this->hasMany(Village::class, 'PanchayatId', 'PanchayatId');
    }
}