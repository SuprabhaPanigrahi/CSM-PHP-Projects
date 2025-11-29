<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $primaryKey = 'DepartmentId';
    protected $fillable = ['DepartmentCode', 'DepartmentName', 'Status'];

    public function teams()
    {
        return $this->hasMany(Team::class, 'DepartmentId');
    }
    
    public function activeTeams()
    {
        return $this->teams()->where('Status', 'Active');
    }
}