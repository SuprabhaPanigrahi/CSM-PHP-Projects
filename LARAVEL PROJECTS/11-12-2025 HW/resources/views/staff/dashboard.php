<!DOCTYPE html>
<html>
<head>
    <title>Staff Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">Staff Panel</a>
            <div class="navbar-nav ms-auto">
                <span class="navbar-text me-3">
                    Welcome, <?php echo session('user_name'); ?> (Staff)
                </span>
                <a href="/logout" class="btn btn-danger">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <?php if(session('success')): ?>
            <div class="alert alert-success"><?php echo session('success'); ?></div>
        <?php endif; ?>

        <div class="row">
            <!-- Create Product -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Create Product</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="/staff/products">
                            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
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
                                    <?php foreach($categories as $category): ?>
                                        <option value="<?php echo $category->id; ?>"><?php echo $category->name; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Create Product</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- View Products -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4>All Products</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Category</th>
                                        <th>Price</th>
                                        <th>Created At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($products as $product): ?>
                                    <tr>
                                        <td><?php echo $product->name; ?></td>
                                        <td><?php echo $product->category->name; ?></td>
                                        <td>$<?php echo number_format($product->price, 2); ?></td>
                                        <td><?php echo date('Y-m-d', strtotime($product->created_at)); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                    
                                    <?php if(count($products) == 0): ?>
                                    <tr>
                                        <td colspan="4" class="text-center">No products found</td>
                                    </tr>
                                    <?php endif; ?>
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