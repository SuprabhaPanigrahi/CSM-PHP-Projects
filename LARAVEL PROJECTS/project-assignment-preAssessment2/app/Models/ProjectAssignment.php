<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectAssignment extends Model
{
    use HasFactory;

    protected $primaryKey = 'AssignmentId';
    protected $fillable = [
        'ProjectId', 'EmployeeId', 'RoleOnProject', 
        'AllocationPercent', 'StartDate', 'EndDate', 'Status'
    ];

    protected $casts = [
        'StartDate' => 'date',
        'EndDate' => 'date',
        'AllocationPercent' => 'decimal:2'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'ProjectId');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'EmployeeId');
    }
}