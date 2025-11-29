<?php
// app/Models/Team.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $table = 'teams';
    protected $primaryKey = 'TeamId';
    
    protected $fillable = ['DepartmentId', 'Name', 'Code', 'IsActive'];

    public function department()
    {
        return $this->belongsTo(Department::class, 'DepartmentId');
    }

    public function employees()
    {
        return $this->hasMany(Employee::class, 'TeamId');
    }
}