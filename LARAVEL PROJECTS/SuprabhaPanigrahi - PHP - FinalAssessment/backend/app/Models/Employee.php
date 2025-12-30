<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $table = 'employees';
    protected $primaryKey = 'id';
    
    // Disable timestamps
    public $timestamps = false;
    
    protected $fillable = [
        'name',
        'email',
        'created_by'
    ];

    // Relationship with user (by email/username)
    public function user()
    {
        return $this->belongsTo(User::class, 'email', 'username');
    }

    // Relationship with creator (user)
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Relationship with projects
    public function projects()
    {
        return $this->belongsToMany(Project::class, 'employee_projects', 'employee_id', 'project_id')
                    ->withPivot('assigned_date');
    }
}