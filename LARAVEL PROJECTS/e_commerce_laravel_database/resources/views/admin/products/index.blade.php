@extends('layouts.app')

@section('title', 'Manage Products')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2>Products</h2>
        </div>
        <div class="col-md-6 text-end">
            <a href="/admin/products/create" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New Product
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
                            <th width="100">Image</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th width="120">Price</th>
                            <th width="100">Stock</th>
                            <th width="100">Status</th>
                            <th width="150">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td>
                                @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" 
                                     alt="{{ $product->name }}"
                                     class="img-thumbnail" 
                                     style="width: 60px; height: 60px; object-fit: cover;">
                                @else
                                <span class="text-muted">No Image</span>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $product->name }}</strong><br>
                                <small class="text-muted">{{ Str::limit($product->description, 50) }}</small>
                            </td>
                            <td>{{ $product->category_name }}</td>
                            <td>
                                @if($product->discount_price)
                                <span class="text-danger"><strong>${{ number_format($product->discount_price, 2) }}</strong></span><br>
                                <small class="text-muted text-decoration-line-through">${{ number_format($product->price, 2) }}</small>
                                @else
                                <strong>${{ number_format($product->price, 2) }}</strong>
                                @endif
                            </td>
                            <td>{{ $product->stock }}</td>
                            <td>
                                @if($product->status == 1)
                                <span class="badge bg-success">Active</span>
                                @else
                                <span class="badge bg-danger">Inactive</span>
                                @endif
                                @if($product->featured == 1)
                                <span class="badge bg-warning mt-1">Featured</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="/products/{{ $product->id }}" target="_blank" 
                                       class="btn btn-sm btn-info" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="/admin/products/{{ $product->id }}/edit" 
                                       class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="/admin/products/{{ $product->id }}/delete" 
                                       class="btn btn-sm btn-danger"
                                       onclick="return confirm('Are you sure you want to delete this product?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                No products found. <a href="/admin/products/create">Create one</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($products->count() > 0)
            <div class="mt-3">
                <p class="text-muted">
                    Showing {{ $products->count() }} products
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