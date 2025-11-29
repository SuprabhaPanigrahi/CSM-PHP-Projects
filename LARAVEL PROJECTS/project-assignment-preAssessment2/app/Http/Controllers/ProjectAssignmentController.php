<?php

namespace App\Http\Controllers;

use App\Models\ProjectAssignment;
use App\Models\Department;
use App\Models\Team;
use App\Models\Project;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectAssignmentController extends Controller
{
    public function index()
    {
        $assignments = ProjectAssignment::with(['project.team.department', 'employee'])
            ->paginate(3);
            
        return view('project-assignments.index', compact('assignments'));
    }

    public function create()
    {
        $departments = Department::where('Status', 'Active')->get();
        $employees = Employee::where('IsActive', true)->get();
        $roles = ['Developer', 'Designer', 'Manager', 'Tester', 'Analyst'];
        
        return view('project-assignments.create', compact('departments', 'employees', 'roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ProjectId' => 'required|exists:projects,ProjectId',
            'EmployeeId' => 'required|exists:employees,EmployeeId',
            'RoleOnProject' => 'required|string|max:100',
            'AllocationPercent' => 'required|numeric|min:0.01|max:100',
            'StartDate' => 'required|date',
            'EndDate' => 'required|date|after_or_equal:StartDate',
        ]);

        DB::transaction(function () use ($validated) {
            ProjectAssignment::create($validated);
        });

        return redirect()->route('project-assignments.index')
            ->with('success', 'Project assignment created successfully.');
    }

    public function edit(ProjectAssignment $projectAssignment)
    {
        $departments = Department::where('Status', 'Active')->get();
        $employees = Employee::where('IsActive', true)->get();
        $roles = ['Developer', 'Designer', 'Manager', 'Tester', 'Analyst'];
        
        // Get current assignment data for pre-population
        $currentProject = $projectAssignment->project;
        $currentTeam = $currentProject->team;
        $currentDepartment = $currentTeam->department;
        
        return view('project-assignments.edit', compact(
            'projectAssignment', 
            'departments', 
            'employees', 
            'roles',
            'currentDepartment',
            'currentTeam',
            'currentProject'
        ));
    }

    public function update(Request $request, ProjectAssignment $projectAssignment)
    {
        $validated = $request->validate([
            'ProjectId' => 'required|exists:projects,ProjectId',
            'EmployeeId' => 'required|exists:employees,EmployeeId',
            'RoleOnProject' => 'required|string|max:100',
            'AllocationPercent' => 'required|numeric|min:0.01|max:100',
            'StartDate' => 'required|date',
            'EndDate' => 'required|date|after_or_equal:StartDate',
        ]);

        DB::transaction(function () use ($projectAssignment, $validated) {
            $projectAssignment->update($validated);
        });

        return redirect()->route('project-assignments.index')
            ->with('success', 'Project assignment updated successfully.');
    }

    public function destroy(ProjectAssignment $projectAssignment)
    {
        DB::transaction(function () use ($projectAssignment) {
            $projectAssignment->delete();
        });

        return redirect()->route('project-assignments.index')
            ->with('success', 'Project assignment deleted successfully.');
    }
}