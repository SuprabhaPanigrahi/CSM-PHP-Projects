<!-- resources/views/products/index.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap Color Picker -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/3.4.0/css/bootstrap-colorpicker.min.css" rel="stylesheet">
    <style>
        .image-preview {
            max-width: 200px;
            max-height: 200px;
            margin: 10px 0;
        }
        .gallery-preview {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
        }
        .gallery-img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Product Management</h1>
        
        <!-- Product Form -->
        <div class="card mb-4">
            <div class="card-header">
                <h4 id="formTitle">Add New Product</h4>
            </div>
            <div class="card-body">
                <form id="productForm" enctype="multipart/form-data">
                    <input type="hidden" id="productId">
                    
                    <div class="row">
                        <!-- Text Input -->
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Product Name *</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        
                        <!-- Textarea -->
                        <div class="col-md-6 mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>
                        
                        <!-- Number Inputs -->
                        <div class="col-md-4 mb-3">
                            <label for="price" class="form-label">Price *</label>
                            <input type="number" class="form-control" id="price" name="price" step="0.01" min="0" required>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="quantity" class="form-label">Quantity *</label>
                            <input type="number" class="form-control" id="quantity" name="quantity" min="0" required>
                        </div>
                        
                        <!-- Date Input -->
                        <div class="col-md-4 mb-3">
                            <label for="manufacturing_date" class="form-label">Manufacturing Date</label>
                            <input type="date" class="form-control" id="manufacturing_date" name="manufacturing_date">
                        </div>
                        
                        <!-- Dropdown -->
                        <div class="col-md-4 mb-3">
                            <label for="category" class="form-label">Category *</label>
                            <select class="form-select" id="category" name="category" required>
                                <option value="">Select Category</option>
                                <option value="Electronics">Electronics</option>
                                <option value="Clothing">Clothing</option>
                                <option value="Books">Books</option>
                                <option value="Home">Home</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        
                        <!-- Radio Buttons -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Status *</label><br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" id="active" value="active" checked>
                                <label class="form-check-label" for="active">Active</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" id="inactive" value="inactive">
                                <label class="form-check-label" for="inactive">Inactive</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" id="discontinued" value="discontinued">
                                <label class="form-check-label" for="discontinued">Discontinued</label>
                            </div>
                        </div>
                        
                        <!-- Color Picker -->
                        <div class="col-md-4 mb-3">
                            <label for="color" class="form-label">Color</label>
                            <input type="color" class="form-control form-control-color" id="color" name="color" value="#563d7c">
                        </div>
                        
                        <!-- Checkboxes -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Features</label><br>
                            <div class="form-check">
                                <input class="form-check-input feature-checkbox" type="checkbox" name="features[]" value="Wireless" id="wireless">
                                <label class="form-check-label" for="wireless">Wireless</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input feature-checkbox" type="checkbox" name="features[]" value="Waterproof" id="waterproof">
                                <label class="form-check-label" for="waterproof">Waterproof</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input feature-checkbox" type="checkbox" name="features[]" value="Rechargeable" id="rechargeable">
                                <label class="form-check-label" for="rechargeable">Rechargeable</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input feature-checkbox" type="checkbox" name="features[]" value="Bluetooth" id="bluetooth">
                                <label class="form-check-label" for="bluetooth">Bluetooth</label>
                            </div>
                        </div>
                        
                        <!-- Switches -->
                        <div class="col-md-6 mb-3">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured">
                                <label class="form-check-label" for="is_featured">Featured Product</label>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="in_stock" name="in_stock" checked>
                                <label class="form-check-label" for="in_stock">In Stock</label>
                            </div>
                        </div>
                        
                        <!-- Image Upload -->
                        <div class="col-md-6 mb-3">
                            <label for="image" class="form-label">Main Image</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                            <div id="imagePreview" class="mt-2"></div>
                            <input type="hidden" id="imageUrl" name="image">
                        </div>
                        
                        <!-- Gallery Images Upload -->
                        <div class="col-md-6 mb-3">
                            <label for="gallery" class="form-label">Gallery Images</label>
                            <input type="file" class="form-control" id="gallery" name="gallery[]" multiple accept="image/*">
                            <div id="galleryPreview" class="gallery-preview"></div>
                            <input type="hidden" id="galleryUrls" name="gallery_images">
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary" id="submitBtn">Save Product</button>
                        <button type="button" class="btn btn-secondary" id="cancelBtn" style="display:none;">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Products Table -->
        <div class="card">
            <div class="card-header">
                <h4>Product List</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="productsTable">
                            <!-- Products will be loaded here via AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Bootstrap Color Picker JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/3.4.0/js/bootstrap-colorpicker.min.js"></script>
    
    <script>
        $(document).ready(function() {
            const baseUrl = '/api/products';
            let editingId = null;
            
            // Initialize color picker
            $('#color').colorpicker();
            
            // Image preview for main image
            $('#image').change(function(e) {
                const file = e.target.files[0];
                if (file) {
                    uploadImage(file, 'single');
                }
            });
            
            // Image preview for gallery
            $('#gallery').change(function(e) {
                const files = e.target.files;
                if (files.length > 0) {
                    uploadGalleryImages(files);
                }
            });
            
            // Load products on page load
            loadProducts();
            
            // Form submit
            $('#productForm').submit(function(e) {
                e.preventDefault();
                const formData = getFormData();
                
                if (editingId) {
                    updateProduct(editingId, formData);
                } else {
                    createProduct(formData);
                }
            });
            
            // Cancel edit
            $('#cancelBtn').click(function() {
                resetForm();
            });
            
            // Functions
            function loadProducts() {
                $.ajax({
                    url: baseUrl,
                    type: 'GET',
                    success: function(response) {
                        if (response.success) {
                            renderProducts(response.data);
                        }
                    },
                    error: function(xhr) {
                        showAlert('Error loading products', 'danger');
                    }
                });
            }
            
            function createProduct(formData) {
                $.ajax({
                    url: baseUrl,
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            showAlert('Product created successfully', 'success');
                            resetForm();
                            loadProducts();
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            displayErrors(errors);
                        } else {
                            showAlert('Error creating product', 'danger');
                        }
                    }
                });
            }
            
            function updateProduct(id, formData) {
                $.ajax({
                    url: `${baseUrl}/${id}`,
                    type: 'PUT',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            showAlert('Product updated successfully', 'success');
                            resetForm();
                            loadProducts();
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            displayErrors(errors);
                        } else {
                            showAlert('Error updating product', 'danger');
                        }
                    }
                });
            }
            
            function deleteProduct(id) {
                if (confirm('Are you sure you want to delete this product?')) {
                    $.ajax({
                        url: `${baseUrl}/${id}`,
                        type: 'DELETE',
                        success: function(response) {
                            if (response.success) {
                                showAlert('Product deleted successfully', 'success');
                                loadProducts();
                            }
                        },
                        error: function(xhr) {
                            showAlert('Error deleting product', 'danger');
                        }
                    });
                }
            }
            
            function editProduct(id) {
                $.ajax({
                    url: `${baseUrl}/${id}`,
                    type: 'GET',
                    success: function(response) {
                        if (response.success) {
                            const product = response.data;
                            editingId = product.id;
                            
                            // Fill form
                            $('#productId').val(product.id);
                            $('#name').val(product.name);
                            $('#description').val(product.description);
                            $('#price').val(product.price);
                            $('#quantity').val(product.quantity);
                            $('#category').val(product.category);
                            $(`input[name="status"][value="${product.status}"]`).prop('checked', true);
                            $('#manufacturing_date').val(product.manufacturing_date);
                            $('#color').val(product.color || '#563d7c');
                            $('#is_featured').prop('checked', product.is_featured);
                            $('#in_stock').prop('checked', product.in_stock);
                            $('#imageUrl').val(product.image);
                            
                            // Set features checkboxes
                            $('.feature-checkbox').prop('checked', false);
                            if (product.features && Array.isArray(product.features)) {
                                product.features.forEach(feature => {
                                    $(`.feature-checkbox[value="${feature}"]`).prop('checked', true);
                                });
                            }
                            
                            // Set gallery images
                            $('#galleryUrls').val(JSON.stringify(product.gallery_images || []));
                            renderGalleryPreview(product.gallery_images || []);
                            
                            // Set image preview
                            if (product.image) {
                                $('#imagePreview').html(`<img src="${product.image}" class="image-preview" alt="Product Image">`);
                            }
                            
                            // Update UI for editing
                            $('#formTitle').text('Edit Product');
                            $('#submitBtn').text('Update Product');
                            $('#cancelBtn').show();
                        }
                    },
                    error: function(xhr) {
                        showAlert('Error loading product', 'danger');
                    }
                });
            }
            
            function getFormData() {
                const formData = {
                    name: $('#name').val(),
                    description: $('#description').val(),
                    price: parseFloat($('#price').val()),
                    quantity: parseInt($('#quantity').val()),
                    category: $('#category').val(),
                    status: $('input[name="status"]:checked').val(),
                    manufacturing_date: $('#manufacturing_date').val(),
                    color: $('#color').val(),
                    is_featured: $('#is_featured').is(':checked'),
                    in_stock: $('#in_stock').is(':checked'),
                    image: $('#imageUrl').val(),
                    gallery_images: JSON.parse($('#galleryUrls').val() || '[]')
                };
                
                // Get selected features
                const features = [];
                $('.feature-checkbox:checked').each(function() {
                    features.push($(this).val());
                });
                formData.features = features;
                
                return formData;
            }
            
            function resetForm() {
                editingId = null;
                $('#productForm')[0].reset();
                $('#productId').val('');
                $('#imagePreview').empty();
                $('#galleryPreview').empty();
                $('#imageUrl').val('');
                $('#galleryUrls').val('[]');
                $('#color').val('#563d7c').colorpicker('setValue', '#563d7c');
                
                // Update UI
                $('#formTitle').text('Add New Product');
                $('#submitBtn').text('Save Product');
                $('#cancelBtn').hide();
            }
            
            function uploadImage(file, type) {
                const formData = new FormData();
                formData.append('image', file);
                
                $.ajax({
                    url: '/api/products/upload-image',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            $('#imageUrl').val(response.url);
                            $('#imagePreview').html(`<img src="${response.url}" class="image-preview" alt="Uploaded Image">`);
                        }
                    },
                    error: function(xhr) {
                        showAlert('Error uploading image', 'danger');
                    }
                });
            }
            
            function uploadGalleryImages(files) {
                const formData = new FormData();
                for (let i = 0; i < files.length; i++) {
                    formData.append('images[]', files[i]);
                }
                
                $.ajax({
                    url: '/api/products/upload-gallery',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            const currentGallery = JSON.parse($('#galleryUrls').val() || '[]');
                            const newGallery = [...currentGallery, ...response.images];
                            $('#galleryUrls').val(JSON.stringify(newGallery));
                            renderGalleryPreview(newGallery);
                        }
                    },
                    error: function(xhr) {
                        showAlert('Error uploading gallery images', 'danger');
                    }
                });
            }
            
            function renderGalleryPreview(images) {
                $('#galleryPreview').empty();
                images.forEach(image => {
                    $('#galleryPreview').append(`<img src="${image}" class="gallery-img" alt="Gallery Image">`);
                });
            }
            
            function renderProducts(products) {
                const tbody = $('#productsTable');
                tbody.empty();
                
                if (products.length === 0) {
                    tbody.html('<tr><td colspan="8" class="text-center">No products found</td></tr>');
                    return;
                }
                
                products.forEach(product => {
                    const row = `
                        <tr>
                            <td>${product.id}</td>
                            <td>
                                ${product.image ? `<img src="${product.image}" alt="Product Image" style="width: 50px; height: 50px; object-fit: cover;">` : 'No Image'}
                            </td>
                            <td>${product.name}</td>
                            <td>${product.category}</td>
                            <td>$${parseFloat(product.price).toFixed(2)}</td>
                            <td>${product.quantity}</td>
                            <td>
                                <span class="badge ${getStatusBadgeClass(product.status)}">
                                    ${product.status}
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-warning" onclick="window.editProduct(${product.id})">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger" onclick="window.deleteProduct(${product.id})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                    tbody.append(row);
                });
            }
            
            function getStatusBadgeClass(status) {
                switch (status) {
                    case 'active': return 'bg-success';
                    case 'inactive': return 'bg-warning';
                    case 'discontinued': return 'bg-danger';
                    default: return 'bg-secondary';
                }
            }
            
            function displayErrors(errors) {
                for (const field in errors) {
                    const errorMessage = errors[field][0];
                    showAlert(`${field}: ${errorMessage}`, 'danger');
                }
            }
            
            function showAlert(message, type) {
                const alert = `<div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>`;
                
                $('.container').prepend(alert);
                
                // Remove alert after 5 seconds
                setTimeout(() => {
                    $('.alert').alert('close');
                }, 5000);
            }
            
            // Make functions available globally for onclick events
            window.editProduct = editProduct;
            window.deleteProduct = deleteProduct;
        });
    </script>
</body>
</html>