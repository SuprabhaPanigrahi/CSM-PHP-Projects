<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'priority',
        'due_date',
        'status',
        'assigned_to',
        'created_by'
    ];

    protected $casts = [
        'due_date' => 'date'
    ];

    // Relationships
    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Helper methods
    public function isHighPriority()
    {
        return $this->priority === 'high';
    }

    public function isOverdue()
    {
        return $this->due_date < now() && $this->status !== 'done';
    }

    public function getPriorityColor()
    {
        return match($this->priority) {
            'high' => 'danger',
            'medium' => 'warning',
            'low' => 'success',
            default => 'secondary'
        };
    }
}