<?php
// app/Models/Project.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $table = 'projects';
    protected $primaryKey = 'ProjectId';
    
    protected $fillable = [
        'ProjectCode', 'ProjectName', 'ProjectType', 'Priority',
        'LocationType', 'LocationCountryId', 'TechnologyId',
        'StartDate', 'EndDate', 'IsActive'
    ];

    public function technology()
    {
        return $this->belongsTo(Technology::class, 'TechnologyId');
    }

    public function locationCountry()
    {
        return $this->belongsTo(Country::class, 'LocationCountryId');
    }

    public function allocations()
    {
        return $this->hasMany(EmployeeProjectAllocation::class, 'ProjectId');
    }
}