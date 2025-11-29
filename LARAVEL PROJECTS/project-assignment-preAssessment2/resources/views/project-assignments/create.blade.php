@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4>Create Project Assignment</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('project-assignments.store') }}" method="POST">
                    @csrf
                    
                    <!-- Cascading Dropdowns -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="DepartmentId" class="form-label">Department *</label>
                            <select name="DepartmentId" id="DepartmentId" class="form-select" required>
                                <option value="">Select Department</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->DepartmentId }}">{{ $department->DepartmentName }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-4">
                            <label for="TeamId" class="form-label">Team *</label>
                            <select name="TeamId" id="TeamId" class="form-select" disabled required>
                                <option value="">Select Team</option>
                            </select>
                        </div>
                        
                        <div class="col-md-4">
                            <label for="ProjectId" class="form-label">Project *</label>
                            <select name="ProjectId" id="ProjectId" class="form-select" disabled required>
                                <option value="">Select Project</option>
                            </select>
                            <div id="projectMessage" class="form-text text-danger"></div>
                        </div>
                    </div>

                    <!-- Other Form Fields -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="EmployeeId" class="form-label">Employee *</label>
                            <select name="EmployeeId" id="EmployeeId" class="form-select" required>
                                <option value="">Select Employee</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->EmployeeId }}">{{ $employee->FullName }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="RoleOnProject" class="form-label">Role *</label>
                            <select name="RoleOnProject" id="RoleOnProject" class="form-select" required>
                                <option value="">Select Role</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role }}">{{ $role }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="AllocationPercent" class="form-label">Allocation % *</label>
                            <input type="number" name="AllocationPercent" id="AllocationPercent" 
                                   class="form-control" step="0.01" min="0.01" max="100" required>
                        </div>
                        
                        <div class="col-md-4">
                            <label for="StartDate" class="form-label">Start Date *</label>
                            <input type="date" name="StartDate" id="StartDate" class="form-control" required>
                        </div>
                        
                        <div class="col-md-4">
                            <label for="EndDate" class="form-label">End Date *</label>
                            <input type="date" name="EndDate" id="EndDate" class="form-control" required>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Create Assignment</button>
                        <a href="{{ route('project-assignments.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Department change event
    $('#DepartmentId').change(function() {
        var departmentId = $(this).val();
        $('#TeamId').prop('disabled', true).html('<option value="">Select Team</option>');
        $('#ProjectId').prop('disabled', true).html('<option value="">Select Project</option>');
        $('#projectMessage').text('');

        if (departmentId) {
            $.get('/api/teams/' + departmentId, function(data) {
                $('#TeamId').prop('disabled', false);
                $.each(data, function(key, team) {
                    $('#TeamId').append('<option value="' + team.TeamId + '">' + team.TeamName + '</option>');
                });
            });
        }
    });

    // Team change event
    $('#TeamId').change(function() {
        var teamId = $(this).val();
        $('#ProjectId').prop('disabled', true).html('<option value="">Select Project</option>');
        $('#projectMessage').text('');

        if (teamId) {
            $.get('/api/projects/' + teamId, function(data) {
                if (data.eligible) {
                    $('#ProjectId').prop('disabled', false);
                    $.each(data.projects, function(key, project) {
                        $('#ProjectId').append('<option value="' + project.ProjectId + '">' + project.ProjectName + '</option>');
                    });
                } else {
                    $('#projectMessage').text(data.message);
                }
            });
        }
    });

    // Date validation
    $('#StartDate, #EndDate').change(function() {
        var startDate = new Date($('#StartDate').val());
        var endDate = new Date($('#EndDate').val());
        
        if (endDate < startDate) {
            alert('End date must be after or equal to start date');
            $('#EndDate').val('');
        }
    });
});
</script>
@endpush