<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Carbon\Carbon;

class LeaveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = Leave::with('employee');
            
            if ($request->has('start_date') && !empty($request->start_date)) {
                $startDate = Carbon::parse($request->start_date)->startOfDay();
                $query->where('start_date', '>=', $startDate);
            }
            
            if ($request->has('end_date') && !empty($request->end_date)) {
                $endDate = Carbon::parse($request->end_date)->endOfDay();
                $query->where('end_date', '<=', $endDate);
            }
            
            if ($request->has('approved') && $request->approved !== null) {
                $query->where('approved', filter_var($request->approved, FILTER_VALIDATE_BOOLEAN));
            }
            
            $leaves = $query->get();
            
            if ($leaves->isEmpty()) {
                return response()->json([], Response::HTTP_NO_CONTENT);
            }
            
            return response()->json($leaves, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to retrieve leaves',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $employeeId)
    {
        try {
            $employee = Employee::find($employeeId);
            if (!$employee) {
                return response()->json([
                    'error' => 'Employee not found'
                ], Response::HTTP_NOT_FOUND);
            }
            
            $validated = $request->validate([
                'description' => 'required|string',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'approved' => 'sometimes|boolean'
            ]);
            
            $validated['employee_id'] = $employeeId;
            
            $leave = Leave::create($validated);
            
            return response()->json($leave, Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to create leave',
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
            $leave = Leave::with('employee')->find($id);
            
            if (!$leave) {
                return response()->json([
                    'error' => 'Leave not found'
                ], Response::HTTP_NOT_FOUND);
            }
            
            return response()->json($leave, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to retrieve leave',
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
            $leave = Leave::find($id);
            
            if (!$leave) {
                return response()->json([
                    'error' => 'Leave not found'
                ], Response::HTTP_NOT_FOUND);
            }
            
            $validated = $request->validate([
                'description' => 'sometimes|string',
                'start_date' => 'sometimes|date',
                'end_date' => 'sometimes|date|after_or_equal:start_date',
                'approved' => 'sometimes|boolean'
            ]);
            
            $leave->update($validated);
            
            return response()->json($leave, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to update leave',
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
            $leave = Leave::find($id);
            
            if (!$leave) {
                return response()->json([
                    'error' => 'Leave not found'
                ], Response::HTTP_NOT_FOUND);
            }
            
            $leave->delete();
            
            return response()->json([], Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to delete leave',
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
            Leave::truncate();
            
            return response()->json([], Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to delete all leaves',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}