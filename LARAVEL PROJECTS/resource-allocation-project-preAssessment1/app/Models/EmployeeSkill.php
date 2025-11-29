<?php
// app/Models/EmployeeSkill.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeSkill extends Model
{
    use HasFactory;

    protected $table = 'employee_skills';
    protected $primaryKey = 'EmployeeSkillId';
    
    protected $fillable = ['EmployeeId', 'TechnologyId', 'IsPrimarySkill', 'SkillLevel'];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'EmployeeId');
    }

    public function technology()
    {
        return $this->belongsTo(Technology::class, 'TechnologyId');
    }
}