<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Employee::with('leaves');
            
            if ($request->has('department')) {
                $department = $request->input('department');
                $query->where('department', 'like', "%{$department}%");
            }
            
            $employees = $query->get();
            
            if ($employees->isEmpty()) {
                return response()->json([], 204);
            }
            
            return response()->json($employees, 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Server Error',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    public function show($id)
    {
        try {
            $employee = Employee::with('leaves')->find($id);
            
            if (!$employee) {
                return response()->json([
                    'message' => 'Employee not found'
                ], 404);
            }
            
            return response()->json($employee, 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Server Error',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
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
            
            return response()->json($employee, 201);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Server Error',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    public function update(Request $request, $id)
    {
        try {
            $employee = Employee::find($id);
            
            if (!$employee) {
                return response()->json([
                    'message' => 'Employee not found'
                ], 404);
            }
            
            $validated = $request->validate([
                'firstname' => 'sometimes|string|max:255',
                'lastname' => 'sometimes|string|max:255',
                'department' => 'sometimes|string|max:255',
                'date_of_birth' => 'sometimes|date'
            ]);
            
            $employee->update($validated);
            
            return response()->json($employee, 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Server Error',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    public function destroy($id)
    {
        try {
            $employee = Employee::find($id);
            
            if (!$employee) {
                return response()->json([], 204);
            }
            
            $employee->delete();
            
            return response()->json([], 204);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Server Error',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    public function destroyAll()
    {
        try {
            Employee::truncate();
            
            return response()->json([], 204);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Server Error',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}