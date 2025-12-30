<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class User extends Authenticatable
{
    use HasFactory;

    protected $table = 'users';
    protected $primaryKey = 'id';
    
    // Disable timestamps since your existing table doesn't have them
    public $timestamps = false;
    
    protected $fillable = [
        'username',
        'password_hash',
        'role',
        'is_active'
    ];

    protected $hidden = [
        'password_hash'
    ];

    // Get employee record (if user is an employee)
    public function employee()
    {
        return $this->hasOne(Employee::class, 'email', 'username');
    }

    // Relationship with employees (for admin/manager who created employees)
    public function createdEmployees()
    {
        return $this->hasMany(Employee::class, 'created_by');
    }

    // Relationship with projects (for admin/manager who created projects)
    public function createdProjects()
    {
        return $this->hasMany(Project::class, 'created_by');
    }
}