@extends('layouts.app')

@section('content')
<h1 class="mb-4">Available Courses</h1>

<!-- Category Filter -->
<form method="GET" action="{{ route('courses.index') }}" class="mb-4">
    <div class="row">
        <div class="col-md-4">
            <select name="category" class="form-select" onchange="this.form.submit()">
                <option value="">All Categories</option>
                <option value="programming" {{ $selectedCategory == 'programming' ? 'selected' : '' }}>Programming</option>
                <option value="data" {{ $selectedCategory == 'data' ? 'selected' : '' }}>Data Science</option>
                <option value="business" {{ $selectedCategory == 'business' ? 'selected' : '' }}>Business</option>
                <option value="design" {{ $selectedCategory == 'design' ? 'selected' : '' }}>Design</option>
            </select>
        </div>
    </div>
</form>

<!-- Courses Grid -->
<div class="row">
    @foreach($courses as $course)
    <div class="col-md-6 col-lg-3">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="card-title">{{ $course['title'] }}</h5>
                <p class="card-text">
                    <strong>Category:</strong> {{ ucfirst($course['category']) }}<br>
                    <strong>Duration:</strong> {{ $course['duration'] }}<br>
                    <strong>Level:</strong> {{ $course['level'] }}
                </p>
                <a href="{{ route('courses.show', $course['slug']) }}" class="btn btn-primary">
                    View Details
                </a>
            </div>
        </div>
    </div>
    @endforeach
    
    @if(count($courses) == 0)
    <div class="col-12">
        <div class="alert alert-info">
            No courses found in this category.
        </div>
    </div>
    @endif
</div>
@endsection