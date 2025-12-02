<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Employee;
use App\Models\LeaveType;
use App\Models\EmployeeLeaveQuota;
use App\Models\LeaveApplication;
use App\Models\Holiday;
use Carbon\Carbon;

class LeaveApplicationController extends Controller
{
    // Show home page
    public function home()
    {
        return view('home');
    }
    
    // Show all leave applications
    public function index()
    {
        try {
            $applications = LeaveApplication::with(['employee', 'leaveType'])->get();
            return view('leave-applications.index', compact('applications'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error loading leave applications: ' . $e->getMessage());
        }
    }
    
    // Show create form
    public function create()
    {
        try {
            $departments = Department::where('IsActive', true)->get();
            return view('leave-applications.create', compact('departments'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error loading form: ' . $e->getMessage());
        }
    }
    
    // Store new leave application
    public function store(Request $request)
    {
        try {
            $request->validate([
                'DepartmentId' => 'required|exists:departments,DepartmentId',
                'EmployeeId' => 'required|exists:employees,EmployeeId',
                'LeaveTypeId' => 'required|exists:leave_types,LeaveTypeId',
                'FromDate' => 'required|date|after_or_equal:today',
                'ToDate' => 'required|date|after_or_equal:FromDate',
                'Reason' => 'required|string|max:500'
            ], [
                'DepartmentId.required' => 'Please select a department',
                'EmployeeId.required' => 'Please select an employee',
                'LeaveTypeId.required' => 'Please select a leave type',
                'FromDate.required' => 'Please select from date',
                'FromDate.after_or_equal' => 'From date cannot be in the past',
                'ToDate.required' => 'Please select to date',
                'ToDate.after_or_equal' => 'To date must be after or equal to from date',
                'Reason.required' => 'Please enter reason for leave',
                'Reason.max' => 'Reason must be less than 500 characters'
            ]);
            
            // 2. Calculate total working days
            $totalDays = $this->calculateWorkingDays($request->FromDate, $request->ToDate);
            
            // 3. Check if employee is active
            $employee = Employee::find($request->EmployeeId);
            if (!$employee->IsActive) {
                return back()->with('error', 'Cannot apply leave for inactive employee')->withInput();
            }
            
            // 4. Check leave balance
            $currentYear = date('Y');
            $quota = EmployeeLeaveQuota::where('EmployeeId', $request->EmployeeId)
                ->where('LeaveTypeId', $request->LeaveTypeId)
                ->where('LeaveYear', $currentYear)
                ->first();
            
            if (!$quota) {
                return back()->with('error', 'No leave quota found for this employee')->withInput();
            }
            
            $remaining = $quota->TotalAllocated - $quota->TotalUsed;
            if ($totalDays > $remaining) {
                return back()->with('error', "Insufficient leave balance! Remaining: {$remaining} days, Requested: {$totalDays} days")->withInput();
            }
            
            // 5. Check for overlapping leave
            $hasOverlap = LeaveApplication::where('EmployeeId', $request->EmployeeId)
                ->whereIn('Status', ['Pending', 'Approved'])
                ->where('LeaveApplicationId', '!=', $request->id ?? 0)
                ->where(function($q) use ($request) {
                    $q->where(function($inner) use ($request) {
                        $inner->where('FromDate', '<=', $request->ToDate)
                              ->where('ToDate', '>=', $request->FromDate);
                    });
                })
                ->exists();
            
            if ($hasOverlap) {
                return back()->with('error', 'Leave application overlaps with existing approved/pending leave')->withInput();
            }
            
            // 6. Create leave application
            LeaveApplication::create([
                'EmployeeId' => $request->EmployeeId,
                'LeaveTypeId' => $request->LeaveTypeId,
                'FromDate' => $request->FromDate,
                'ToDate' => $request->ToDate,
                'TotalDays' => $totalDays,
                'Reason' => $request->Reason,
                'Status' => 'Pending',
                'AppliedOn' => Carbon::now()
            ]);
            
            // 7. Update used quota
            $quota->TotalUsed += $totalDays;
            $quota->save();
            
            return redirect()->route('leave.index')->with('success', 'Leave application submitted successfully!');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage())->withInput();
        }
    }
    
    // Show edit form
    public function edit($id)
    {
        try {
            $application = LeaveApplication::with(['employee.department', 'leaveType'])->findOrFail($id);
            
            // Only allow editing of Pending applications
            if ($application->Status !== 'Pending') {
                return redirect()->route('leave.index')->with('error', 'Only pending applications can be edited');
            }
            
            $departments = Department::where('IsActive', true)->get();
            
            return view('leave-applications.edit', compact('application', 'departments'));
            
        } catch (\Exception $e) {
            return redirect()->route('leave.index')->with('error', 'Application not found');
        }
    }
    
    // Update leave application
    public function update(Request $request, $id)
    {
        try {
            $application = LeaveApplication::findOrFail($id);
            
            // Only allow updating of Pending applications
            if ($application->Status !== 'Pending') {
                return redirect()->route('leave.index')->with('error', 'Only pending applications can be updated');
            }
            
            // 1. Basic validation
            $request->validate([
                'DepartmentId' => 'required|exists:departments,DepartmentId',
                'EmployeeId' => 'required|exists:employees,EmployeeId',
                'LeaveTypeId' => 'required|exists:leave_types,LeaveTypeId',
                'FromDate' => 'required|date',
                'ToDate' => 'required|date|after_or_equal:FromDate',
                'Reason' => 'required|string|max:500'
            ], [
                'DepartmentId.required' => 'Please select a department',
                'EmployeeId.required' => 'Please select an employee',
                'LeaveTypeId.required' => 'Please select a leave type',
                'FromDate.required' => 'Please select from date',
                'ToDate.required' => 'Please select to date',
                'ToDate.after_or_equal' => 'To date must be after or equal to from date',
                'Reason.required' => 'Please enter reason for leave',
                'Reason.max' => 'Reason must be less than 500 characters'
            ]);
            
            // 2. Calculate total working days
            $totalDays = $this->calculateWorkingDays($request->FromDate, $request->ToDate);
            
            // 3. Check if employee is active
            $employee = Employee::find($request->EmployeeId);
            if (!$employee->IsActive) {
                return back()->with('error', 'Cannot apply leave for inactive employee')->withInput();
            }
            
            // 4. Check leave balance
            $currentYear = date('Y');
            $quota = EmployeeLeaveQuota::where('EmployeeId', $request->EmployeeId)
                ->where('LeaveTypeId', $request->LeaveTypeId)
                ->where('LeaveYear', $currentYear)
                ->first();
            
            if (!$quota) {
                return back()->with('error', 'No leave quota found for this employee')->withInput();
            }
            
            // Subtract previous days if leave type changed
            if ($application->LeaveTypeId != $request->LeaveTypeId) {
                $oldQuota = EmployeeLeaveQuota::where('EmployeeId', $application->EmployeeId)
                    ->where('LeaveTypeId', $application->LeaveTypeId)
                    ->where('LeaveYear', $currentYear)
                    ->first();
                
                if ($oldQuota) {
                    $oldQuota->TotalUsed -= $application->TotalDays;
                    $oldQuota->save();
                }
            }
            
            $remaining = $quota->TotalAllocated - $quota->TotalUsed;
            
            // If same leave type, add back previous days
            if ($application->LeaveTypeId == $request->LeaveTypeId) {
                $remaining += $application->TotalDays;
            }
            
            if ($totalDays > $remaining) {
                return back()->with('error', "Insufficient leave balance! Available: {$remaining} days, Requested: {$totalDays} days")->withInput();
            }
            
            // 5. Check for overlapping leave (excluding current application)
            $hasOverlap = LeaveApplication::where('EmployeeId', $request->EmployeeId)
                ->whereIn('Status', ['Pending', 'Approved'])
                ->where('LeaveApplicationId', '!=', $id)
                ->where(function($q) use ($request) {
                    $q->where(function($inner) use ($request) {
                        $inner->where('FromDate', '<=', $request->ToDate)
                              ->where('ToDate', '>=', $request->FromDate);
                    });
                })
                ->exists();
            
            if ($hasOverlap) {
                return back()->with('error', 'Leave application overlaps with existing approved/pending leave')->withInput();
            }
            
            // 6. Update leave quota
            if ($application->LeaveTypeId != $request->LeaveTypeId) {
                $quota->TotalUsed += $totalDays;
                $quota->save();
            } else {
                $quota->TotalUsed = $quota->TotalUsed - $application->TotalDays + $totalDays;
                $quota->save();
            }
            
            // 7. Update application
            $application->update([
                'EmployeeId' => $request->EmployeeId,
                'LeaveTypeId' => $request->LeaveTypeId,
                'FromDate' => $request->FromDate,
                'ToDate' => $request->ToDate,
                'TotalDays' => $totalDays,
                'Reason' => $request->Reason,
            ]);
            
            return redirect()->route('leave.index')->with('success', '✅ Leave application updated successfully!');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage())->withInput();
        }
    }
    
    // Delete leave application
    public function destroy($id)
    {
        try {
            $application = LeaveApplication::findOrFail($id);
            
            // Only allow deletion of Pending applications
            if ($application->Status !== 'Pending') {
                return redirect()->route('leave.index')->with('error', 'Only pending applications can be deleted');
            }
            
            // Refund leave quota
            $currentYear = date('Y');
            $quota = EmployeeLeaveQuota::where('EmployeeId', $application->EmployeeId)
                ->where('LeaveTypeId', $application->LeaveTypeId)
                ->where('LeaveYear', $currentYear)
                ->first();
            
            if ($quota) {
                $quota->TotalUsed -= $application->TotalDays;
                $quota->save();
            }
            
            // Delete application
            $application->delete();
            
            return redirect()->route('leave.index')->with('success', '✅ Leave application deleted successfully!');
            
        } catch (\Exception $e) {
            return redirect()->route('leave.index')->with('error', 'Error deleting application: ' . $e->getMessage());
        }
    }
    
    // Calculate working days (excluding weekends and holidays)
    private function calculateWorkingDays($fromDate, $toDate)
    {
        $start = Carbon::parse($fromDate);
        $end = Carbon::parse($toDate);
        $totalDays = 0;
        
        for ($date = $start; $date->lte($end); $date->addDay()) {
            // Skip weekends
            if (!$date->isWeekend()) {
                // Check if it's a holiday
                $isHoliday = Holiday::whereDate('HolidayDate', $date->format('Y-m-d'))->exists();
                if (!$isHoliday) {
                    $totalDays += 1;
                }
            }
        }
        
        return $totalDays;
    }
    
    public function getEmployees($departmentId)
    {
        try {
            $employees = Employee::where('DepartmentId', $departmentId)
                ->where('IsActive', true)
                ->get(['EmployeeId', 'FirstName', 'LastName', 'EmployeeCode']);
            
            return response()->json($employees);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error fetching employees'], 500);
        }
    }
    
    public function getLeaveTypes($employeeId)
    {
        try {
            $currentYear = date('Y');
            
            $leaveTypes = EmployeeLeaveQuota::with('leaveType')
                ->where('EmployeeId', $employeeId)
                ->where('LeaveYear', $currentYear)
                ->whereRaw('(TotalAllocated - TotalUsed) > 0')
                ->get()
                ->map(function($quota) {
                    return [
                        'LeaveTypeId' => $quota->leaveType->LeaveTypeId,
                        'LeaveTypeName' => $quota->leaveType->LeaveTypeName,
                    ];
                });
            
            return response()->json($leaveTypes);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error fetching leave types'], 500);
        }
    }
    
    public function calculateDays(Request $request)
    {
        try {
            $request->validate([
                'fromDate' => 'required|date',
                'toDate' => 'required|date|after_or_equal:fromDate'
            ]);
            
            $totalDays = $this->calculateWorkingDays($request->fromDate, $request->toDate);
            return response()->json(['totalDays' => $totalDays]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error calculating days'], 500);
        }
    }
    
    // Get employee details for edit form
    public function getEmployeeDetails($employeeId)
    {
        try {
            $employee = Employee::with('department')->findOrFail($employeeId);
            return response()->json([
                'EmployeeId' => $employee->EmployeeId,
                'FirstName' => $employee->FirstName,
                'LastName' => $employee->LastName,
                'DepartmentId' => $employee->DepartmentId,
                'DepartmentName' => $employee->department->DepartmentName
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error fetching employee details'], 500);
        }
    }
}