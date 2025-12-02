<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveApplication extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'LeaveApplicationId';
    protected $fillable = ['EmployeeId', 'LeaveTypeId', 'FromDate', 'ToDate', 'TotalDays', 'Reason', 'Status'];
    
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'EmployeeId');
    }
    
    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class, 'LeaveTypeId');
    }
}