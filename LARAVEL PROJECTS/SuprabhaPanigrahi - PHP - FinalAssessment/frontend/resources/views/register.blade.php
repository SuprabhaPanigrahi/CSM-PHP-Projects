@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Register</h4>
            </div>
            <div class="card-body">
                <form id="registerForm">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username *</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                        <small class="text-muted">This will be your login username</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Password *</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm Password *</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="role" class="form-label">Role *</label>
                        <select class="form-control" id="role" name="role" required onchange="toggleEmployeeFields()">
                            <option value="">Select Role</option>
                            <option value="admin">Admin</option>
                            <option value="manager">Manager</option>
                            <option value="employee">Employee</option>
                        </select>
                    </div>
                    
                    <!-- Employee specific fields (hidden by default) -->
                    <div id="employeeFields" style="display: none;">
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name *</label>
                            <input type="text" class="form-control" id="name" name="name">
                            <small class="text-muted">Your full name for employee record</small>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address *</label>
                            <input type="email" class="form-control" id="email" name="email">
                            <small class="text-muted">Your official email address</small>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Register</button>
                    <a href="/login" class="btn btn-link">Already have an account? Login</a>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function toggleEmployeeFields() {
        const role = $("#role").val();
        const employeeFields = $("#employeeFields");
        
        if (role === 'employee') {
            employeeFields.show();
            $("#name").prop('required', true);
            $("#email").prop('required', true);
        } else {
            employeeFields.hide();
            $("#name").prop('required', false);
            $("#email").prop('required', false);
        }
    }
    
    $(document).ready(function() {
        toggleEmployeeFields();
        
        $("#registerForm").submit(function(e) {
            e.preventDefault();
            $("#loader").show();
            
            // Prepare data based on role
            const formData = {
                username: $("#username").val(),
                password: $("#password").val(),
                password_confirmation: $("#password_confirmation").val(),
                role: $("#role").val()
            };
            
            // Add employee fields if role is employee
            if ($("#role").val() === 'employee') {
                formData.name = $("#name").val();
                formData.email = $("#email").val();
            }
            
            $.ajax({
                url: API_BASE_URL + "/auth/register",
                method: "POST",
                data: formData,
                dataType: "json",
                success: function(response) {
                    $("#loader").hide();
                    if (response.status === 'success') {
                        alert("User Registered Successfully");
                        setTimeout(function() {
                            window.location.href = "/login";
                        }, 1000);
                    }
                },
                error: function(error) {
                    $("#loader").hide();
                    if (error.responseJSON && error.responseJSON.errors) {
                        const errors = error.responseJSON.errors;
                        // Show all errors
                        for (const field in errors) {
                            if (errors[field][0]) {
                                alert(field + ": " + errors[field][0]);
                                break;
                            }
                        }
                    } else if (error.responseJSON && error.responseJSON.message) {
                        alert(error.responseJSON.message);
                    } else {
                        alert("Registration failed. Please try again.");
                    }
                }
            });
        });
    });
</script>
@endpush
@endsection