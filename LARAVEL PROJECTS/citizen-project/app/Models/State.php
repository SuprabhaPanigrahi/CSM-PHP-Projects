<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory;

    protected $table = 'states';
    protected $primaryKey = 'StateId';
    
    protected $fillable = [
        'StateName'
    ];

    public function blocks()
    {
        return $this->hasMany(Block::class, 'StateId', 'StateId');
    }
}