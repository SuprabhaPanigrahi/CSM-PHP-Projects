<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Block extends Model
{
    use HasFactory;

    protected $table = 'blocks';
    protected $primaryKey = 'BlockId';
    
    protected $fillable = [
        'StateId',
        'BlockName'
    ];

    protected $with = ['state']; // Eager load relationships

    public function state()
    {
        return $this->belongsTo(State::class, 'StateId', 'StateId');
    }

    public function panchayats()
    {
        return $this->hasMany(Panchayat::class, 'BlockId', 'BlockId');
    }
}