@extends('layout')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Students List</h1>
    <a href="{{ route('students.create') }}" class="btn btn-primary">Add New Student</a>
</div>

@if($students->count() > 0)
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Country</th>
                    <th>State</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $student)
                <tr>
                    <td>
                        @if($student->profile_image)
                            <img src="{{ asset('images/students/' . $student->profile_image) }}" 
                                 alt="Profile" class="student-img">
                        @else
                            <img src="{{ asset('images/students/default.png') }}" 
                                 alt="Profile" class="student-img">
                        @endif
                    </td>
                    <td>{{ $student->name }}</td>
                    <td>{{ $student->email }}</td>
                    <td>{{ $student->phone }}</td>
                    <td>{{ $student->country->name }}</td>
                    <td>{{ $student->state->name }}</td>
                    <td>
                        <a href="{{ route('students.edit', $student->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('students.destroy', $student->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" 
                                    onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <div class="alert alert-info">
        No students found. <a href="{{ route('students.create') }}">Add the first student</a>.
    </div>
@endif
@endsection