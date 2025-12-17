@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-body">
        <h1 class="card-title">{{ $course['title'] }}</h1>
        
        <div class="row mt-4">
            <div class="col-md-6">
                <p><strong>Category:</strong> {{ ucfirst($course['category']) }}</p>
                <p><strong>Duration:</strong> {{ $course['duration'] }}</p>
                <p><strong>Level:</strong> {{ $course['level'] }}</p>
            </div>
        </div>
        
        <div class="mt-4">
            <h5>Description</h5>
            <p>{{ $course['description'] }}</p>
        </div>
        
        <div class="mt-4">
            <a href="{{ route('enquiry.create', ['course' => $course['title']]) }}" class="btn btn-primary btn-lg">
                Enquire Now
            </a>
            <a href="{{ route('courses.index') }}" class="btn btn-secondary">Back to Courses</a>
        </div>
    </div>
</div>
@endsection