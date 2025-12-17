<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'emp_id',
        'dept_name',
        'dept_head',
        'location'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'emp_id');
    }
}