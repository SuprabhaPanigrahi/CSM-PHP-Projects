<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'firstname',
        'lastname',
        'department',
        'date_of_birth'
    ];

    protected $casts = [
        'date_of_birth' => 'date'
    ];

    public function leaves(): HasMany
    {
        return $this->hasMany(Leave::class);
    }
}