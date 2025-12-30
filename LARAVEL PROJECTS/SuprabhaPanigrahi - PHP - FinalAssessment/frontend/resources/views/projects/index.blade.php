@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0" id="pageTitle">Project Management</h4>
                <button class="btn btn-primary btn-sm" id="addProjectBtn" onclick="showAddProjectModal()" style="display: none;">
                    Add Project
                </button>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Project Name</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Created By</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="projectsTable">
                        <tr>
                            <td colspan="6" class="text-center">Loading projects...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Project Modal -->
<div class="modal fade" id="addProjectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Project</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="projectName" class="form-label">Project Name</label>
                    <input type="text" class="form-control" id="projectName">
                </div>
                <div class="mb-3">
                    <label for="startDate" class="form-label">Start Date</label>
                    <input type="date" class="form-control" id="startDate">
                </div>
                <div class="mb-3">
                    <label for="endDate" class="form-label">End Date</label>
                    <input type="date" class="form-control" id="endDate">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="addProject()">Save</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        const role = getUserRole();
        const pageTitle = $("#pageTitle");
        const addButton = $("#addProjectBtn");
        
        if (role === 'admin') {
            pageTitle.text("View Projects");
            addButton.hide();
        } else if (role === 'manager') {
            pageTitle.text("Manage Projects");
            addButton.show();
        } else {
            alert("You don't have permission to view this page");
            window.location.href = "/dashboard";
            return;
        }
        
        loadProjects();
    });
    
    function loadProjects() {
        $("#loader").show();
        $.ajax({
            url: API_BASE_URL + "/projects",
            method: "GET",
            success: function(response) {
                $("#loader").hide();
                if (response.status === 'success') {
                    const projects = response.data;
                    const tableBody = $("#projectsTable");
                    tableBody.empty();
                    
                    if (projects.length === 0) {
                        tableBody.append(`
                            <tr>
                                <td colspan="6" class="text-center">No projects found</td>
                            </tr>
                        `);
                        return;
                    }
                    
                    const role = getUserRole();
                    
                    projects.forEach(project => {
                        let actionButtons = '';
                        
                        if (role === 'manager') {
                            actionButtons = `
                                <button class="btn btn-sm btn-info" onclick="editProject(${project.id})">Edit</button>
                                <button class="btn btn-sm btn-danger" onclick="deleteProject(${project.id})">Delete</button>
                            `;
                        } else if (role === 'admin') {
                            actionButtons = '<span class="text-muted">View Only</span>';
                        }
                        
                        tableBody.append(`
                            <tr>
                                <td>${project.id}</td>
                                <td>${project.project_name}</td>
                                <td>${project.start_date}</td>
                                <td>${project.end_date}</td>
                                <td>${project.creator ? project.creator.username : 'N/A'}</td>
                                <td>${actionButtons}</td>
                            </tr>
                        `);
                    });
                }
            },
            error: function(error) {
                $("#loader").hide();
                if (error.status === 403) {
                    alert("You don't have permission to view projects.");
                    window.location.href = "/dashboard";
                } else if (error.responseJSON && error.responseJSON.message) {
                    alert(error.responseJSON.message);
                }
            }
        });
    }
    
    function showAddProjectModal() {
        const role = getUserRole();
        if (role !== 'manager') {
            alert("Only managers can add projects");
            return;
        }
        
        $("#projectName").val("");
        $("#startDate").val("");
        $("#endDate").val("");
        $("#addProjectModal").modal("show");
    }
    
    function addProject() {
        const role = getUserRole();
        if (role !== 'manager') {
            alert("Only managers can add projects");
            return;
        }
        
        const name = $("#projectName").val();
        const startDate = $("#startDate").val();
        const endDate = $("#endDate").val();
        
        if (!name || !startDate || !endDate) {
            alert("Please fill all fields");
            return;
        }
        
        if (new Date(endDate) <= new Date(startDate)) {
            alert("End date must be after start date");
            return;
        }
        
        $("#loader").show();
        $.ajax({
            url: API_BASE_URL + "/projects",
            method: "POST",
            data: {
                project_name: name,
                start_date: startDate,
                end_date: endDate
            },
            success: function(response) {
                $("#loader").hide();
                if (response.status === 'success') {
                    $("#addProjectModal").modal("hide");
                    alert("Project added successfully");
                    loadProjects();
                }
            },
            error: function(error) {
                $("#loader").hide();
                if (error.responseJSON && error.responseJSON.errors) {
                    const errors = error.responseJSON.errors;
                    if (errors.project_name) {
                        alert(errors.project_name[0]);
                    } else if (errors.start_date) {
                        alert(errors.start_date[0]);
                    } else if (errors.end_date) {
                        alert(errors.end_date[0]);
                    }
                } else if (error.responseJSON && error.responseJSON.message) {
                    alert(error.responseJSON.message);
                }
            }
        });
    }
    
    function editProject(id) {
        const role = getUserRole();
        if (role !== 'manager') {
            alert("Only managers can edit projects");
            return;
        }
        
        const newName = prompt("Enter new project name:");
        if (!newName) return;
        
        const newStartDate = prompt("Enter new start date (YYYY-MM-DD):");
        if (!newStartDate) return;
        
        const newEndDate = prompt("Enter new end date (YYYY-MM-DD):");
        if (!newEndDate) return;
        
        $("#loader").show();
        $.ajax({
            url: API_BASE_URL + "/projects/" + id,
            method: "PUT",
            data: {
                project_name: newName,
                start_date: newStartDate,
                end_date: newEndDate
            },
            success: function(response) {
                $("#loader").hide();
                if (response.status === 'success') {
                    alert("Project updated successfully");
                    loadProjects();
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
    
    function deleteProject(id) {
        const role = getUserRole();
        if (role !== 'manager') {
            alert("Only managers can delete projects");
            return;
        }
        
        if (!confirm("Are you sure you want to delete this project?")) {
            return;
        }
        
        $("#loader").show();
        $.ajax({
            url: API_BASE_URL + "/projects/" + id,
            method: "DELETE",
            success: function(response) {
                $("#loader").hide();
                if (response.status === 'success') {
                    alert("Project deleted successfully");
                    loadProjects();
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