<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Village extends Model
{
    use HasFactory;

    protected $table = 'villages';
    protected $primaryKey = 'VillageId';
    
    protected $fillable = [
        'PanchayatId',
        'VillageName'
    ];

    protected $with = ['panchayat.block.state']; // Eager load relationships

    public function panchayat()
    {
        return $this->belongsTo(Panchayat::class, 'PanchayatId', 'PanchayatId');
    }

    public function citizens()
    {
        return $this->hasMany(Citizen::class, 'VillageId', 'VillageId');
    }
}