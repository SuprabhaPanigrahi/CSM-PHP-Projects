<?php
// app/Models/Employee.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $table = 'employees';
    protected $primaryKey = 'EmployeeId';
    
    protected $fillable = [
        'EmployeeCode', 'FullName', 'TeamId', 'EmployeeStatusId',
        'YearsOfExperience', 'WorkLocationCountryId', 'IsActive'
    ];

    public function team()
    {
        return $this->belongsTo(Team::class, 'TeamId');
    }

    public function status()
    {
        return $this->belongsTo(EmployeeStatus::class, 'EmployeeStatusId');
    }

    public function workLocationCountry()
    {
        return $this->belongsTo(Country::class, 'WorkLocationCountryId');
    }

    public function skills()
    {
        return $this->hasMany(EmployeeSkill::class, 'EmployeeId');
    }

    public function allocations()
    {
        return $this->hasMany(EmployeeProjectAllocation::class, 'EmployeeId');
    }
}