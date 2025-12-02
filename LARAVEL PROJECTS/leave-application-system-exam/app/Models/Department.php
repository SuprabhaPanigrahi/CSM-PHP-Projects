<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'DepartmentId';
    protected $fillable = ['DepartmentName', 'IsActive'];
    
    public function employees()
    {
        return $this->hasMany(Employee::class, 'DepartmentId');
    }
}