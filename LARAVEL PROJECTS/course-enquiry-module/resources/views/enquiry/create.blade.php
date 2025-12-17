@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h2>Course Enquiry Form</h2>
            </div>
            
            <div class="card-body">
                <!-- Display Validation Errors -->
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('enquiry.store') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Name *</label>
                        <input type="text" class="form-control" id="name" name="name" 
                               value="{{ old('name') }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email *</label>
                        <input type="email" class="form-control" id="email" name="email" 
                               value="{{ old('email') }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="course" class="form-label">Course *</label>
                        <input type="text" class="form-control" id="course" name="course" 
                               value="{{ old('course', $prefilledCourse) }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="message" class="form-label">Message *</label>
                        <textarea class="form-control" id="message" name="message" 
                                  rows="5" required>{{ old('message') }}</textarea>
                        <small class="text-muted">Minimum 10 characters required</small>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">Submit Enquiry</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection