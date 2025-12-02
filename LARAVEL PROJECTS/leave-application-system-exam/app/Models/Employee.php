<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'EmployeeId';
    protected $fillable = ['EmployeeCode', 'FirstName', 'LastName', 'DepartmentId', 'DateOfJoining', 'IsActive'];
    
    public function department()
    {
        return $this->belongsTo(Department::class, 'DepartmentId');
    }
    
    public function leaveQuotas()
    {
        return $this->hasMany(EmployeeLeaveQuota::class, 'EmployeeId');
    }
}