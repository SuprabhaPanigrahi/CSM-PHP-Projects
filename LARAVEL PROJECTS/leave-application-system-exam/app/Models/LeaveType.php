<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'LeaveTypeId';
    protected $fillable = ['LeaveTypeCode', 'LeaveTypeName', 'IsPaidLeave', 'IsActive'];
}