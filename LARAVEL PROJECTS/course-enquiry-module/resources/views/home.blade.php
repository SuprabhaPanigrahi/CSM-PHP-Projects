@extends('layouts.app')

@section('content')
<div class="text-center py-5">
    <h1 class="display-4 mb-4">Welcome to the Course Enquiry Portal</h1>
    <p class="lead mb-5">Explore our range of courses and submit your enquiry online.</p>
    
    <a href="{{ route('courses.index') }}" class="btn btn-primary btn-lg">
        View Courses
    </a>
</div>
@endsection