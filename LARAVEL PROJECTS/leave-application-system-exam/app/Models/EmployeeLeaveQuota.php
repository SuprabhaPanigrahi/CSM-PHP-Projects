<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeLeaveQuota extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'QuotaId';
    protected $fillable = ['EmployeeId', 'LeaveTypeId', 'LeaveYear', 'TotalAllocated', 'TotalUsed'];
    
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'EmployeeId');
    }
    
    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class, 'LeaveTypeId');
    }
}