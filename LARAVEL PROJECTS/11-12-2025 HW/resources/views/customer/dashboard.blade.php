<!DOCTYPE html>
<html>
<head>
    <title>Customer Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-success">
        <div class="container">
            <a class="navbar-brand" href="#">Our Store</a>
            <div class="navbar-nav ms-auto">
                <span class="navbar-text me-3">
                    Welcome, {{ session('user_name') }}
                </span>
                <a href="/logout" class="btn btn-danger">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h2 class="text-center mb-4">Available Products</h2>
        
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="row">
            @foreach($products as $product)
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <h6 class="card-subtitle mb-2 text-muted">{{ $product->category->name }}</h6>
                        <p class="card-text">{{ $product->description ?: 'No description available' }}</p>
                        <h4 class="text-primary">${{ number_format($product->price, 2) }}</h4>
                    </div>
                    <div class="card-footer">
                        <small class="text-muted">Added: {{ $product->created_at->format('M d, Y') }}</small>
                    </div>
                </div>
            </div>
            @endforeach
            
            @if($products->isEmpty())
            <div class="col-12">
                <div class="alert alert-info">No products available at the moment.</div>
            </div>
            @endif
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>