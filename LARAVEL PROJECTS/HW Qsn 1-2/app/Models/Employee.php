<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'emp_name', 
        'emp_code', 
        'emp_email', 
        'emp_phone', 
        'joining_date'
    ];

    public function department()
    {
        return $this->hasOne(Department::class, 'emp_id');
    }

    public function salary()
    {
        return $this->hasOne(Salary::class, 'emp_id');
    }
}