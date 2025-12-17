@extends('layouts.app')

@section('title', 'Manage Categories')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2>Categories</h2>
        </div>
        <div class="col-md-6 text-end">
            <a href="/admin/categories/create" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New Category
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th width="50">ID</th>
                            <th>Name</th>
                            <th width="100">Image</th>
                            <th width="100">Status</th>
                            <th width="150">Created At</th>
                            <th width="150">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                        <tr>
                            <td>{{ $category->id }}</td>
                            <td>{{ $category->name }}</td>
                            <td>
                                @if($category->image)
                                <img src="{{ asset('storage/' . $category->image) }}" 
                                     alt="{{ $category->name }}"
                                     class="img-thumbnail" 
                                     style="width: 80px; height: 80px; object-fit: cover;">
                                @else
                                <span class="text-muted">No Image</span>
                                @endif
                            </td>
                            <td>
                                @if($category->status == 1)
                                <span class="badge bg-success">Active</span>
                                @else
                                <span class="badge bg-danger">Inactive</span>
                                @endif
                            </td>
                            <td>{{ date('d M Y', strtotime($category->created_at)) }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="/admin/categories/{{ $category->id }}/edit" 
                                       class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a href="/admin/categories/{{ $category->id }}/delete" 
                                       class="btn btn-sm btn-danger"
                                       onclick="return confirm('Are you sure you want to delete this category?')">
                                        <i class="fas fa-trash"></i> Delete
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                No categories found. <a href="/admin/categories/create">Create one</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($categories->count() > 0)
            <div class="mt-3">
                <p class="text-muted">
                    Showing {{ $categories->count() }} categories
                </p>
            </div>
            @endif
        </div>
    </div>

    <div class="mt-3">
        <a href="/admin/dashboard" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>
</div>
@endsection