@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>Leave Applications</h4>
    </div>
    <div class="card-body">
        
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif
        
        @if($applications->count() == 0)
        <div class="alert alert-info">
            No leave applications found. 
            <a href="{{ route('leave.create') }}" class="alert-link">Create your first application</a>
        </div>
        @else
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Employee</th>
                        <th>Leave Type</th>
                        <th>From Date</th>
                        <th>To Date</th>
                        <th>Days</th>
                        <th>Status</th>
                        <th>Applied On</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($applications as $app)
                    <tr>
                        <td>{{ $app->LeaveApplicationId }}</td>
                        <td>{{ $app->employee->FirstName }} {{ $app->employee->LastName }}</td>
                        <td>{{ $app->leaveType->LeaveTypeName }}</td>
                        <td>{{ date('d-m-Y', strtotime($app->FromDate)) }}</td>
                        <td>{{ date('d-m-Y', strtotime($app->ToDate)) }}</td>
                        <td>{{ $app->TotalDays }}</td>
                        <td>
                            @if($app->Status == 'Approved')
                            <span class="badge bg-success">Approved</span>
                            @elseif($app->Status == 'Rejected')
                            <span class="badge bg-danger">Rejected</span>
                            @else
                            <span class="badge bg-warning">Pending</span>
                            @endif
                        </td>
                        <td>{{ date('d-m-Y', strtotime($app->AppliedOn)) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>
@endsection