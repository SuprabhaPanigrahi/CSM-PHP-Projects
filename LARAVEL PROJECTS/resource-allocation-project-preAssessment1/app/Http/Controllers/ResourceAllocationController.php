<?php

namespace App\Http\Controllers;

use App\Models\BusinessUnit;
use App\Models\Department;
use App\Models\Team;
use App\Models\Employee;
use App\Models\Technology;
use App\Models\Project;
use App\Models\EmployeeProjectAllocation;
use Illuminate\Http\Request;

class ResourceAllocationController extends Controller
{
    public function index()
    {
        $businessUnits = BusinessUnit::where('IsActive', true)->get();
        $technologies = Technology::all();

        return view('resource-allocation', compact('businessUnits', 'technologies'));
    }

    public function getDepartments($businessUnitId)
    {
        $departments = Department::where('BusinessUnitId', $businessUnitId)
            ->where('IsActive', true)
            ->get();

        return response()->json($departments);
    }

    public function getTeams($departmentId)
    {
        $teams = Team::where('DepartmentId', $departmentId)
            ->where('IsActive', true)
            ->get();

        return response()->json($teams);
    }

    public function getEmployees(Request $request)
    {
        $teamId = $request->team_id;
        $technologyId = $request->technology_id;
        $projectType = $request->project_type;
        $priority = $request->priority;
        $locationType = $request->location_type;
        $locationCountryId = $request->location_country_id;

        // Base query for employees in the selected team
        $query = Employee::with(['skills', 'allocations'])
            ->where('TeamId', $teamId)
            ->where('IsActive', true)
            ->whereHas('status', function ($q) {
                $q->where('StatusName', 'Available');
            });

        // Filter by primary skill matching project technology
        if ($technologyId) {
            $query->whereHas('skills', function ($q) use ($technologyId) {
                $q->where('TechnologyId', $technologyId)
                    ->where('IsPrimarySkill', true);
            });
        }

        // Filter by experience for billable projects
        if ($projectType === 'Billable') {
            $query->where('YearsOfExperience', '>=', 2);
        }

        // Filter by location preference for onsite projects
        if ($locationType === 'Onsite' && $locationCountryId) {
            $query->where('WorkLocationCountryId', $locationCountryId);
        }

        $employees = $query->get();

        // Additional filtering for high priority projects (PHP side for simplicity)
        if ($priority === 'High') {
            $employees = $employees->filter(function ($employee) {
                // Count active allocations (simplified logic)
                $activeAllocations = $employee->allocations()
                    ->where('IsActive', true)
                    ->count();
                return $activeAllocations <= 1;
            });
        }

        return response()->json($employees);
    }

    public function allocations()
    {
        $allocations = EmployeeProjectAllocation::with([
            'employee.team.department.businessUnit',
            'project.technology',
            'project.locationCountry'
        ])
            ->where('IsActive', true)
            ->orderBy('AllocationStartDate', 'desc')
            ->get();

        return view('allocations-list', compact('allocations'));
    }

    public function createProject()
    {
        $technologies = Technology::all();
        $countries = Country::all();

        return view('create-project', compact('technologies', 'countries'));
    }

    public function storeProject(Request $request)
    {
        $request->validate([
            'ProjectCode' => 'required|unique:projects,ProjectCode',
            'ProjectName' => 'required',
            'ProjectType' => 'required|in:Billable,Non-Billable',
            'Priority' => 'required|in:Normal,High',
            'LocationType' => 'required|in:Onsite,Offshore,Hybrid',
            'TechnologyId' => 'required|exists:technologies,TechnologyId',
            'StartDate' => 'required|date',
        ]);

        Project::create([
            'ProjectCode' => $request->ProjectCode,
            'ProjectName' => $request->ProjectName,
            'ProjectType' => $request->ProjectType,
            'Priority' => $request->Priority,
            'LocationType' => $request->LocationType,
            'LocationCountryId' => $request->LocationCountryId,
            'TechnologyId' => $request->TechnologyId,
            'StartDate' => $request->StartDate,
            'EndDate' => $request->EndDate,
            'IsActive' => true,
        ]);

        return redirect()->route('allocations.list')->with('success', 'Project created successfully!');
    }
    public function allocateEmployee(Request $request)
    {
        try {
            // Simple validation for testing
            $request->validate([
                'employee_id' => 'required|exists:employees,EmployeeId',
                'start_date' => 'required|date',
                'allocation_percentage' => 'required|integer|min:1|max:100',
            ]);

            // Use first project for testing
            $project = Project::first();

            if (!$project) {
                // Create a test project if none exists
                $project = Project::create([
                    'ProjectCode' => 'TEST001',
                    'ProjectName' => 'Test Project',
                    'ProjectType' => 'Billable',
                    'Priority' => 'Normal',
                    'LocationType' => 'Offshore',
                    'TechnologyId' => 1,
                    'StartDate' => '2024-01-01',
                    'EndDate' => '2024-12-31',
                    'IsActive' => true,
                ]);
            }

            // Check for overlapping allocations
            $overlappingAllocation = EmployeeProjectAllocation::where('EmployeeId', $request->employee_id)
                ->where('IsActive', true)
                ->where(function ($query) use ($request) {
                    $query->whereBetween('AllocationStartDate', [$request->start_date, $request->end_date ?? $request->start_date])
                        ->orWhereBetween('AllocationEndDate', [$request->start_date, $request->end_date ?? $request->start_date])
                        ->orWhere(function ($q) use ($request) {
                            $q->where('AllocationStartDate', '<=', $request->start_date)
                                ->where('AllocationEndDate', '>=', $request->end_date ?? $request->start_date);
                        });
                })
                ->exists();

            if ($overlappingAllocation) {
                return response()->json([
                    'success' => false,
                    'message' => 'Employee already has an allocation during this period.'
                ], 422);
            }

            // Create allocation
            $allocation = EmployeeProjectAllocation::create([
                'EmployeeId' => $request->employee_id,
                'ProjectId' => $project->ProjectId,
                'AllocationStartDate' => $request->start_date,
                'AllocationEndDate' => $request->end_date,
                'AllocationPercentage' => $request->allocation_percentage,
                'IsActive' => true,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Employee allocated successfully!',
                'allocation' => $allocation
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 422);
        }
    }

    // Temporary debug method
    public function debugAllocate(Request $request)
    {
        return response()->json([
            'received_data' => $request->all(),
            'errors' => 'None - this is just for debugging'
        ]);
    }
}
