<!DOCTYPE html>
<html>
<head>
    <title>Products</title>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        
        .container {
            max-width: 1000px;
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            margin-top: 20px;
            margin-bottom: 20px;
        }
        
        h2 {
            color: #333;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        
        h4 {
            color: #555;
            margin-top: 30px;
            margin-bottom: 15px;
        }
        
        .card {
            border: 1px solid #dee2e6;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .card h5 {
            color: #007bff;
            margin-bottom: 15px;
        }
        
        .form-control {
            border-radius: 3px;
            border: 1px solid #ced4da;
            padding: 8px 12px;
        }
        
        .form-control:focus {
            border-color: #80bdff;
            box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
        }
        
        .form-label {
            font-weight: 500;
            margin-bottom: 5px;
            color: #495057;
        }
        
        .btn-info {
            background-color: #17a2b8;
            border-color: #17a2b8;
            color: white;
            font-weight: 500;
        }
        
        .btn-info:hover {
            background-color: #138496;
            border-color: #117a8b;
        }
        
        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
            color: white;
            font-weight: 500;
        }
        
        .btn-danger:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }
        
        .btn-sm {
            padding: 5px 10px;
            font-size: 14px;
            margin-right: 5px;
        }
        
        .table {
            margin-bottom: 0;
        }
        
        .table thead th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            color: #495057;
            font-weight: 600;
        }
        
        .table tbody tr:hover {
            background-color: #f8f9fa;
        }
        
        .table td, .table th {
            padding: 12px 15px;
            vertical-align: middle;
        }
        
        #saveBtn {
            width: 100%;
            margin-top: 10px;
        }
        
        .row {
            margin-bottom: 15px;
        }
        
        .row:last-child {
            margin-bottom: 0;
        }
        
        .image-preview {
            max-width: 100px;
            max-height: 100px;
            object-fit: cover;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 3px;
            margin-top: 5px;
            display: none;
        }
        
        .image-preview.show {
            display: block;
        }
        
        .product-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 4px;
        }
        
        @media (max-width: 768px) {
            .container {
                padding: 15px;
                margin-top: 10px;
            }
            
            .btn-sm {
                display: block;
                width: 100%;
                margin-bottom: 5px;
            }
        }
    </style>
</head>
<body class="bg-light">

<div class="container mt-4">
    <h2 class="mb-3">Product Management</h2>

    {{-- FORM FOR ADD & UPDATE --}}
    <div class="card p-3 mb-4">
        <h5 id="formTitle">Add Product</h5>
        <form id="productForm" enctype="multipart/form-data">
            <input type="hidden" id="edit_id">

            <div class="row">
                <div class="col-md-4">
                    <label class="form-label">Name</label>
                    <input type="text" id="name" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Price</label>
                    <input type="number" id="price" class="form-control" step="0.01" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Category</label>
                    <select id="category_id" class="form-control" required>
                        <option value="">Select Category</option>
                    </select>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-4">
                    <label class="form-label">Product Image</label>
                    <input type="file" id="image" class="form-control" accept="image/*">
                    <img id="imagePreview" class="image-preview" src="" alt="Image Preview">
                    <input type="hidden" id="existing_image">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-info w-100 mt-4" id="saveBtn">Add</button>
                </div>
            </div>
        </form>
    </div>

    {{-- PRODUCT LIST --}}
    <h4>Product List</h4>
    
    <table class="table table-bordered" id="productTable">
        <thead class="table-light">
            <tr>
                <th width="50">ID</th>
                <th>Image</th>
                <th>Name</th>
                <th>Category</th>
                <th width="100">Price</th>
                <th width="140">Action</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){
    let API = "http://127.0.0.1:8080/api";  // FULL API PATH USED
    
    loadCategories();
    loadProducts();  

    //-- LOAD CATEGORIES FOR DROPDOWN
    function loadCategories(){
        $.get(`${API}/categories`, function(res){
            let options = '<option value="">Select Category</option>';
            $.each(res, function(i, category){
                options += `<option value="${category.id}">${category.name}</option>`;
            });
            $("#category_id").html(options);
        });
    }

    //-- LOAD PRODUCT LIST
    function loadProducts(){
        $.get(`${API}/products`, function(res){
            let rows = "";
            $.each(res, function(i,item){
                let imageUrl = item.image ? `${API}/uploads/${item.image}` : 'https://via.placeholder.com/80';
                rows += `
                    <tr>
                        <td>${item.id}</td>
                        <td><img src="${imageUrl}" class="product-image" alt="${item.name}"></td>
                        <td>${item.name}</td>
                        <td>${item.category_name || 'N/A'}</td>
                        <td>$${parseFloat(item.price).toFixed(2)}</td>
                        <td>
                            <button class="btn btn-info btn-sm edit" 
                                    data-id="${item.id}" 
                                    data-name="${item.name}" 
                                    data-price="${item.price}"
                                    data-category_id="${item.category_id}"
                                    data-description="${item.description || ''}"
                                    data-image="${item.image || ''}">
                                Edit
                            </button>
                            <button class="btn btn-danger btn-sm delete" data-id="${item.id}">Delete</button>
                        </td>
                    </tr>
                `;
            });
            $("#productTable tbody").html(rows);
        });
    }

    // CREATE OR UPDATE
    $("#productForm").submit(function(e){
        e.preventDefault();
    
        let id = $("#edit_id").val();
        let name = $("#name").val();
        let price = $("#price").val();
        let category_id = $("#category_id").val();
        let description = $("#description").val();
        let existing_image = $("#existing_image").val();
        
        let formData = new FormData();
        formData.append('name', name);
        formData.append('price', price);
        formData.append('category_id', category_id);
        formData.append('description', description);
        
        // Add image if new one is selected
        let imageFile = $("#image")[0].files[0];
        if (imageFile) {
            formData.append('image', imageFile);
        }
        
        if (id) {
            formData.append('_method', 'PUT');
            if (existing_image) {
                formData.append('existing_image', existing_image);
            }
            
            $.ajax({
                url: `${API}/product/${id}`,
                method: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(){
                    resetForm();
                    loadProducts();
                },
                error: function(xhr, status, error){
                    console.error('Error:', error);
                }
            });
        } else {
            $.ajax({
                url: `${API}/product/create`,
                method: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(){
                    resetForm();
                    loadProducts();
                },
                error: function(xhr, status, error){
                    console.error('Error:', error);
                }
            });
        }
    });
    
    // EDIT BUTTON CLICK
    $(document).on('click','.edit',function(){
        $("#edit_id").val($(this).data('id'));
        $("#name").val($(this).data('name'));
        $("#price").val($(this).data('price'));
        $("#category_id").val($(this).data('category_id'));
        $("#description").val($(this).data('description'));
        $("#existing_image").val($(this).data('image'));
        
        // Show existing image preview
        if($(this).data('image')) {
            $("#imagePreview").attr('src', `${API}/uploads/${$(this).data('image')}`).addClass('show');
        } else {
            $("#imagePreview").removeClass('show');
        }

        $("#formTitle").text("Update Product");
        $("#saveBtn").text("Update");
    });

    // DELETE
    $(document).on('click','.delete',function(){
        let id = $(this).data('id');

        $.ajax({
            url: `${API}/product/${id}`,
            method: "DELETE",
            success:function(){
                loadProducts();
            }
        });
    });

    // IMAGE PREVIEW
    $("#image").change(function(){
        let file = this.files[0];
        if (file) {
            let reader = new FileReader();
            reader.onload = function(e){
                $("#imagePreview").attr('src', e.target.result).addClass('show');
            }
            reader.readAsDataURL(file);
        } else {
            $("#imagePreview").removeClass('show');
        }
    });

    // RESET FORM
    function resetForm(){
        $("#productForm")[0].reset();
        $("#formTitle").text("Add Product");
        $("#saveBtn").text("Add");
        $("#edit_id").val('');
        $("#existing_image").val('');
        $("#imagePreview").removeClass('show');
        loadCategories();
    }
});
</script>
</body>
</html>