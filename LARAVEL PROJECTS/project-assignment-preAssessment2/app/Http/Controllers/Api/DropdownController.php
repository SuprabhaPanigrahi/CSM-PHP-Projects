<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Team;
use App\Models\Project;
use Illuminate\Http\Request;

class DropdownController extends Controller
{
    public function getTeams($departmentId)
    {
        $teams = Team::where('DepartmentId', $departmentId)
            ->where('Status', 'Active')
            ->get(['TeamId', 'TeamName']);
            
        return response()->json($teams);
    }

    public function getProjects($teamId)
    {
        $team = Team::with(['projects' => function($query) {
            $query->where('IsBillable', true)
                  ->where('Status', 'Active');
        }])->find($teamId);

        if (!$team || $team->Status !== 'Active') {
            return response()->json([
                'eligible' => false,
                'message' => 'No eligible projects available for the selected team.',
                'projects' => []
            ]);
        }

        $projects = $team->projects;
        
        if ($projects->isEmpty()) {
            return response()->json([
                'eligible' => false,
                'message' => 'No eligible projects available for the selected team.',
                'projects' => []
            ]);
        }

        return response()->json([
            'eligible' => true,
            'projects' => $projects
        ]);
    }
}