<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessUnit extends Model
{
    use HasFactory;

    protected $table = 'business_units';
    protected $primaryKey = 'BusinessUnitId';
    
    protected $fillable = ['Name', 'Code', 'IsActive'];

    public function departments()
    {
        return $this->hasMany(Department::class, 'BusinessUnitId');
    }
}