@extends('layouts.app')

@section('title', 'Unauthorized')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center mt-5">
        <div class="col-md-6">
            <div class="card border-warning">
                <div class="card-header bg-warning text-dark">
                    <h4><i class="fas fa-exclamation-triangle"></i> Unauthorized Access</h4>
                </div>
                <div class="card-body text-center">
                    <div class="mb-4">
                        <i class="fas fa-user-slash fa-5x text-warning"></i>
                    </div>
                    <h3 class="text-warning mb-3">Session Expired</h3>
                    <p class="lead">Your session has expired or you are not logged in.</p>
                    
                    <div class="alert alert-warning">
                        <h5><i class="fas fa-clock"></i> Session Information</h5>
                        <p>Please login again to continue accessing the application.</p>
                    </div>
                    
                    <div class="mt-4">
                        <a href="{{ route('login') }}" class="btn btn-warning btn-lg">
                            <i class="fas fa-sign-in-alt"></i> Login Again
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection