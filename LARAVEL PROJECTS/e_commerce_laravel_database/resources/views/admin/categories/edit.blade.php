@extends('layouts.app')

@section('title', 'Edit Category')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-warning text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-edit"></i> Edit Category
                    </h4>
                </div>
                <div class="card-body">
                    <form action="/admin/categories/{{ $category->id }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Category Name <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $category->name) }}"
                                   required
                                   placeholder="Enter category name">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Current Image</label>
                            <div>
                                @if($category->image)
                                <img src="{{ asset('storage/' . $category->image) }}" 
                                     alt="{{ $category->name }}"
                                     class="img-thumbnail mb-2" 
                                     style="width: 200px; height: 150px; object-fit: cover;">
                                @else
                                <div class="alert alert-info">
                                    No image uploaded for this category.
                                </div>
                                @endif
                            </div>
                            
                            <label for="image" class="form-label">Change Image</label>
                            <input type="file" 
                                   class="form-control @error('image') is-invalid @enderror" 
                                   id="image" 
                                   name="image"
                                   accept="image/*">
                            <div class="form-text">
                                Upload a new image to replace the current one (optional). Max size: 2MB.
                            </div>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            
                            <div id="imagePreview" class="mt-2" style="display: none;">
                                <img id="previewImage" src="" alt="Preview" 
                                     class="img-thumbnail" style="max-width: 200px;">
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="status" 
                                       name="status"
                                       {{ $category->status == 1 ? 'checked' : '' }}>
                                <label class="form-check-label" for="status">
                                    Active Category
                                </label>
                            </div>
                            <div class="form-text">
                                If unchecked, the category will be hidden from the website.
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="/admin/categories" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-save"></i> Update Category
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    // Image preview functionality
    document.getElementById('image').addEventListener('change', function(e) {
        const preview = document.getElementById('imagePreview');
        const previewImage = document.getElementById('previewImage');
        
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                preview.style.display = 'block';
            }
            
            reader.readAsDataURL(this.files[0]);
        } else {
            preview.style.display = 'none';
        }
    });
</script>
@endsection
@endsection