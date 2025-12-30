@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Login</h4>
            </div>
            <div class="card-body">
                <form id="loginForm">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Login</button>
                    <a href="/register" class="btn btn-link">Don't have an account? Register</a>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        $("#loginForm").submit(function(e) {
            e.preventDefault();
            $("#loader").show();
            
            $.ajax({
                url: API_BASE_URL + "/auth/login",
                method: "POST",
                data: {
                    username: $("#username").val(),
                    password: $("#password").val()
                },
                dataType: "json",
                success: function(response) {
                    $("#loader").hide();
                    if (response.status === 'success') {
                        localStorage.setItem("token", response.access_token);
                        
                        const token = response.access_token;
                        const base64Url = token.split('.')[1];
                        const base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
                        const jsonPayload = decodeURIComponent(atob(base64).split('').map(function(c) {
                            return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
                        }).join(''));
                        
                        const payload = JSON.parse(jsonPayload);
                        localStorage.setItem("userId", payload.user_id);
                        localStorage.setItem("username", payload.username);
                        localStorage.setItem("userRole", payload.role);
                        
                        alert("Login Successful");
                        setTimeout(function() {
                            window.location.href = "/dashboard";
                        }, 1000);
                    }
                },
                error: function(error) {
                    $("#loader").hide();
                    if (error.responseJSON && error.responseJSON.message) {
                        alert(error.responseJSON.message);
                    } else {
                        alert("Login failed. Please check your credentials.");
                    }
                }
            });
        });
    });
</script>
@endpush
@endsection