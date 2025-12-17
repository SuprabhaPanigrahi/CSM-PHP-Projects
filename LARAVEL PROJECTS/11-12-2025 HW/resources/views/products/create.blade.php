<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Create Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">

        @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Validation Errors:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <h2>Add New Product</h2>
        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Product Name -->
            <div class="mb-3">
                <label class="form-label">Product Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <!-- Description -->
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="4"></textarea>
            </div>

            <!-- Price -->
            <div class="mb-3">
                <label class="form-label">Price ($)</label>
                <input type="number" name="price" class="form-control" step="0.01">
            </div>

            <!-- Dropdown Category -->
            <div class="mb-3">
                <label class="form-label">Category</label>
                <select name="category" class="form-select">
                    <option value="">Select Category</option>
                    <option value="electronics">Electronics</option>
                    <option value="fashion">Fashion</option>
                    <option value="grocery">Grocery</option>
                </select>
            </div>

            <!-- Radio Buttons -->
            <div class="mb-3">
                <label class="form-label">Availability</label><br>

                <label>
                    <input type="radio" name="availability" value="in_stock"> In Stock
                </label>
                <label class="ms-3">
                    <input type="radio" name="availability" value="out_of_stock"> Out of Stock
                </label>
            </div>

            <!-- Multiple Checkboxes -->
            <div class="mb-3">
                <label class="form-label">Tags</label><br>

                <label><input type="checkbox" name="tags[]" value="new"> New</label><br>
                <label><input type="checkbox" name="tags[]" value="trending"> Trending</label><br>
                <label><input type="checkbox" name="tags[]" value="best_seller"> Best Seller</label><br>
                <label><input type="checkbox" name="tags[]" value="limited_stock"> Limited Stock</label><br>
            </div>

            <!-- Multiple File Upload -->
            <div class="mb-3">
                <label class="form-label">Upload Images</label>
                <input type="file" name="images[]" class="form-control" multiple>
            </div>

            <!-- Submit -->
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</body>
</html>