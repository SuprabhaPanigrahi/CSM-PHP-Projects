<?php
// app/Models/Technology.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Technology extends Model
{
    use HasFactory;

    protected $table = 'technologies';
    protected $primaryKey = 'TechnologyId';
    
    protected $fillable = ['Name', 'Code'];
}