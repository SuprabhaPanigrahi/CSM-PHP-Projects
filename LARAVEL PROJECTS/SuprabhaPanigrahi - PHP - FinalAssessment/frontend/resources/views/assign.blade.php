@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Assign Project to Employee</h4>
            </div>
            <div class="card-body">
                <form id="assignForm">
                    <div class="mb-3">
                        <label for="employeeSelect" class="form-label">Select Employee</label>
                        <select class="form-control" id="employeeSelect" required>
                            <option value="">Select Employee</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="projectSelect" class="form-label">Select Project</label>
                        <select class="form-control" id="projectSelect" required>
                            <option value="">Select Project</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Assign Project</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        const role = getUserRole();
        if (role !== 'admin' && role !== 'manager') {
            alert("Only admins and managers can assign projects.");
            window.location.href = "/dashboard";
            return;
        }
        
        loadEmployees();
        loadProjects();
        
        $("#assignForm").submit(function(e) {
            e.preventDefault();
            assignProject();
        });
    });
    
    function loadEmployees() {
        $("#loader").show();
        $.ajax({
            url: API_BASE_URL + "/employees",
            method: "GET",
            success: function(response) {
                $("#loader").hide();
                if (response.status === 'success') {
                    const select = $("#employeeSelect");
                    select.empty();
                    select.append('<option value="">Select Employee</option>');
                    
                    response.data.forEach(employee => {
                        select.append(`<option value="${employee.id}">${employee.name} (${employee.email})</option>`);
                    });
                }
            },
            error: function(error) {
                $("#loader").hide();
                alert("Failed to load employees");
            }
        });
    }
    
    function loadProjects() {
        $("#loader").show();
        $.ajax({
            url: API_BASE_URL + "/projects",
            method: "GET",
            success: function(response) {
                $("#loader").hide();
                if (response.status === 'success') {
                    const select = $("#projectSelect");
                    select.empty();
                    select.append('<option value="">Select Project</option>');
                    
                    response.data.forEach(project => {
                        select.append(`<option value="${project.id}">${project.project_name}</option>`);
                    });
                }
            },
            error: function(error) {
                $("#loader").hide();
                alert("Failed to load projects");
            }
        });
    }
    
    function assignProject() {
        const employeeId = $("#employeeSelect").val();
        const projectId = $("#projectSelect").val();
        
        if (!employeeId || !projectId) {
            alert("Please select both employee and project");
            return;
        }
        
        $("#loader").show();
        $.ajax({
            url: API_BASE_URL + "/assignments/assign",
            method: "POST",
            data: {
                employee_id: employeeId,
                project_id: projectId
            },
            success: function(response) {
                $("#loader").hide();
                if (response.status === 'success') {
                    alert("Project assigned successfully!");
                    $("#assignForm")[0].reset();
                }
            },
            error: function(error) {
                $("#loader").hide();
                if (error.responseJSON && error.responseJSON.message) {
                    alert(error.responseJSON.message);
                } else {
                    alert("Failed to assign project");
                }
            }
        });
    }
</script>
@endpush
@endsection