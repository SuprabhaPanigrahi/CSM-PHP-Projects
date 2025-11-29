<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $primaryKey = 'ProjectId';
    protected $fillable = ['ProjectCode', 'ProjectName', 'TeamId', 'IsBillable', 'Status', 'StartDate', 'EndDate'];

    public function team()
    {
        return $this->belongsTo(Team::class, 'TeamId');
    }

    public function projectAssignments()
    {
        return $this->hasMany(ProjectAssignment::class, 'ProjectId');
    }
}