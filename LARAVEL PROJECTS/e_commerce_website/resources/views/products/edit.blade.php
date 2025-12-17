@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Edit Product</h2>

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif

    <form action="{{ route('products.update', $product['id']) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Product Name *</label>
                <input type="text" name="name" class="form-control"
                    value="{{ old('name', $product['name'] ?? '') }}" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Category *</label>
                <select name="category" class="form-select" required>
                    <option value="">Select Category</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat }}" {{ old('category', $product['category'] ?? '') == $cat ? 'selected' : '' }}>
                        {{ $cat }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Price *</label>
                <input type="number" step="0.01" name="price" class="form-control"
                    value="{{ old('price', $product['price'] ?? 0) }}" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Product Image</label>
                <input type="file" name="image" class="form-control" accept="image/*">
                <small class="text-muted">Leave empty to keep current image</small>
                @if(isset($product['image']) && $product['image'] != 'default.jpg')
                <div class="mt-2">
                    <small>Current image:</small><br>
                    <img src="{{ asset('storage/' . $product['image']) }}"
                        width="100" class="img-thumbnail mt-1">
                </div>
                @endif
            </div>

            <div class="col-12 mb-3">
                <label class="form-label">Description *</label>
                <textarea name="description" class="form-control" rows="4"
                    required>{{ old('description', $product['description'] ?? '') }}</textarea>
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-save me-2"></i>Update Product
                </button>
                <a href="{{ route('products.index') }}" class="btn btn-secondary ms-2">
                    <i class="bi bi-x-circle me-2"></i>Cancel
                </a>
            </div>
        </div>
    </form>
</div>
@endsection