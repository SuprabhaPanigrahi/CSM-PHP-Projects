@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Add New Product</h2>

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Product Name *</label>
                <input type="text" name="name" class="form-control"
                    value="{{ old('name') }}" required>
            </div>

            <!-- In the form, replace the category input with: -->
            <div class="col-md-6 mb-3">
                <label class="form-label">Category *</label>
                <select name="category" class="form-select" required>
                    <option value="">Select Category</option>
                    @foreach($categories as $category)
                    <option value="{{ $category }}" {{ old('category') == $category ? 'selected' : '' }}>
                        {{ $category }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Price *</label>
                <input type="number" step="0.01" name="price" class="form-control"
                    value="{{ old('price') }}" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Product Image *</label>
                <input type="file" name="image" class="form-control" accept="image/*" required>
                <small class="text-muted">Max 2MB, JPG, PNG, GIF, WebP</small>
            </div>

            <div class="col-12 mb-3">
                <label class="form-label">Description *</label>
                <textarea name="description" class="form-control" rows="4"
                    required>{{ old('description') }}</textarea>
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-success px-4">
                    <i class="bi bi-check-circle me-2"></i>Save Product
                </button>
                <a href="{{ route('products.index') }}" class="btn btn-secondary ms-2">
                    <i class="bi bi-x-circle me-2"></i>Cancel
                </a>
            </div>
        </div>
    </form>
</div>
@endsection