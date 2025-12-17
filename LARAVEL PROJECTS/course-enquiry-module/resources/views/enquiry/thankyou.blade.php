@extends('layouts.app')

@section('content')
<div class="text-center py-5">
    <div class="card border-success">
        <div class="card-body py-5">
            <h1 class="display-4 text-success mb-4">Thank You!</h1>
            
            <div class="alert alert-success fs-4">
                Thank you, <strong>{{ $name }}</strong>, for your enquiry about 
                <strong>{{ $course }}</strong>. We will contact you soon.
            </div>
            
            <div class="mt-5">
                <a href="{{ route('home') }}" class="btn btn-primary btn-lg me-2">Go to Home</a>
                <a href="{{ route('courses.index') }}" class="btn btn-secondary btn-lg">Browse More Courses</a>
            </div>
        </div>
    </div>
</div>
@endsection