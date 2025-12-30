<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Project;

class AssignmentController extends Controller
{
    /**
     * Assign employee to project
     * NOTE: Middleware ensures only admin/manager can access
     */
    public function assignEmployeeToProject(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'project_id' => 'required|exists:projects,id'
        ]);

        try {
            $employee = Employee::find($request->employee_id);
            $project = Project::find($request->project_id);

            // Check if already assigned
            $existingAssignment = $employee->projects()
                ->where('project_id', $request->project_id)
                ->exists();

            if ($existingAssignment) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Employee is already assigned to this project'
                ], 400);
            }

            // Assign with current date
            $employee->projects()->attach($request->project_id, [
                'assigned_date' => now()->format('Y-m-d')
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Employee assigned to project successfully',
                'data' => [
                    'employee_id' => $employee->id,
                    'employee_name' => $employee->name,
                    'project_id' => $project->id,
                    'project_name' => $project->project_name,
                    'assigned_date' => now()->format('Y-m-d')
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to assign employee to project',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * View assigned projects
     * NOTE: This needs to handle different access:
     * - Admin/Manager: Can view all assignments or specific employee
     * - Employee: Can only view their OWN assignments
     */
    public function viewAssignedProjects(Request $request, $employeeId = null)
    {
        try {
            $authUser = $request->input('auth_user');
            $role = $authUser['role'];
            
            // EMPLOYEE: Can only view their own assignments
            if ($role === 'employee') {
                // Get employee record for the authenticated user
                $employee = Employee::where('email', $authUser['username'])->first();
                
                if (!$employee) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Employee record not found'
                    ], 404);
                }
                
                // Employee can only view their own assignments
                // Ignore any provided employeeId parameter
                return response()->json([
                    'status' => 'success',
                    'employee' => [
                        'id' => $employee->id,
                        'name' => $employee->name,
                        'email' => $employee->email
                    ],
                    'assigned_projects' => $employee->projects
                ]);
            }
            
            // ADMIN/MANAGER: Can view all or specific employee
            if ($employeeId) {
                // Get projects for specific employee
                $employee = Employee::with('projects')->find($employeeId);
                
                if (!$employee) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Employee not found'
                    ], 404);
                }

                return response()->json([
                    'status' => 'success',
                    'employee' => [
                        'id' => $employee->id,
                        'name' => $employee->name,
                        'email' => $employee->email
                    ],
                    'assigned_projects' => $employee->projects
                ]);
            } else {
                // Get all assignments
                $assignments = Employee::with('projects')->get();
                
                return response()->json([
                    'status' => 'success',
                    'data' => $assignments
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch assigned projects',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove employee from project
     * NOTE: Middleware ensures only manager can access
     */
    public function removeEmployeeFromProject(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'project_id' => 'required|exists:projects,id'
        ]);

        try {
            $employee = Employee::find($request->employee_id);
            
            $employee->projects()->detach($request->project_id);

            return response()->json([
                'status' => 'success',
                'message' => 'Employee removed from project successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to remove employee from project',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}