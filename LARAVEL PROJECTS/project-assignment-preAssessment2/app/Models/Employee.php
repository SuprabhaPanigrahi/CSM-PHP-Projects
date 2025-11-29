<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $primaryKey = 'EmployeeId';
    protected $fillable = ['EmployeeCode', 'FullName', 'Email', 'IsActive'];

    public function projectAssignments()
    {
        return $this->hasMany(ProjectAssignment::class, 'EmployeeId');
    }
}