<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $primaryKey = 'TeamId';
    protected $fillable = ['TeamCode', 'TeamName', 'DepartmentId', 'Status'];

    public function department()
    {
        return $this->belongsTo(Department::class, 'DepartmentId');
    }

    public function projects()
    {
        return $this->hasMany(Project::class, 'TeamId');
    }
    
    public function activeProjects()
    {
        return $this->projects()->where('Status', 'Active');
    }
    
    public function hasEligibleProjects()
    {
        return $this->Status === 'Active' && 
               $this->projects()
                   ->where('IsBillable', true)
                   ->where('Status', 'Active')
                   ->exists();
    }
}