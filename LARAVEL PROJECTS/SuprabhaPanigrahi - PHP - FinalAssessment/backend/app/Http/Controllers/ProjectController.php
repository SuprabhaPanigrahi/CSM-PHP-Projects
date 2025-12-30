<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ProjectService;

class ProjectController extends Controller
{
    protected $projectService;

    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
    }

    public function index()
    {
        try {
            $projects = $this->projectService->getAllProjects();

            return response()->json([
                'status' => 'success',
                'data' => $projects
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch projects',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function show($id)
    {
        try {
            $project = $this->projectService->getProjectById($id);

            if (!$project) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Project not found'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'data' => $project
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch project',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'project_name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date'
        ]);

        try {
            $authUser = $request->input('auth_user');

            $project = $this->projectService->createProject([
                'project_name' => $request->project_name,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'created_by' => $authUser['id']
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Project created successfully',
                'data' => $project
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create project',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'project_name' => 'sometimes|string|max:255',
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date|after:start_date'
        ]);

        try {
            $project = $this->projectService->updateProject($id, $request->all());

            if (!$project) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Project not found'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Project updated successfully',
                'data' => $project
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update project',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function destroy($id)
    {
        try {
            $result = $this->projectService->deleteProject($id);

            if (!$result) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Project not found'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Project deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete project',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get projects assigned to employee
     * NOTE: Employee can only view their own projects
     */
    public function getEmployeeProjects(Request $request, $employeeId)
    {
        try {
            $authUser = $request->input('auth_user');
            $role = $authUser['role'];

            // If user is employee, they can only access their own employee ID
            if ($role === 'employee') {
                // Find employee record for this user
                $employee = Employee::where('email', $authUser['username'])->first();

                if (!$employee) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Employee record not found'
                    ], 404);
                }

                // Check if requested employee ID matches the authenticated employee's ID
                if ($employee->id != $employeeId) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'You can only view your own projects'
                    ], 403);
                }
            }

            // Get projects
            $projects = $this->projectService->getProjectsByEmployee($employeeId);

            return response()->json([
                'status' => 'success',
                'data' => $projects
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch employee projects',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
