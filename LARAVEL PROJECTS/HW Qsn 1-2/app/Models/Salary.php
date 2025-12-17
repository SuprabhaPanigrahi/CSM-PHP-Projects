<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    use HasFactory;

    protected $fillable = [
        'emp_id',
        'basic_salary',
        'allowances',
        'deductions',
        'net_salary',
        'payment_date'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'emp_id');
    }
}