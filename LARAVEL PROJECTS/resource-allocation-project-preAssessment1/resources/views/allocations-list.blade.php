@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Allocated Employees</h2>
            <div>
                <!-- <a href="{{ route('project.create') }}" class="btn btn-success me-2">
                    Create New Project
                </a> -->
                <a href="/" class="btn btn-primary">
                    Back to Allocation
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Current Allocations</h5>
            </div>
            <div class="card-body">
                @if($allocations->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Employee</th>
                                    <th>Employee Code</th>
                                    <th>Project</th>
                                    <th>Project Code</th>
                                    <th>Technology</th>
                                    <th>Project Type</th>
                                    <th>Priority</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Allocation %</th>
                                    <th>Business Unit</th>
                                    <th>Department</th>
                                    <th>Team</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($allocations as $allocation)
                                <tr>
                                    <td>
                                        <strong>{{ $allocation->employee->FullName }}</strong>
                                    </td>
                                    <td>{{ $allocation->employee->EmployeeCode }}</td>
                                    <td>
                                        <strong>{{ $allocation->project->ProjectName }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $allocation->project->ProjectCode }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $allocation->project->technology->Name }}</span>
                                    </td>
                                    <td>
                                        @if($allocation->project->ProjectType === 'Billable')
                                            <span class="badge bg-success">Billable</span>
                                        @else
                                            <span class="badge bg-warning">Non-Billable</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($allocation->project->Priority === 'High')
                                            <span class="badge bg-danger">High</span>
                                        @else
                                            <span class="badge bg-primary">Normal</span>
                                        @endif
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($allocation->AllocationStartDate)->format('M d, Y') }}</td>
                                    <td>
                                        @if($allocation->AllocationEndDate)
                                            {{ \Carbon\Carbon::parse($allocation->AllocationEndDate)->format('M d, Y') }}
                                        @else
                                            <span class="text-muted">Not set</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-dark">{{ $allocation->AllocationPercentage }}%</span>
                                    </td>
                                    <td>{{ $allocation->employee->team->department->businessUnit->Name }}</td>
                                    <td>{{ $allocation->employee->team->department->Name }}</td>
                                    <td>{{ $allocation->employee->team->Name }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        <p class="text-muted">
                            Total Allocations: <strong>{{ $allocations->count() }}</strong>
                        </p>
                    </div>
                @else
                    <div class="text-center py-5">
                        <div class="mb-3">
                            <i class="fas fa-users fa-3x text-muted"></i>
                        </div>
                        <h4 class="text-muted">No Allocations Found</h4>
                        <p class="text-muted">There are no active employee allocations at the moment.</p>
                        <a href="/" class="btn btn-primary">Allocate Employees</a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Statistics Card -->
        @if($allocations->count() > 0)
        <div class="row mt-4">
            <div class="col-md-3">
                <div class="card text-white bg-primary">
                    <div class="card-body">
                        <h5 class="card-title">Total Allocations</h5>
                        <h2 class="card-text">{{ $allocations->count() }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <h5 class="card-title">Billable Projects</h5>
                        <h2 class="card-text">
                            {{ $allocations->where('project.ProjectType', 'Billable')->count() }}
                        </h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-danger">
                    <div class="card-body">
                        <h5 class="card-title">High Priority</h5>
                        <h2 class="card-text">
                            {{ $allocations->where('project.Priority', 'High')->count() }}
                        </h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-info">
                    <div class="card-body">
                        <h5 class="card-title">Unique Employees</h5>
                        <h2 class="card-text">
                            {{ $allocations->unique('EmployeeId')->count() }}
                        </h2>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@section('styles')
<style>
.table th {
    background-color: #343a40;
    color: white;
}
.badge {
    font-size: 0.75em;
}
.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}
</style>
@endsection