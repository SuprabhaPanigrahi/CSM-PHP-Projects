@extends('layouts.app')

@section('title', 'Register')

@section('auth-content')
<div class="container">
    <div class="row justify-content-center mt-4">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow">
                <div class="card-header bg-success text-white text-center">
                    <h4>Customer Registration</h4>
                </div>
                <div class="card-body">
                    <div id="message"></div>
                    <form id="registerForm">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="customer_name" class="form-label">Full Name *</label>
                                <input type="text" class="form-control" id="customer_name" required
                                    placeholder="Enter your full name">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email Address *</label>
                                <input type="email" class="form-control" id="email" required
                                    placeholder="Enter your email">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Password *</label>
                                <input type="password" class="form-control" id="password" required
                                    placeholder="Enter password (min 6 characters)">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">Confirm Password *</label>
                                <input type="password" class="form-control" id="password_confirmation" required
                                    placeholder="Confirm your password">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="phone_number" class="form-label">Phone Number *</label>
                                <input type="text" class="form-control" id="phone_number" required
                                    placeholder="Enter phone number">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="customer_type" class="form-label">Customer Type *</label>
                                <select class="form-select" id="customer_type" required>
                                    <option value="">Select Type</option>
                                    <option value="silver">Silver</option>
                                    <option value="gold">Gold</option>
                                    <option value="diamond">Diamond</option>
                                </select>
                                <small class="text-muted">Note: Gold and Diamond customers get additional benefits</small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Address *</label>
                            <textarea class="form-control" id="address" rows="3" required
                                placeholder="Enter your complete address"></textarea>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg">
                                Register
                            </button>
                            <a href="{{ route('login') }}" class="btn btn-outline-secondary">
                                Already Registered? Login Here
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
        $('#registerForm').submit(function(e) {
            e.preventDefault();

            // Get form data
            const formData = {
                customer_name: $('#customer_name').val(),
                email: $('#email').val(),
                password: $('#password').val(),
                password_confirmation: $('#password_confirmation').val(),
                phone_number: $('#phone_number').val(),
                customer_type: $('#customer_type').val(),
                address: $('#address').val()
            };

            // Validate passwords match
            if (formData.password !== formData.password_confirmation) {
                $('#message').html(`
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        Passwords do not match!
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `);
                return;
            }

            // Clear previous messages
            $('#message').empty();

            // Show loading
            const submitBtn = $(this).find('button[type="submit"]');
            const originalText = submitBtn.html();
            submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Registering...').prop('disabled', true);

            $.ajax({
                url: API_BASE_URL + '/register',
                method: 'POST',
                data: formData,
                success: function(response) {
                    if (response.status === 'success') {
                        $('#message').html(`
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                Registration successful! Redirecting to login...
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        `);

                        setTimeout(() => {
                            window.location.href = '{{ route("login") }}';
                        }, 2000);
                    } else {
                        $('#message').html(`
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                ${response.message || 'Registration failed'}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        `);
                        submitBtn.html(originalText).prop('disabled', false);
                    }
                },
                error: function(xhr) {
                    let errorMessage = 'An error occurred during registration';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                        const errors = xhr.responseJSON.errors;
                        errorMessage = '';
                        for (const field in errors) {
                            errorMessage += errors[field].join('<br>') + '<br>';
                        }
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