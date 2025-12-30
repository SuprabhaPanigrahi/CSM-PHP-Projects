@extends('layouts.app')

@section('title', 'Login')

@section('auth-content')
<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-6 col-lg-4">
            <div class="card shadow">
                <div class="card-header bg-primary text-white text-center">
                    <h4>Customer Login</h4>
                </div>
                <div class="card-body">
                    <div id="message"></div>
                    <form id="loginForm">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <div class="input-group">
                                <input type="email" class="form-control" id="email" required
                                    placeholder="Enter your email">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password" required
                                    placeholder="Enter your password">
                            </div>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                Login
                            </button>
                            <a href="{{ route('register') }}" class="btn btn-outline-primary">
                                New Customer? Register Here
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    $(document).ready(function() {
        $('#loginForm').submit(function(e) {
            e.preventDefault();

            const email = $('#email').val();
            const password = $('#password').val();

            // Clear previous messages
            $('#message').empty();

            // Show loading
            const submitBtn = $(this).find('button[type="submit"]');
            const originalText = submitBtn.html();
            submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Logging in...').prop('disabled', true);

            $.ajax({
                url: API_BASE_URL + '/login',
                method: 'POST',
                data: {
                    email: email,
                    password: password
                },
                success: function(response) {
                    if (response.status === 'success') {
                        // Store token and user data in client session via our client route
                        $.post('/set-session', {
                            token: response.data.token,
                            customer: response.data.customer,
                            _token: $('meta[name="csrf-token"]').attr('content')
                        }, function() {
                            window.location.href = '{{ route("dashboard") }}';
                        });
                    } else {
                        $('#message').html(`
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                ${response.message || 'Login failed'}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        `);
                        submitBtn.html(originalText).prop('disabled', false);
                    }
                },
                error: function(xhr) {
                    let errorMessage = 'An error occurred during login';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                        errorMessage = Object.values(xhr.responseJSON.errors).join('<br>');
                    }

                    $('#message').html(`
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            ${errorMessage}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    `);
                    submitBtn.html(originalText).prop('disabled', false);
                }
            });
        });
    });
</script>
@endsection
@endsection