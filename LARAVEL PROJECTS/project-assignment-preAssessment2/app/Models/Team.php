<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Log;

class Team extends Model
{
    use HasFactory;

    protected $primaryKey = 'TeamId';
    protected $fillable = ['TeamCode', 'TeamName', 'DepartmentId', 'Status'];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'DepartmentId');
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class, 'TeamId');
    }
    
    public function activeProjects(): HasMany
    {
        return $this->projects()->where('Status', 'Active');
    }
    
    public function hasEligibleProjects(): bool
    {
        try {
            return $this->Status === 'Active' && 
                   $this->projects()
                       ->where('IsBillable', true)
                       ->where('Status', 'Active')
                       ->exists();
        } catch (\Exception $e) {
            \Log::error('Error checking eligible projects for team: ' . $e->getMessage());
            return false;
        }
    }
}