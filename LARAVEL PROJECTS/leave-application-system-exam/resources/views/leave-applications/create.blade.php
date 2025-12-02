@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>Apply for Leave</h4>
    </div>
    <div class="card-body">
        <form id="leaveForm" method="POST" action="{{ route('leave.store') }}">
            @csrf
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Department <span class="text-danger">*</span></label>
                    <select name="DepartmentId" id="DepartmentId" class="form-control" required>
                        <option value="">Select Department</option>
                        @foreach($departments as $dept)
                        <option value="{{ $dept->DepartmentId }}">{{ $dept->DepartmentName }}</option>
                        @endforeach
                    </select>
                    <small class="text-muted">Select department first</small>
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">Employee <span class="text-danger">*</span></label>
                    <select name="EmployeeId" id="EmployeeId" class="form-control" required disabled>
                        <option value="">Select Employee</option>
                    </select>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Leave Type <span class="text-danger">*</span></label>
                    <select name="LeaveTypeId" id="LeaveTypeId" class="form-control" required disabled>
                        <option value="">Select Leave Type</option>
                    </select>
                </div>
                
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">From Date <span class="text-danger">*</span></label>
                            <input type="date" name="FromDate" id="FromDate" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">To Date <span class="text-danger">*</span></label>
                            <input type="date" name="ToDate" id="ToDate" class="form-control" required>
                        </div>
                    </div>
                    <div id="totalDaysContainer" class="mt-2" style="display:none;">
                        <strong>Total Working Days Leave: <span id="totalDays">0</span></strong>
                    </div>
                </div>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Reason for Leave <span class="text-danger">*</span></label>
                <textarea name="Reason" id="Reason" class="form-control" rows="3" required></textarea>
            </div>
            
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">Submit Application</button>
            </div>
        </form>
    </div>
</div>
@endsection