@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Project Assignments</h1>
    <a href="{{ route('project-assignments.create') }}" class="btn btn-primary">Create New Assignment</a>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">All Assignments</h5>
    </div>
    <div class="card-body">
        @if($assignments->isEmpty())
            <div class="alert alert-info text-center">
                No project assignments found. <a href="{{ route('project-assignments.create') }}">Create the first one!</a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Department</th>
                            <th>Team</th>
                            <th>Project</th>
                            <th>Employee</th>
                            <th>Role</th>
                            <th>Allocation %</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($assignments as $assignment)
                        <tr>
                            <td>{{ $assignment->AssignmentId }}</td>
                            <td>{{ $assignment->project->team->department->DepartmentName ?? 'N/A' }}</td>
                            <td>{{ $assignment->project->team->TeamName ?? 'N/A' }}</td>
                            <td>{{ $assignment->project->ProjectName ?? 'N/A' }}</td>
                            <td>{{ $assignment->employee->FullName ?? 'N/A' }}</td>
                            <td>{{ $assignment->RoleOnProject }}</td>
                            <td>{{ number_format($assignment->AllocationPercent, 2) }}%</td>
                            <td>{{ $assignment->StartDate->format('M d, Y') }}</td>
                            <td>{{ $assignment->EndDate->format('M d, Y') }}</td>
                            <td>
                                <span class="badge bg-{{ $assignment->Status === 'Active' ? 'success' : 'secondary' }}">
                                    {{ $assignment->Status }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('project-assignments.edit', $assignment->AssignmentId) }}" 
                                       class="btn btn-warning" title="Edit">
                                        Edit
                                    </a>
                                    <form action="{{ route('project-assignments.destroy', $assignment->AssignmentId) }}" 
                                          method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" 
                                                onclick="return confirm('Are you sure you want to delete this assignment?')"
                                                title="Delete">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-4">
                {{ $assignments->links() }}
            </div>
        @endif
    </div>
</div>
@endsection