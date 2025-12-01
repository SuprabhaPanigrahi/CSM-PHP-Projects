<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    use HasFactory;

    protected $primaryKey = 'EmployeeId';
    protected $fillable = ['EmployeeCode', 'FullName', 'Email', 'IsActive'];

    public function projectAssignments(): HasMany
    {
        return $this->hasMany(ProjectAssignment::class, 'EmployeeId');
    }
}