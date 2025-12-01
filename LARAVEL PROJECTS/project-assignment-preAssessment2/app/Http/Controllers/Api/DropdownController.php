<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Team;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DropdownController extends Controller
{
    public function getTeams($departmentId)
    {
        try {
            if (!is_numeric($departmentId)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid department ID',
                    'teams' => []
                ], 400);
            }

            $teams = Team::where('DepartmentId', $departmentId)
                ->where('Status', 'Active')
                ->get(['TeamId', 'TeamName']);
                
            return response()->json([
                'success' => true,
                'teams' => $teams
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error fetching teams: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error loading teams',
                'teams' => []
            ], 500);
        }
    }

    public function getProjects($teamId)
    {
        try {
            if (!is_numeric($teamId)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid team ID',
                    'eligible' => false,
                    'projects' => []
                ], 400);
            }

            $team = Team::with(['projects' => function($query) {
                $query->where('IsBillable', true)
                      ->where('Status', 'Active')
                      ->select(['ProjectId', 'ProjectName', 'TeamId']);
            }])->find($teamId);

            if (!$team) {
                return response()->json([
                    'success' => false,
                    'eligible' => false,
                    'message' => 'Team not found',
                    'projects' => []
                ], 404);
            }

            if ($team->Status !== 'Active') {
                return response()->json([
                    'success' => true,
                    'eligible' => false,
                    'message' => 'Selected team is not active.',
                    'projects' => []
                ]);
            }

            $projects = $team->projects;
            
            if ($projects->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'eligible' => false,
                    'message' => 'No billable projects available for the selected team.',
                    'projects' => []
                ]);
            }

            return response()->json([
                'success' => true,
                'eligible' => true,
                'projects' => $projects
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error fetching projects: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'eligible' => false,
                'message' => 'Error loading projects',
                'projects' => []
            ], 500);
        }
    }
}