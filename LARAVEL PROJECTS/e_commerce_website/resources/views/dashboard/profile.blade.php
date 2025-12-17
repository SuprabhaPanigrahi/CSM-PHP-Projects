@extends('dashboard.main')

@section('content')

<h2 class="mb-4 fw-bold">Admin Profile</h2>

<div class="row g-4">

    <!-- Profile Left Sidebar -->
    <div class="col-md-4">
        <div class="profile-card shadow-sm p-4 text-center">

            <!-- Profile Image -->
            <img src="{{ asset('images/admin.png') }}" class="profile-img" alt="Admin">

            <h4 class="mt-3">{{ $admin->name ?? 'Admin User' }}</h4>
            <p class="text-muted">{{ $admin->email ?? 'admin@example.com' }}</p>

            <div class="mt-3">
                <a href="#" class="btn btn-primary btn-sm">Change Photo</a>
            </div>

            <hr>

            <!-- Small Stats -->
            <div class="row text-center">
                <div class="col-4">
                    <h5 class="mb-0 fw-bold">35</h5>
                    <small class="text-muted">Products</small>
                </div>
                <div class="col-4">
                    <h5 class="mb-0 fw-bold">10</h5>
                    <small class="text-muted">Categories</small>
                </div>
                <div class="col-4">
                    <h5 class="mb-0 fw-bold">120</h5>
                    <small class="text-muted">Orders</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Edit Form -->
    <div class="col-md-8">
        <div class="profile-card shadow-sm p-4">

            <h4 class="fw-bold mb-3">Edit Profile</h4>

            <form action=" " method="POST">
                @csrf

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Full Name</label>
                        <input type="text" name="name" class="form-control" 
                               value="{{ $admin->name ?? '' }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Email</label>
                        <input type="email" name="email" class="form-control"
                               value="{{ $admin->email ?? '' }}" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Phone Number</label>
                    <input type="text" name="phone" class="form-control"
                           value="{{ $admin->phone ?? '' }}">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Address</label>
                    <textarea name="address" class="form-control" rows="3">{{ $admin->address ?? '' }}</textarea>
                </div>

                <hr>

                <h5 class="fw-bold mb-3">Change Password</h5>

                <div class="mb-3">
                    <label class="form-label fw-bold">New Password</label>
                    <input type="password" name="password" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-control">
                </div>

                <button class="btn btn-success mt-2">Save Changes</button>
            </form>

        </div>
    </div>

</div>

@endsection
