<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = Employee::with('leaves');
            
            if ($request->has('department') && !empty($request->department)) {
                $query->where('department', 'like', '%' . $request->department . '%');
            }
            
            $employees = $query->get();
            
            if ($employees->isEmpty()) {
                return response()->json([], Response::HTTP_NO_CONTENT);
            }
            
            return response()->json($employees, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to retrieve employees',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'firstname' => 'required|string|max:255',
                'lastname' => 'required|string|max:255',
                'department' => 'required|string|max:255',
                'date_of_birth' => 'required|date'
            ]);
            
            $employee = Employee::create($validated);
            
            return response()->json($employee, Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to create employee',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $employee = Employee::with('leaves')->find($id);
            
            if (!$employee) {
                return response()->json([
                    'error' => 'Employee not found'
                ], Response::HTTP_NOT_FOUND);
            }
            
            return response()->json($employee, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to retrieve employee',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $employee = Employee::find($id);
            
            if (!$employee) {
                return response()->json([
                    'error' => 'Employee not found'
                ], Response::HTTP_NOT_FOUND);
            }
            
            $validated = $request->validate([
                'firstname' => 'sometimes|string|max:255',
                'lastname' => 'sometimes|string|max:255',
                'department' => 'sometimes|string|max:255',
                'date_of_birth' => 'sometimes|date'
            ]);
            
            $employee->update($validated);
            
            return response()->json($employee, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to update employee',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $employee = Employee::find($id);
            
            if (!$employee) {
                return response()->json([
                    'error' => 'Employee not found'
                ], Response::HTTP_NOT_FOUND);
            }
            
            $employee->delete();
            
            return response()->json([], Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to delete employee',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove all resources from storage.
     */
    public function destroyAll()
    {
        try {
            Employee::truncate();
            
            return response()->json([], Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to delete all employees',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}