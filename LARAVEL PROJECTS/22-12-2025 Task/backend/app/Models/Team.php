<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'manager_id'];

    // Relationships
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function members()
    {
        return $this->belongsToMany(User::class);
    }

    public function tasks()
    {
        return Task::whereIn('assigned_to', $this->members()->pluck('id'))
                   ->orWhereIn('created_by', $this->members()->pluck('id'));
    }
}