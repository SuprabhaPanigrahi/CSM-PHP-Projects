<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $fillable = ['username', 'password', 'role'];
    
    protected $hidden = ['password'];
    
    public function isManager()
    {
        return $this->role === 'MANAGER';
    }
    
    public function isEmployee()
    {
        return $this->role === 'EMPLOYEE';
    }
}