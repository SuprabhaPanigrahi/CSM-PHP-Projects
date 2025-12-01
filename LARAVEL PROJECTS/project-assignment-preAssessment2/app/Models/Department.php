<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    use HasFactory;

    protected $primaryKey = 'DepartmentId';
    protected $fillable = ['DepartmentCode', 'DepartmentName', 'Status'];

    public function teams(): HasMany
    {
        return $this->hasMany(Team::class, 'DepartmentId');
    }
    
    public function activeTeams(): HasMany
    {
        return $this->teams()->where('Status', 'Active');
    }
}