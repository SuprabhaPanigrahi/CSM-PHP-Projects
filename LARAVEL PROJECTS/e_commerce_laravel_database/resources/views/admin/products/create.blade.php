@extends('layouts.app')

@section('title', 'Add New Product')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-plus"></i> Add New Product
                    </h4>
                </div>
                <div class="card-body">
                    <form action="/admin/products" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Product Name <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}"
                                   required
                                   placeholder="Enter product name">
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                            <textarea class="form-control" 
                                      id="description" 
                                      name="description" 
                                      rows="4" 
                                      required
                                      placeholder="Enter product description">{{ old('description') }}</textarea>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="price" class="form-label">Price ($) <span class="text-danger">*</span></label>
                                <input type="number" 
                                       step="0.01" 
                                       class="form-control" 
                                       id="price" 
                                       name="price" 
                                       value="{{ old('price') }}"
                                       required
                                       placeholder="0.00"
                                       min="0">
                            </div>
                            <div class="col-md-6">
                                <label for="discount_price" class="form-label">Discount Price ($)</label>
                                <input type="number" 
                                       step="0.01" 
                                       class="form-control" 
                                       id="discount_price" 
                                       name="discount_price" 
                                       value="{{ old('discount_price') }}"
                                       placeholder="0.00"
                                       min="0">
                                <small class="text-muted">Leave empty for no discount</small>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="stock" class="form-label">Stock Quantity <span class="text-danger">*</span></label>
                                <input type="number" 
                                       class="form-control" 
                                       id="stock" 
                                       name="stock" 
                                       value="{{ old('stock') }}"
                                       required
                                       placeholder="0"
                                       min="0">
                            </div>
                            <div class="col-md-6">
                                <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                                <select class="form-select" id="category_id" name="category_id" required>
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Product Image <span class="text-danger">*</span></label>
                            <input type="file" 
                                   class="form-control" 
                                   id="image" 
                                   name="image"
                                   required
                                   accept="image/*">
                            <div class="form-text">
                                Upload a product image. Max size: 2MB. Supported formats: JPG, PNG, GIF.
                            </div>
                            
                            <div id="imagePreview" class="mt-2" style="display: none;">
                                <img id="previewImage" src="" alt="Preview" 
                                     class="img-thumbnail" style="max-width: 200px;">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           id="status" 
                                           name="status"
                                           checked>
                                    <label class="form-check-label" for="status">
                                        Active Product
                                    </label>
                                </div>
                                <div class="form-text">
                                    If unchecked, the product will be hidden from the website.
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           id="featured" 
                                           name="featured">
                                    <label class="form-check-label" for="featured">
                                        Featured Product
                                    </label>
                                </div>
                                <div class="form-text">
                                    Featured products appear on homepage.
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="/admin/products" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Product
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

    // Validate discount price is less than regular price
    document.getElementById('discount_price').addEventListener('blur', function() {
        const price = parseFloat(document.getElementById('price').value);
        const discountPrice = parseFloat(this.value);
        
        if (discountPrice && discountPrice >= price) {
            alert('Discount price must be less than regular price');
            this.value = '';
            this.focus();
        }
    });
</script>
@endsection
@endsection