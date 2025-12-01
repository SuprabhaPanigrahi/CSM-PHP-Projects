@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Create New Project Assignment</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('project-assignments.store') }}" method="POST" id="assignmentForm">
                    @csrf
                    
                    <!-- Cascading Dropdowns -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label for="DepartmentId" class="form-label">Department *</label>
                            <select name="DepartmentId" id="DepartmentId" class="form-select" required>
                                <option value="">Select Department</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->DepartmentId }}" 
                                        {{ old('DepartmentId') == $department->DepartmentId ? 'selected' : '' }}>
                                        {{ $department->DepartmentName }}
                                    </option>
                                @endforeach
                            </select>
                            @error('DepartmentId')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4">
                            <label for="TeamId" class="form-label">Team *</label>
                            <select name="TeamId" id="TeamId" class="form-select" disabled required>
                                <option value="">Select Team</option>
                            </select>
                            <div class="form-text">Select a department first</div>
                            @error('TeamId')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4">
                            <label for="ProjectId" class="form-label">Project *</label>
                            <select name="ProjectId" id="ProjectId" class="form-select" disabled required>
                                <option value="">Select Project</option>
                            </select>
                            <div id="projectMessage" class="form-text"></div>
                            @error('ProjectId')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Other Form Fields -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="EmployeeId" class="form-label">Employee *</label>
                            <select name="EmployeeId" id="EmployeeId" class="form-select" required>
                                <option value="">Select Employee</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->EmployeeId }}" 
                                        {{ old('EmployeeId') == $employee->EmployeeId ? 'selected' : '' }}>
                                        {{ $employee->FullName }} ({{ $employee->EmployeeCode }})
                                    </option>
                                @endforeach
                            </select>
                            @error('EmployeeId')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="RoleOnProject" class="form-label">Role *</label>
                            <select name="RoleOnProject" id="RoleOnProject" class="form-select" required>
                                <option value="">Select Role</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role }}" 
                                        {{ old('RoleOnProject') == $role ? 'selected' : '' }}>
                                        {{ $role }}
                                    </option>
                                @endforeach
                            </select>
                            @error('RoleOnProject')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label for="AllocationPercent" class="form-label">Allocation % *</label>
                            <input type="number" name="AllocationPercent" id="AllocationPercent" 
                                   class="form-control" step="0.01" min="0.01" max="100" 
                                   value="{{ old('AllocationPercent') }}" 
                                   placeholder="e.g., 80.50" required>
                            <div class="form-text">Enter percentage (0.01 to 100)</div>
                            @error('AllocationPercent')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4">
                            <label for="StartDate" class="form-label">Start Date *</label>
                            <input type="date" name="StartDate" id="StartDate" class="form-control" 
                                   value="{{ old('StartDate') }}" required>
                            @error('StartDate')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4">
                            <label for="EndDate" class="form-label">End Date *</label>
                            <input type="date" name="EndDate" id="EndDate" class="form-control" 
                                   value="{{ old('EndDate') }}" required>
                            @error('EndDate')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('project-assignments.index') }}" class="btn btn-secondary me-md-2">Cancel</a>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            Create Assignment
                        </button>
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
    let isLoading = false;

    function showLoading(element) {
        element.addClass('loading');
        isLoading = true;
        $('#submitBtn').prop('disabled', true).text('Loading...');
    }

    function hideLoading(element) {
        element.removeClass('loading');
        isLoading = false;
        $('#submitBtn').prop('disabled', false).text('Create Assignment');
    }

    function showError(element, message) {
        element.addClass('error-border');
        element.next('.form-text').addClass('text-danger').text(message);
    }

    function clearError(element) {
        element.removeClass('error-border');
        element.next('.form-text').removeClass('text-danger').text('');
    }

    // Department change event
    $('#DepartmentId').change(function() {
        if (isLoading) return;

        var departmentId = $(this).val();
        var $teamSelect = $('#TeamId');
        var $projectSelect = $('#ProjectId');
        
        $teamSelect.prop('disabled', true).html('<option value="">Select Team</option>');
        $projectSelect.prop('disabled', true).html('<option value="">Select Project</option>');
        $('#projectMessage').text('').removeClass('text-danger text-success');

        if (departmentId) {
            showLoading($teamSelect);
            
            $.ajax({
                url: '/api/teams/' + departmentId,
                method: 'GET',
                success: function(response) {
                    hideLoading($teamSelect);
                    if (response.success && response.teams.length > 0) {
                        $teamSelect.prop('disabled', false);
                        $.each(response.teams, function(key, team) {
                            $teamSelect.append('<option value="' + team.TeamId + '">' + team.TeamName + '</option>');
                        });
                    } else {
                        showError($teamSelect, 'No active teams available for this department.');
                    }
                },
                error: function(xhr, status, error) {
                    hideLoading($teamSelect);
                    showError($teamSelect, 'Error loading teams. Please try again.');
                    console.error('Error loading teams:', error);
                }
            });
        }
    });

    // Team change event
    $('#TeamId').change(function() {
        if (isLoading) return;

        var teamId = $(this).val();
        var $projectSelect = $('#ProjectId');
        
        $projectSelect.prop('disabled', true).html('<option value="">Select Project</option>');
        $('#projectMessage').text('').removeClass('text-danger text-success');

        if (teamId) {
            showLoading($projectSelect);
            
            $.ajax({
                url: '/api/projects/' + teamId,
                method: 'GET',
                success: function(response) {
                    hideLoading($projectSelect);
                    if (response.success) {
                        if (response.eligible && response.projects.length > 0) {
                            $projectSelect.prop('disabled', false);
                            $.each(response.projects, function(key, project) {
                                $projectSelect.append('<option value="' + project.ProjectId + '">' + project.ProjectName + '</option>');
                            });
                            $('#projectMessage').addClass('text-success').text(response.projects.length + ' eligible project(s) found.');
                        } else {
                            $('#projectMessage').addClass('text-danger').text(response.message);
                        }
                    } else {
                        $('#projectMessage').addClass('text-danger').text(response.message || 'Error loading projects.');
                    }
                },
                error: function(xhr, status, error) {
                    hideLoading($projectSelect);
                    $('#projectMessage').addClass('text-danger').text('Error loading projects. Please try again.');
                    console.error('Error loading projects:', error);
                }
            });
        }
    });

    // Date validation
    $('#StartDate, #EndDate').change(function() {
        var startDate = new Date($('#StartDate').val());
        var endDate = new Date($('#EndDate').val());
        
        if ($('#StartDate').val() && $('#EndDate').val() && endDate < startDate) {
            alert('End date must be after or equal to start date');
            $('#EndDate').val('');
        }
    });

    // Form submission validation
    $('#assignmentForm').submit(function(e) {
        var projectId = $('#ProjectId').val();
        if (!projectId) {
            alert('Please select a valid project before submitting.');
            e.preventDefault();
            return false;
        }
        return true;
    });

    // Clear errors on change
    $('select, input').change(function() {
        clearError($(this));
    });
});
</script>
@endpush