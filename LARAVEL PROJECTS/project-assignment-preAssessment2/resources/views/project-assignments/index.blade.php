@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Project Assignments</h1>
    <a href="{{ route('project-assignments.create') }}" class="btn btn-primary">Create Assignment</a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Department</th>
                        <th>Team</th>
                        <th>Project</th>
                        <th>Employee</th>
                        <th>Allocation %</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($assignments as $assignment)
                    <tr>
                        <td>{{ $assignment->AssignmentId }}</td>
                        <td>{{ $assignment->project->team->department->DepartmentName }}</td>
                        <td>{{ $assignment->project->team->TeamName }}</td>
                        <td>{{ $assignment->project->ProjectName }}</td>
                        <td>{{ $assignment->employee->FullName }}</td>
                        <td>{{ $assignment->AllocationPercent }}%</td>
                        <td>{{ $assignment->StartDate->format('M d, Y') }}</td>
                        <td>{{ $assignment->EndDate->format('M d, Y') }}</td>
                        <td>
                            <a href="{{ route('project-assignments.edit', $assignment->AssignmentId) }}" 
                               class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('project-assignments.destroy', $assignment->AssignmentId) }}" 
                                  method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" 
                                        onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-center">
            {{ $assignments->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection