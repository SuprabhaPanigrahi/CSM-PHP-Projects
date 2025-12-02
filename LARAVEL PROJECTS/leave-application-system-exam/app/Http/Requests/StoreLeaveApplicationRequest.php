<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\EmployeeLeaveQuota;
use App\Models\LeaveApplication;
use App\Models\Holiday;
use Carbon\Carbon;

class StoreLeaveApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    
    public function rules(): array
    {
        return [
            'DepartmentId' => 'required|exists:departments,DepartmentId',
            'EmployeeId' => 'required|exists:employees,EmployeeId',
            'LeaveTypeId' => 'required|exists:leave_types,LeaveTypeId',
            'FromDate' => 'required|date|after_or_equal:today',
            'ToDate' => 'required|date|after_or_equal:FromDate',
            'Reason' => 'required|string|max:500',
        ];
    }
    
    public function messages(): array
    {
        return [
            'DepartmentId.required' => 'Please select a department',
            'EmployeeId.required' => 'Please select an employee',
            'LeaveTypeId.required' => 'Please select a leave type',
            'FromDate.required' => 'Please select from date',
            'FromDate.after_or_equal' => 'From date cannot be in the past',
            'ToDate.required' => 'Please select to date',
            'ToDate.after_or_equal' => 'To date must be after or equal to from date',
            'Reason.required' => 'Please enter reason for leave',
        ];
    }
    
    // Calculate working days (simplified)
    private function calculateWorkingDays($fromDate, $toDate): float
    {
        $start = Carbon::parse($fromDate);
        $end = Carbon::parse($toDate);
        $totalDays = 0;
        
        for ($date = $start; $date->lte($end); $date->addDay()) {
            if (!$date->isWeekend()) {
                $isHoliday = Holiday::whereDate('HolidayDate', $date->format('Y-m-d'))->exists();
                if (!$isHoliday) {
                    $totalDays += 1;
                }
            }
        }
        
        return $totalDays;
    }
    
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (!$this->has('EmployeeId') || !$this->has('LeaveTypeId') || !$this->has('FromDate') || !$this->has('ToDate')) {
                return;
            }
            
            try {
                // Calculate working days
                $totalDays = $this->calculateWorkingDays($this->FromDate, $this->ToDate);
                
                // Check leave balance
                $currentYear = Carbon::now()->year;
                $quota = EmployeeLeaveQuota::where('EmployeeId', $this->EmployeeId)
                    ->where('LeaveTypeId', $this->LeaveTypeId)
                    ->where('LeaveYear', $currentYear)
                    ->first();
                
                if (!$quota) {
                    $validator->errors()->add('leave_balance', 'No leave quota found for this employee');
                } else {
                    $remaining = $quota->TotalAllocated - $quota->TotalUsed;
                    if ($totalDays > $remaining) {
                        $validator->errors()->add('leave_balance', "Insufficient leave balance. Remaining: {$remaining} days, Requested: {$totalDays} days");
                    }
                }
                
                // Check overlapping leave
                $hasOverlap = LeaveApplication::where('EmployeeId', $this->EmployeeId)
                    ->whereIn('Status', ['Pending', 'Approved'])
                    ->where(function($q) {
                        $q->where(function($inner) {
                            $inner->where('FromDate', '<=', $this->ToDate)
                                  ->where('ToDate', '>=', $this->FromDate);
                        });
                    })
                    ->exists();
                
                if ($hasOverlap) {
                    $validator->errors()->add('overlapping', 'Leave application overlaps with existing approved/pending leave');
                }
                
            } catch (\Exception $e) {
                $validator->errors()->add('system_error', 'An error occurred while processing your request');
            }
        });
    }
}