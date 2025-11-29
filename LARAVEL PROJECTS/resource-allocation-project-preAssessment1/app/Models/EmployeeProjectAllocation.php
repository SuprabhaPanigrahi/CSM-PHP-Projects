<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeProjectAllocation extends Model
{
    use HasFactory;

    protected $table = 'employee_project_allocations';
    protected $primaryKey = 'AllocationId';
    
    protected $fillable = [
        'EmployeeId', 'ProjectId', 'AllocationStartDate',
        'AllocationEndDate', 'AllocationPercentage', 'IsActive'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'EmployeeId');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'ProjectId');
    }
}