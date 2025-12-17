<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Leave;
use App\Models\Employee;
use Carbon\Carbon;

class LeaveController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Leave::with('employee');
            
            // Apply filters
            if ($request->has('start_date')) {
                $startDate = Carbon::parse($request->input('start_date'));
                $query->where('start_date', '>=', $startDate);
            }
            
            if ($request->has('end_date')) {
                $endDate = Carbon::parse($request->input('end_date'));
                $query->where('end_date', '<=', $endDate);
            }
            
            if ($request->has('approved') && $request->input('approved') !== '') {
                $approved = filter_var($request->input('approved'), FILTER_VALIDATE_BOOLEAN);
                $query->where('approved', $approved);
            }
            
            $leaves = $query->get();
            
            if ($leaves->isEmpty()) {
                return response()->json([], 204);
            }
            
            return response()->json($leaves, 200);
            
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
            $leave = Leave::with('employee')->find($id);
            
            if (!$leave) {
                return response()->json([
                    'message' => 'Leave not found'
                ], 404);
            }
            
            return response()->json($leave, 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Server Error',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    public function store(Request $request, $employeeId)
    {
        try {
            $employee = Employee::find($employeeId);
            
            if (!$employee) {
                return response()->json([
                    'message' => 'Employee not found'
                ], 404);
            }
            
            $validated = $request->validate([
                'description' => 'required|string',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'approved' => 'boolean'
            ]);
            
            $validated['employee_id'] = $employeeId;
            
            $leave = Leave::create($validated);
            
            return response()->json($leave, 201);
            
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
            $leave = Leave::find($id);
            
            if (!$leave) {
                return response()->json([
                    'message' => 'Leave not found'
                ], 404);
            }
            
            $validated = $request->validate([
                'description' => 'sometimes|string',
                'start_date' => 'sometimes|date',
                'end_date' => 'sometimes|date|after_or_equal:start_date',
                'approved' => 'sometimes|boolean'
            ]);
            
            $leave->update($validated);
            
            return response()->json($leave, 200);
            
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
            $leave = Leave::find($id);
            
            if (!$leave) {
                return response()->json([], 204);
            }
            
            $leave->delete();
            
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
            Leave::truncate();
            
            return response()->json([], 204);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Server Error',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}