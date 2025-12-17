<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Admin Panel</a>
            <div class="navbar-nav ms-auto">
                <span class="navbar-text me-3">
                    Welcome, {{ session('user_name') }} (Admin)
                </span>
                <a href="/logout" class="btn btn-danger">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="row">
            <!-- Category Management -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Category Management</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="/admin/categories">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Category Name</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Add Category</button>
                        </form>

                        <hr>
                        <h5>Existing Categories</h5>
                        <ul class="list-group">
                            @foreach($categories as $category)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $category->name }}
                                <div>
                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editCategory{{ $category->id }}">Edit</button>
                                    <form action="/admin/categories/{{ $category->id }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </div>
                            </li>

                            <!-- Edit Category Modal -->
                            <div class="modal fade" id="editCategory{{ $category->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form method="POST" action="/admin/categories/{{ $category->id }}">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Category</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="name" class="form-label">Category Name</label>
                                                    <input type="text" class="form-control" id="name" name="name" value="{{ $category->name }}" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Update</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Product Management -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Product Management</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="/admin/products">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Product Name</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="2"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="price" class="form-label">Price</label>
                                <input type="number" step="0.01" class="form-control" id="price" name="price" required>
                            </div>
                            <div class="mb-3">
                                <label for="category_id" class="form-label">Category</label>
                                <select class="form-control" id="category_id" name="category_id" required>
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Add Product</button>
                        </form>

                        <hr>
                        <h5>Existing Products</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Category</th>
                                        <th>Price</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($products as $product)
                                    <tr>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ $product->category->name }}</td>
                                        <td>${{ number_format($product->price, 2) }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editProduct{{ $product->id }}">Edit</button>
                                            <form action="/admin/products/{{ $product->id }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                            </form>
                                        </td>
                                    </tr>

                                    <!-- Edit Product Modal -->
                                    <div class="modal fade" id="editProduct{{ $product->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form method="POST" action="/admin/products/{{ $product->id }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Edit Product</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label for="name" class="form-label">Product Name</label>
                                                            <input type="text" class="form-control" name="name" value="{{ $product->name }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="description" class="form-label">Description</label>
                                                            <textarea class="form-control" name="description" rows="2">{{ $product->description }}</textarea>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="price" class="form-label">Price</label>
                                                            <input type="number" step="0.01" class="form-control" name="price" value="{{ $product->price }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="category_id" class="form-label">Category</label>
                                                            <select class="form-control" name="category_id" required>
                                                                @foreach($categories as $category)
                                                                    <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                                                        {{ $category->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary">Update</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>