@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Dashboard</h4>
            </div>
            <div class="card-body">
                <h5>Welcome, <span id="userName"></span>!</h5>
                <p>Role: <span id="userRole" class="badge bg-primary"></span></p>
                
                <div class="mt-4" id="dashboardContent">
                    <!-- Content will be loaded based on role -->
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        if (!isLoggedIn()) {
            window.location.href = "/login";
            return;
        }
        
        const username = localStorage.getItem("username");
        const userRole = localStorage.getItem("userRole");
        
        $("#userName").text(username || 'User');
        $("#userRole").text(userRole || 'Guest');
        
        loadDashboardContent(userRole);
        verifyToken();
    });
    
    function loadDashboardContent(role) {
        const contentDiv = $("#dashboardContent");
        contentDiv.empty();
        
        if (role === 'admin') {
            contentDiv.html(`
                <div class="alert alert-success">
                    <h6>Admin Privileges:</h6>
                    <ul>
                        <li>View all employees</li>
                        <li>View all projects</li>
                        <li>Assign projects to employees</li>
                        <li>View all assignments</li>
                    </ul>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <button class="btn btn-success w-100 mb-2" onclick="window.location.href='/employees'">View Employees</button>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-success w-100 mb-2" onclick="window.location.href='/projects'">View Projects</button>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-success w-100 mb-2" onclick="window.location.href='/assign'">Assign Project</button>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-success w-100 mb-2" onclick="window.location.href='/assignments'">View Assignments</button>
                    </div>
                </div>
            `);
        } else if (role === 'manager') {
            contentDiv.html(`
                <div class="alert alert-info">
                    <h6>Manager Privileges:</h6>
                    <ul>
                        <li>Assign projects to employees</li>
                        <li>View all assignments</li>
                    </ul>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <button class="btn btn-info w-100 mb-2" onclick="window.location.href='/assign'">Assign Project to Employees</button>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-info w-100 mb-2" onclick="window.location.href='/assignments'">View Assignments</button>
                    </div>
                </div>
            `);
        } else if (role === 'employee') {
            contentDiv.html(`
                <div class="alert alert-warning">
                    <h6>Employee Access:</h6>
                    <ul>
                        <li>View assigned projects</li>
                        <li>View your assignments</li>
                    </ul>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <button class="btn btn-warning w-100 mb-2" onclick="loadMyAssignments()">View My Assignments</button>
                    </div>
                </div>
                <div class="mt-3" id="employeeData">
                    <!-- Employee projects/assignments will be loaded here -->
                </div>
            `);
        }
    }
    
    function verifyToken() {
        $.ajax({
            url: API_BASE_URL + "/auth/me",
            method: "GET",
            success: function(response) {
                console.log("Token is valid");
            },
            error: function(error) {
                if (error.status === 401) {
                    alert("Session expired. Please login again.");
                    logout();
                }
            }
        });
    }
    
    function loadMyProjects() {
        const role = getUserRole();
        
        if (role !== 'employee') {
            alert("This feature is only available for employees");
            return;
        }
        
        $("#loader").show();
        
        // Get employee ID
        $.ajax({
            url: API_BASE_URL + "/auth/my-employee-id",
            method: "GET",
            success: function(response) {
                if (response.status === 'success') {
                    const employeeId = response.employee_id;
                    
                    // Get projects for this employee
                    $.ajax({
                        url: API_BASE_URL + "/my-projects/" + employeeId,
                        method: "GET",
                        success: function(projectResponse) {
                            $("#loader").hide();
                            if (projectResponse.status === 'success') {
                                const projects = projectResponse.data;
                                const employeeDataDiv = $("#employeeData");
                                employeeDataDiv.empty();
                                
                                if (projects.length === 0) {
                                    employeeDataDiv.html('<div class="alert alert-info mt-3">No projects assigned to you yet.</div>');
                                    return;
                                }
                                
                                let html = `
                                    <div class="card mt-3">
                                        <div class="card-header">
                                            <h6>Your Assigned Projects</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="list-group">
                                `;
                                
                                projects.forEach(project => {
                                    html += `
                                        <div class="list-group-item">
                                            <h6>${project.project_name}</h6>
                                            <small>Start: ${project.start_date} | End: ${project.end_date}</small>
                                        </div>
                                    `;
                                });
                                
                                html += '</div></div></div>';
                                employeeDataDiv.html(html);
                            }
                        },
                        error: function(error) {
                            $("#loader").hide();
                            if (error.responseJSON && error.responseJSON.message) {
                                alert(error.responseJSON.message);
                            }
                        }
                    });
                }
            },
            error: function(error) {
                $("#loader").hide();
                if (error.responseJSON && error.responseJSON.message) {
                    alert(error.responseJSON.message);
                } else {
                    alert("Unable to fetch your employee information");
                }
            }
        });
    }
    
    function loadMyAssignments() {
        const role = getUserRole();
        
        if (role !== 'employee') {
            alert("This feature is only available for employees");
            return;
        }
        
        $("#loader").show();
        
        // Get assignments for current employee
        $.ajax({
            url: API_BASE_URL + "/assignments",
            method: "GET",
            success: function(response) {
                $("#loader").hide();
                if (response.status === 'success') {
                    const employeeDataDiv = $("#employeeData");
                    employeeDataDiv.empty();
                    
                    if (!response.employee || !response.assigned_projects || response.assigned_projects.length === 0) {
                        employeeDataDiv.html('<div class="alert alert-info mt-3">No project assignments found.</div>');
                        return;
                    }
                    
                    let html = `
                        <div class="card mt-3">
                            <div class="card-header">
                                <h6>Your Project Assignments</h6>
                            </div>
                            <div class="card-body">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Project Name</th>
                                            <th>Start Date</th>
                                            <th>End Date</th>
                                            <th>Assigned Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                    `;
                    
                    response.assigned_projects.forEach(project => {
                        html += `
                            <tr>
                                <td>${project.project_name}</td>
                                <td>${project.start_date}</td>
                                <td>${project.end_date}</td>
                                <td>${project.pivot.assigned_date}</td>
                            </tr>
                        `;
                    });
                    
                    html += '</tbody></table></div></div>';
                    employeeDataDiv.html(html);
                }
            },
            error: function(error) {
                $("#loader").hide();
                if (error.responseJSON && error.responseJSON.message) {
                    alert(error.responseJSON.message);
                }
            }
        });
    }
</script>
@endpush
@endsection