<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $table = 'projects';
    protected $primaryKey = 'id';
    
    public $timestamps = false;
    
    protected $fillable = [
        'project_name',
        'start_date',
        'end_date',
        'created_by'
    ];

    // Relationship with creator (user)
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Relationship with employees
    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'employee_projects', 'project_id', 'employee_id')
                    ->withPivot('assigned_date');
    }
}