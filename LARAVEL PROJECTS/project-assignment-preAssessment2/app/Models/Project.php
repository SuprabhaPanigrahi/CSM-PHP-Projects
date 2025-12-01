<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;

    protected $primaryKey = 'ProjectId';
    protected $fillable = ['ProjectCode', 'ProjectName', 'TeamId', 'IsBillable', 'Status', 'StartDate', 'EndDate'];

    protected $casts = [
        'IsBillable' => 'boolean',
        'StartDate' => 'date',
        'EndDate' => 'date'
    ];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'TeamId');
    }

    public function projectAssignments(): HasMany
    {
        return $this->hasMany(ProjectAssignment::class, 'ProjectId');
    }
}