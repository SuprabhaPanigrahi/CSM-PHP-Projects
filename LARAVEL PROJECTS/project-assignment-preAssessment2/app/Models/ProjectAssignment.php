<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'ProjectId');
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'EmployeeId');
    }
}