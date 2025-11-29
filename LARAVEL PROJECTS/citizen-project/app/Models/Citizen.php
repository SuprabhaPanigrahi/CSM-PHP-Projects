<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Citizen extends Model
{
    use HasFactory;

    protected $table = 'citizens';
    protected $primaryKey = 'CitizenId';
    
    protected $fillable = [
        'VillageId',
        'CitizenName',
        'CitizenGender',
        'CitizenPhone',
        'CitizenEmail'
    ];

    protected $with = ['village.panchayat.block.state']; // Eager load relationships

    public function village()
    {
        return $this->belongsTo(Village::class, 'VillageId', 'VillageId');
    }
}