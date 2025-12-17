@extends('layouts.app')

@section('title', 'Sliders - Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Homepage Sliders</h2>
    <a href="/admin/sliders/create" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add Slider
    </a>
</div>

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sliders as $slider)
                    <tr>
                        <td>{{ $slider->id }}</td>
                        <td>
                            <img src="{{ asset('storage/' . $slider->image) }}" 
                                 style="width: 100px; height: 60px; object-fit: cover;">
                        </td>
                        <td>{{ $slider->title }}</td>
                        <td>{{ Str::limit($slider->description, 50) }}</td>
                        <td>
                            <span class="badge bg-{{ $slider->status ? 'success' : 'danger' }}">
                                {{ $slider->status ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <a href="/admin/sliders/{{ $slider->id }}/edit" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="/admin/sliders/{{ $slider->id }}/delete" 
                               class="btn btn-sm btn-danger"
                               onclick="return confirm('Delete this slider?')">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection