@extends('layouts.app')

@section('title', 'Home - Form Application')

@section('content')
<div class="text-center">
    <h1 class="mb-4"><i class="fas fa-form text-primary"></i> Form Application Frontend</h1>
    <p class="lead mb-4">This frontend application communicates with a separate backend API to submit and retrieve form data.</p>
    
    <div class="row mt-5">
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="mb-3">
                        <i class="fas fa-edit fa-3x text-primary"></i>
                    </div>
                    <h3 class="card-title">Submit Form</h3>
                    <p class="card-text">Fill out the multi-field form with various input types including dropdowns, file uploads, checkboxes, and radio buttons.</p>
                    <a href="{{ url('/form') }}" class="btn btn-primary-custom">
                        <i class="fas fa-arrow-right me-2"></i>Go to Form
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="mb-3">
                        <i class="fas fa-database fa-3x text-success"></i>
                    </div>
                    <h3 class="card-title">View Submissions</h3>
                    <p class="card-text">View all form submissions fetched from the backend API. See data submitted by users in real-time.</p>
                    <a href="{{ url('/submissions') }}" class="btn btn-success-custom">
                        <i class="fas fa-arrow-right me-2"></i>View Submissions
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="mb-3">
                        <i class="fas fa-code fa-3x text-info"></i>
                    </div>
                    <h3 class="card-title">API Integration</h3>
                    <p class="card-text">Built with Laravel Blade templates and AJAX calls to a separate backend API running on a different port.</p>
                    <div class="mt-3">
                        <span class="badge bg-info">Frontend: Port 8080</span>
                        <span class="badge bg-secondary">Backend: Port 8000</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection