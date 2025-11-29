@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4>Edit Project Assignment</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('project-assignments.update', $projectAssignment->AssignmentId) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <!-- Cascading Dropdowns -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="DepartmentId" class="form-label">Department *</label>
                            <select name="DepartmentId" id="DepartmentId" class="form-select" required>
                                <option value="">Select Department</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->DepartmentId }}" 
                                        {{ $currentDepartment->DepartmentId == $department->DepartmentId ? 'selected' : '' }}>
                                        {{ $department->DepartmentName }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-4">
                            <label for="TeamId" class="form-label">Team *</label>
                            <select name="TeamId" id="TeamId" class="form-select" required>
                                <option value="">Select Team</option>
                            </select>
                        </div>
                        
                        <div class="col-md-4">
                            <label for="ProjectId" class="form-label">Project *</label>
                            <select name="ProjectId" id="ProjectId" class="form-select" required>
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
                                    <option value="{{ $employee->EmployeeId }}" 
                                        {{ $projectAssignment->EmployeeId == $employee->EmployeeId ? 'selected' : '' }}>
                                        {{ $employee->FullName }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="RoleOnProject" class="form-label">Role *</label>
                            <select name="RoleOnProject" id="RoleOnProject" class="form-select" required>
                                <option value="">Select Role</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role }}" 
                                        {{ $projectAssignment->RoleOnProject == $role ? 'selected' : '' }}>
                                        {{ $role }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="AllocationPercent" class="form-label">Allocation % *</label>
                            <input type="number" name="AllocationPercent" id="AllocationPercent" 
                                   class="form-control" step="0.01" min="0.01" max="100" 
                                   value="{{ $projectAssignment->AllocationPercent }}" required>
                        </div>
                        
                        <div class="col-md-4">
                            <label for="StartDate" class="form-label">Start Date *</label>
                            <input type="date" name="StartDate" id="StartDate" class="form-control" 
                                   value="{{ $projectAssignment->StartDate->format('Y-m-d') }}" required>
                        </div>
                        
                        <div class="col-md-4">
                            <label for="EndDate" class="form-label">End Date *</label>
                            <input type="date" name="EndDate" id="EndDate" class="form-control" 
                                   value="{{ $projectAssignment->EndDate->format('Y-m-d') }}" required>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Update Assignment</button>
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
    var currentDepartmentId = {{ $currentDepartment->DepartmentId }};
    var currentTeamId = {{ $currentTeam->TeamId }};
    var currentProjectId = {{ $currentProject->ProjectId }};

    // Load teams for current department
    function loadTeams(departmentId, teamId = null) {
        if (departmentId) {
            $.get('/api/teams/' + departmentId, function(data) {
                $('#TeamId').html('<option value="">Select Team</option>');
                $.each(data, function(key, team) {
                    var selected = teamId && team.TeamId == teamId ? 'selected' : '';
                    $('#TeamId').append('<option value="' + team.TeamId + '" ' + selected + '>' + team.TeamName + '</option>');
                });
                
                if (teamId) {
                    loadProjects(teamId, currentProjectId);
                }
            });
        }
    }

    // Load projects for current team
    function loadProjects(teamId, projectId = null) {
        if (teamId) {
            $.get('/api/projects/' + teamId, function(data) {
                $('#ProjectId').html('<option value="">Select Project</option>');
                if (data.eligible) {
                    $.each(data.projects, function(key, project) {
                        var selected = projectId && project.ProjectId == projectId ? 'selected' : '';
                        $('#ProjectId').append('<option value="' + project.ProjectId + '" ' + selected + '>' + project.ProjectName + '</option>');
                    });
                    $('#projectMessage').text('');
                } else {
                    $('#projectMessage').text(data.message);
                }
            });
        }
    }

    // Initial load
    loadTeams(currentDepartmentId, currentTeamId);

    // Department change event
    $('#DepartmentId').change(function() {
        var departmentId = $(this).val();
        $('#TeamId').prop('disabled', true).html('<option value="">Select Team</option>');
        $('#ProjectId').prop('disabled', true).html('<option value="">Select Project</option>');
        $('#projectMessage').text('');

        if (departmentId) {
            loadTeams(departmentId);
        }
    });

    // Team change event
    $('#TeamId').change(function() {
        var teamId = $(this).val();
        $('#ProjectId').prop('disabled', true).html('<option value="">Select Project</option>');
        $('#projectMessage').text('');

        if (teamId) {
            loadProjects(teamId);
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