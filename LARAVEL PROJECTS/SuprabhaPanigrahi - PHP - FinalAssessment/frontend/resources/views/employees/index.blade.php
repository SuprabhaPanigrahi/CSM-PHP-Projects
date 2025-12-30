@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0" id="pageTitle">Employee Management</h4>
                <button class="btn btn-primary btn-sm" id="addEmployeeBtn" onclick="showAddEmployeeModal()" style="display: none;">
                    Add Employee
                </button>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Created By</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="employeesTable">
                        <tr>
                            <td colspan="5" class="text-center">Loading employees...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Employee Modal -->
<div class="modal fade" id="addEmployeeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Employee</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="employeeName" class="form-label">Name</label>
                    <input type="text" class="form-control" id="employeeName">
                </div>
                <div class="mb-3">
                    <label for="employeeEmail" class="form-label">Email</label>
                    <input type="email" class="form-control" id="employeeEmail">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="addEmployee()">Save</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        const role = getUserRole();
        const pageTitle = $("#pageTitle");
        const addButton = $("#addEmployeeBtn");
        
        if (role === 'admin') {
            pageTitle.text("View Employees");
            addButton.hide();
        } else if (role === 'manager') {
            pageTitle.text("Manage Employees");
            addButton.show();
        } else {
            alert("You don't have permission to view this page");
            window.location.href = "/dashboard";
            return;
        }
        
        loadEmployees();
    });
    
    function loadEmployees() {
        $("#loader").show();
        $.ajax({
            url: API_BASE_URL + "/employees",
            method: "GET",
            success: function(response) {
                $("#loader").hide();
                if (response.status === 'success') {
                    const employees = response.data;
                    const tableBody = $("#employeesTable");
                    tableBody.empty();
                    
                    if (employees.length === 0) {
                        tableBody.append(`
                            <tr>
                                <td colspan="5" class="text-center">No employees found</td>
                            </tr>
                        `);
                        return;
                    }
                    
                    const role = getUserRole();
                    
                    employees.forEach(employee => {
                        let actionButtons = '';
                        
                        if (role === 'manager') {
                            actionButtons = `
                                <button class="btn btn-sm btn-info" onclick="editEmployee(${employee.id})">Edit</button>
                                <button class="btn btn-sm btn-danger" onclick="deleteEmployee(${employee.id})">Delete</button>
                            `;
                        } else if (role === 'admin') {
                            actionButtons = '<span class="text-muted">View Only</span>';
                        }
                        
                        tableBody.append(`
                            <tr>
                                <td>${employee.id}</td>
                                <td>${employee.name}</td>
                                <td>${employee.email}</td>
                                <td>${employee.creator ? employee.creator.username : 'N/A'}</td>
                                <td>${actionButtons}</td>
                            </tr>
                        `);
                    });
                }
            },
            error: function(error) {
                $("#loader").hide();
                if (error.status === 403) {
                    alert("You don't have permission to view employees.");
                    window.location.href = "/dashboard";
                } else if (error.responseJSON && error.responseJSON.message) {
                    alert(error.responseJSON.message);
                }
            }
        });
    }
    
    function showAddEmployeeModal() {
        const role = getUserRole();
        if (role !== 'manager') {
            alert("Only managers can add employees");
            return;
        }
        
        $("#employeeName").val("");
        $("#employeeEmail").val("");
        $("#addEmployeeModal").modal("show");
    }
    
    function addEmployee() {
        const role = getUserRole();
        if (role !== 'manager') {
            alert("Only managers can add employees");
            return;
        }
        
        const name = $("#employeeName").val();
        const email = $("#employeeEmail").val();
        
        if (!name || !email) {
            alert("Please fill all fields");
            return;
        }
        
        $("#loader").show();
        $.ajax({
            url: API_BASE_URL + "/employees",
            method: "POST",
            data: {
                name: name,
                email: email
            },
            success: function(response) {
                $("#loader").hide();
                if (response.status === 'success') {
                    $("#addEmployeeModal").modal("hide");
                    alert("Employee added successfully");
                    loadEmployees();
                }
            },
            error: function(error) {
                $("#loader").hide();
                if (error.responseJSON && error.responseJSON.errors) {
                    const errors = error.responseJSON.errors;
                    if (errors.name) {
                        alert(errors.name[0]);
                    } else if (errors.email) {
                        alert(errors.email[0]);
                    }
                } else if (error.responseJSON && error.responseJSON.message) {
                    alert(error.responseJSON.message);
                }
            }
        });
    }
    
    function editEmployee(id) {
        const role = getUserRole();
        if (role !== 'manager') {
            alert("Only managers can edit employees");
            return;
        }
        
        const newName = prompt("Enter new name:");
        if (!newName) return;
        
        const newEmail = prompt("Enter new email:");
        if (!newEmail) return;
        
        $("#loader").show();
        $.ajax({
            url: API_BASE_URL + "/employees/" + id,
            method: "PUT",
            data: {
                name: newName,
                email: newEmail
            },
            success: function(response) {
                $("#loader").hide();
                if (response.status === 'success') {
                    alert("Employee updated successfully");
                    loadEmployees();
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
    
    function deleteEmployee(id) {
        const role = getUserRole();
        if (role !== 'manager') {
            alert("Only managers can delete employees");
            return;
        }
        
        if (!confirm("Are you sure you want to delete this employee?")) {
            return;
        }
        
        $("#loader").show();
        $.ajax({
            url: API_BASE_URL + "/employees/" + id,
            method: "DELETE",
            success: function(response) {
                $("#loader").hide();
                if (response.status === 'success') {
                    alert("Employee deleted successfully");
                    loadEmployees();
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