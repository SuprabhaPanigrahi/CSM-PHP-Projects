<?php

namespace App\Http\Controllers;

use App\Models\ProjectAssignment;
use App\Models\Department;
use App\Models\Team;
use App\Models\Project;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class ProjectAssignmentController extends Controller
{
    public function index()
    {
        try {
            $assignments = ProjectAssignment::with(['project.team.department', 'employee'])
                ->orderBy('AssignmentId', 'asc')
                ->paginate(10);
                
            return view('project-assignments.index', compact('assignments'));
        } catch (\Exception $e) {
            Log::error('Error fetching project assignments: ' . $e->getMessage());
            return redirect()->route('project-assignments.index')
                ->with('error', 'Error loading project assignments. Please try again.');
        }
    }

    public function create()
    {
        try {
            $departments = Department::where('Status', 'Active')->get();
            $employees = Employee::where('IsActive', true)->get();
            $roles = ['Developer', 'Designer', 'Manager', 'Tester', 'Analyst', 'Architect', 'Lead'];
            
            return view('project-assignments.create', compact('departments', 'employees', 'roles'));
        } catch (\Exception $e) {
            Log::error('Error loading create form: ' . $e->getMessage());
            return redirect()->route('project-assignments.index')
                ->with('error', 'Error loading form. Please try again.');
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'ProjectId' => 'required|exists:projects,ProjectId',
                'EmployeeId' => 'required|exists:employees,EmployeeId',
                'RoleOnProject' => 'required|string|max:100',
                'AllocationPercent' => 'required|numeric|min:0.01|max:100',
                'StartDate' => 'required|date',
                'EndDate' => 'required|date|after_or_equal:StartDate',
            ]);

            // Additional validation: Check if project is active and billable
            $project = Project::find($validated['ProjectId']);
            if (!$project || $project->Status !== 'Active' || !$project->IsBillable) {
                throw ValidationException::withMessages([
                    'ProjectId' => 'Selected project is not eligible for assignment.',
                ]);
            }

            // Check for overlapping assignments
            $existingAssignment = ProjectAssignment::where('EmployeeId', $validated['EmployeeId'])
                ->where('Status', 'Active')
                ->where(function($query) use ($validated) {
                    $query->whereBetween('StartDate', [$validated['StartDate'], $validated['EndDate']])
                          ->orWhereBetween('EndDate', [$validated['StartDate'], $validated['EndDate']])
                          ->orWhere(function($q) use ($validated) {
                              $q->where('StartDate', '<=', $validated['StartDate'])
                                ->where('EndDate', '>=', $validated['EndDate']);
                          });
                })
                ->exists();

            if ($existingAssignment) {
                throw ValidationException::withMessages([
                    'EmployeeId' => 'Employee already has an assignment during this period.',
                ]);
            }

            DB::transaction(function () use ($validated) {
                ProjectAssignment::create($validated);
            });

            return redirect()->route('project-assignments.index')
                ->with('success', 'Project assignment created successfully.');

        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            Log::error('Error creating project assignment: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error creating project assignment. Please try again.')
                ->withInput();
        }
    }

    public function edit($id)
    {
        try {
            $projectAssignment = ProjectAssignment::with(['project.team.department', 'employee'])
                ->findOrFail($id);
                
            $departments = Department::where('Status', 'Active')->get();
            $employees = Employee::where('IsActive', true)->get();
            $roles = ['Developer', 'Designer', 'Manager', 'Tester', 'Analyst', 'Architect', 'Lead'];
            
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
        } catch (\Exception $e) {
            Log::error('Error loading edit form: ' . $e->getMessage());
            return redirect()->route('project-assignments.index')
                ->with('error', 'Error loading assignment for editing. Please try again.');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $projectAssignment = ProjectAssignment::findOrFail($id);

            $validated = $request->validate([
                'ProjectId' => 'required|exists:projects,ProjectId',
                'EmployeeId' => 'required|exists:employees,EmployeeId',
                'RoleOnProject' => 'required|string|max:100',
                'AllocationPercent' => 'required|numeric|min:0.01|max:100',
                'StartDate' => 'required|date',
                'EndDate' => 'required|date|after_or_equal:StartDate',
            ]);

            // Additional validation: Check if project is active and billable
            $project = Project::find($validated['ProjectId']);
            if (!$project || $project->Status !== 'Active' || !$project->IsBillable) {
                throw ValidationException::withMessages([
                    'ProjectId' => 'Selected project is not eligible for assignment.',
                ]);
            }

            // Check for overlapping assignments (excluding current assignment)
            $existingAssignment = ProjectAssignment::where('EmployeeId', $validated['EmployeeId'])
                ->where('Status', 'Active')
                ->where('AssignmentId', '!=', $id)
                ->where(function($query) use ($validated) {
                    $query->whereBetween('StartDate', [$validated['StartDate'], $validated['EndDate']])
                          ->orWhereBetween('EndDate', [$validated['StartDate'], $validated['EndDate']])
                          ->orWhere(function($q) use ($validated) {
                              $q->where('StartDate', '<=', $validated['StartDate'])
                                ->where('EndDate', '>=', $validated['EndDate']);
                          });
                })
                ->exists();

            if ($existingAssignment) {
                throw ValidationException::withMessages([
                    'EmployeeId' => 'Employee already has another assignment during this period.',
                ]);
            }

            DB::transaction(function () use ($projectAssignment, $validated) {
                $projectAssignment->update($validated);
            });

            return redirect()->route('project-assignments.index')
                ->with('success', 'Project assignment updated successfully.');

        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            Log::error('Error updating project assignment: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error updating project assignment. Please try again.')
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $projectAssignment = ProjectAssignment::findOrFail($id);

            DB::transaction(function () use ($projectAssignment) {
                $projectAssignment->delete();
            });

            return redirect()->route('project-assignments.index')
                ->with('success', 'Project assignment deleted successfully.');

        } catch (\Exception $e) {
            Log::error('Error deleting project assignment: ' . $e->getMessage());
            return redirect()->route('project-assignments.index')
                ->with('error', 'Error deleting project assignment. Please try again.');
        }
    }
}