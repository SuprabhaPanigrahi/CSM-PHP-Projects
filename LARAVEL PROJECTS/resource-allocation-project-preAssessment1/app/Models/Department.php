<?php
// app/Models/Department.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $table = 'departments';
    protected $primaryKey = 'DepartmentId';
    
    protected $fillable = ['BusinessUnitId', 'Name', 'Code', 'IsActive'];

    public function businessUnit()
    {
        return $this->belongsTo(BusinessUnit::class, 'BusinessUnitId');
    }

    public function teams()
    {
        return $this->hasMany(Team::class, 'DepartmentId');
    }
}