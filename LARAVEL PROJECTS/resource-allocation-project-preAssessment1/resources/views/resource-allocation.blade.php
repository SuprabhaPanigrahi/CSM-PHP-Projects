@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <h2>Project Resource Allocation</h2>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <a href="{{ route('allocations.list') }}" class="btn btn-info me-2">
                    <i class="fas fa-list me-1"></i>View Allocations
                </a>
                <a href="{{ route('project.create') }}" class="btn btn-success">
                    <i class="fas fa-plus me-1"></i>Create Project
                </a>
            </div>
        </div>
        <div class="card mt-4">
            <div class="card-header">
                <h5>Allocation Form</h5>
            </div>
            <div class="card-body">
                <form id="allocationForm">
                    @csrf

                    <!-- Hidden project_id for testing -->
                    <input type="hidden" name="project_id" value="1">

                    <!-- Project Details -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Project Technology *</label>
                            <select class="form-control" id="technology_id" name="technology_id" required>
                                <option value="">Select Technology</option>
                                @foreach($technologies as $technology)
                                <option value="{{ $technology->TechnologyId }}">{{ $technology->Name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Project Type *</label>
                            <select class="form-control" id="project_type" name="project_type" required>
                                <option value="">Select Type</option>
                                <option value="Billable">Billable</option>
                                <option value="Non-Billable">Non-Billable</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Priority *</label>
                            <select class="form-control" id="priority" name="priority" required>
                                <option value="">Select Priority</option>
                                <option value="Normal">Normal</option>
                                <option value="High">High</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Location Type *</label>
                            <select class="form-control" id="location_type" name="location_type" required>
                                <option value="">Select Location</option>
                                <option value="Onsite">Onsite</option>
                                <option value="Offshore">Offshore</option>
                                <option value="Hybrid">Hybrid</option>
                            </select>
                        </div>
                    </div>

                    <!-- Cascading Dropdowns -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label class="form-label">Business Unit *</label>
                            <select class="form-control" id="business_unit_id" name="business_unit_id" required>
                                <option value="">Select Business Unit</option>
                                @foreach($businessUnits as $unit)
                                <option value="{{ $unit->BusinessUnitId }}">{{ $unit->Name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Department *</label>
                            <select class="form-control" id="department_id" name="department_id" required disabled>
                                <option value="">Select Department</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Team *</label>
                            <select class="form-control" id="team_id" name="team_id" required disabled>
                                <option value="">Select Team</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Employee *</label>
                            <select class="form-control" id="employee_id" name="employee_id" required disabled>
                                <option value="">Select Employee</option>
                            </select>
                        </div>
                    </div>

                    <!-- Allocation Details -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Start Date *</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">End Date</label>
                            <input type="date" class="form-control" id="end_date" name="end_date">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Allocation Percentage *</label>
                            <input type="number" class="form-control" id="allocation_percentage" name="allocation_percentage" value="100" min="1" max="100" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Allocate Employee</button>
                </form>
            </div>
        </div>

        <!-- Results Section -->
        <div id="results" class="mt-4" style="display: none;">
            <div class="card">
                <div class="card-header">
                    <h5>Available Employees</h5>
                </div>
                <div class="card-body">
                    <div id="employeeList"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Cascading dropdown functionality
        $('#business_unit_id').change(function() {
            var businessUnitId = $(this).val();

            // Reset downstream dropdowns
            $('#department_id').prop('disabled', true).html('<option value="">Select Department</option>');
            $('#team_id').prop('disabled', true).html('<option value="">Select Team</option>');
            $('#employee_id').prop('disabled', true).html('<option value="">Select Employee</option>');
            $('#results').hide();

            if (businessUnitId) {
                $.get('/departments/' + businessUnitId, function(data) {
                    $('#department_id').prop('disabled', false);
                    $('#department_id').html('<option value="">Select Department</option>');
                    $.each(data, function(index, department) {
                        $('#department_id').append('<option value="' + department.DepartmentId + '">' + department.Name + '</option>');
                    });
                }).fail(function(xhr, status, error) {
                    console.error('Error loading departments:', error);
                    alert('Error loading departments. Please try again.');
                });
            }
        });

        $('#department_id').change(function() {
            var departmentId = $(this).val();

            // Reset downstream dropdowns
            $('#team_id').prop('disabled', true).html('<option value="">Select Team</option>');
            $('#employee_id').prop('disabled', true).html('<option value="">Select Employee</option>');
            $('#results').hide();

            if (departmentId) {
                $.get('/teams/' + departmentId, function(data) {
                    $('#team_id').prop('disabled', false);
                    $('#team_id').html('<option value="">Select Team</option>');
                    $.each(data, function(index, team) {
                        $('#team_id').append('<option value="' + team.TeamId + '">' + team.Name + '</option>');
                    });
                }).fail(function(xhr, status, error) {
                    console.error('Error loading teams:', error);
                    alert('Error loading teams. Please try again.');
                });
            }
        });

        $('#team_id, #technology_id, #project_type, #priority, #location_type').change(function() {
            loadEmployees();
        });

        function loadEmployees() {
            var teamId = $('#team_id').val();
            var technologyId = $('#technology_id').val();
            var projectType = $('#project_type').val();
            var priority = $('#priority').val();
            var locationType = $('#location_type').val();

            if (teamId && technologyId && projectType && priority && locationType) {
                $.get('/employees', {
                    team_id: teamId,
                    technology_id: technologyId,
                    project_type: projectType,
                    priority: priority,
                    location_type: locationType
                }, function(data) {
                    $('#employee_id').prop('disabled', false);
                    $('#employee_id').html('<option value="">Select Employee</option>');

                    if (data.length > 0) {
                        $.each(data, function(index, employee) {
                            $('#employee_id').append('<option value="' + employee.EmployeeId + '">' + employee.FullName + ' (' + employee.YearsOfExperience + ' years)</option>');
                        });
                        $('#results').show();
                        displayEmployeeList(data);
                    } else {
                        $('#employee_id').html('<option value="">No employees found</option>');
                        $('#results').hide();
                    }
                }).fail(function(xhr, status, error) {
                    console.error('Error loading employees:', error);
                    alert('Error loading employees. Please try again.');
                });
            }
        }

        function displayEmployeeList(employees) {
            var html = '<div class="row">';
            $.each(employees, function(index, employee) {
                html += '<div class="col-md-4 mb-3">';
                html += '<div class="card">';
                html += '<div class="card-body">';
                html += '<h6>' + employee.FullName + '</h6>';
                html += '<p class="mb-1">Experience: ' + employee.YearsOfExperience + ' years</p>';
                html += '<p class="mb-1">Employee Code: ' + employee.EmployeeCode + '</p>';
                html += '</div>';
                html += '</div>';
                html += '</div>';
            });
            html += '</div>';
            $('#employeeList').html(html);
        }

        // Form submission
        $('#allocationForm').submit(function(e) {
            e.preventDefault();

            // Get form data including CSRF token
            var formData = $(this).serialize();

            $.ajax({
                url: '/allocate',
                method: 'POST',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    alert(response.message);
                    if (response.success) {
                        $('#allocationForm')[0].reset();
                        $('#results').hide();
                        // Reset all dropdowns
                        $('select').prop('disabled', false);
                        $('#department_id, #team_id, #employee_id').prop('disabled', true).html('<option value="">Select...</option>');
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        // Validation errors
                        var errors = xhr.responseJSON.errors;
                        var errorMessage = 'Validation failed:\n';
                        for (var field in errors) {
                            errorMessage += errors[field][0] + '\n';
                        }
                        alert(errorMessage);
                    } else {
                        var error = xhr.responseJSON;
                        alert(error?.message || 'An error occurred. Please check console for details.');
                        console.error('AJAX Error:', xhr.responseText);
                    }
                }
            });
        });
    });
</script>
@endsection