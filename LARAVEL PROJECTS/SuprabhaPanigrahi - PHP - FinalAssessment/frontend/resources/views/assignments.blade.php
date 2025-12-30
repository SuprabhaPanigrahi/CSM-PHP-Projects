@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0" id="pageTitle">Project Assignments</h4>
            </div>
            <div class="card-body">
                <!-- Employee filter - only show for admin/manager -->
                <div class="mb-3" id="employeeFilterSection" style="display: none;">
                    <select class="form-control" id="employeeFilter" onchange="loadAssignments()" style="max-width: 300px;">
                        <option value="">All Employees</option>
                    </select>
                </div>
                
                <div id="assignmentsList">
                    <p class="text-center">Loading assignments...</p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        const role = getUserRole();
        
        if (role === 'admin' || role === 'manager') {
            $("#employeeFilterSection").show();
            $("#pageTitle").text("Project Assignments");
            loadEmployeesForFilter();
        } else if (role === 'employee') {
            $("#pageTitle").text("My Project Assignments");
        }
        
        loadAssignments();
    });
    
    function loadEmployeesForFilter() {
        $.ajax({
            url: API_BASE_URL + "/employees",
            method: "GET",
            success: function(response) {
                if (response.status === 'success') {
                    const select = $("#employeeFilter");
                    response.data.forEach(employee => {
                        select.append(`<option value="${employee.id}">${employee.name}</option>`);
                    });
                }
            },
            error: function(error) {
                console.error("Failed to load employees for filter");
            }
        });
    }
    
    function loadAssignments() {
        const role = getUserRole();
        let url = API_BASE_URL + "/assignments";
        
        // For admin/manager with filter
        if ((role === 'admin' || role === 'manager') && $("#employeeFilter").length) {
            const employeeId = $("#employeeFilter").val();
            if (employeeId) {
                url = API_BASE_URL + "/assignments/employee/" + employeeId;
            }
        }
        // For employee, they always see their own assignments
        else if (role === 'employee') {
            // Employee will see their own assignments (handled by backend)
        }
        
        $("#loader").show();
        $.ajax({
            url: url,
            method: "GET",
            success: function(response) {
                $("#loader").hide();
                if (response.status === 'success') {
                    displayAssignments(response, role);
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
    
    function displayAssignments(response, role) {
        const container = $("#assignmentsList");
        container.empty();
        
        // Handle different response structures
        if (role === 'employee') {
            // Employee sees their own assignments
            if (response.employee && response.assigned_projects) {
                const html = `
                    <div class="card">
                        <div class="card-header">
                            <h5>${response.employee.name} - ${response.employee.email}</h5>
                        </div>
                        <div class="card-body">
                `;
                
                if (response.assigned_projects.length > 0) {
                    const tableHtml = `
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
                                ${response.assigned_projects.map(project => `
                                    <tr>
                                        <td>${project.project_name}</td>
                                        <td>${project.start_date}</td>
                                        <td>${project.end_date}</td>
                                        <td>${project.pivot.assigned_date}</td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    `;
                    container.html(html + tableHtml + '</div></div>');
                } else {
                    container.html(html + '<p class="text-center">No projects assigned to you yet.</p></div></div>');
                }
            }
        } else {
            // Admin/Manager view
            if (response.employee) {
                // Viewing specific employee
                container.html(`
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5>${response.employee.name} - ${response.employee.email}</h5>
                        </div>
                        <div class="card-body">
                            ${response.assigned_projects && response.assigned_projects.length > 0 ? `
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
                                        ${response.assigned_projects.map(project => `
                                            <tr>
                                                <td>${project.project_name}</td>
                                                <td>${project.start_date}</td>
                                                <td>${project.end_date}</td>
                                                <td>${project.pivot.assigned_date}</td>
                                            </tr>
                                        `).join('')}
                                    </tbody>
                                </table>
                            ` : '<p class="text-center">No projects assigned to this employee</p>'}
                        </div>
                    </div>
                `);
            } else if (Array.isArray(response.data)) {
                // Viewing all employees
                let html = '';
                response.data.forEach(employee => {
                    if (employee.projects && employee.projects.length > 0) {
                        html += `
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h5>${employee.name} - ${employee.email}</h5>
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
                        
                        employee.projects.forEach(project => {
                            html += `
                                <tr>
                                    <td>${project.project_name}</td>
                                    <td>${project.start_date}</td>
                                    <td>${project.end_date}</td>
                                    <td>${project.pivot.assigned_date}</td>
                                </tr>
                            `;
                        });
                        
                        html += `</tbody></table></div></div>`;
                    }
                });
                
                if (html === '') {
                    container.html('<p class="text-center">No assignments found</p>');
                } else {
                    container.html(html);
                }
            }
        }
        
        if (container.html().trim() === '') {
            container.html('<p class="text-center">No assignments found</p>');
        }
    }
</script>
@endpush
@endsection