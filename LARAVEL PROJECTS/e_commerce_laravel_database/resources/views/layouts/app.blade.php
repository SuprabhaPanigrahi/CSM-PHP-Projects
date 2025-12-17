<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Ecommerce</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            padding-top: 56px;
        }

        .navbar {
            box-shadow: 0 2px 4px rgba(0, 0, 0, .1);
        }

        .product-card {
            transition: transform 0.3s;
        }

        .product-card:hover {
            transform: translateY(-5px);
        }

        .cart-badge {
            position: absolute;
            top: -5px;
            right: -5px;
        }

        .pagination {
            justify-content: center;
        }

        .pagination .page-link {
            color: #0d6efd;
        }

        .pagination .page-item.active .page-link {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top">
        <div class="container">
            <a class="navbar-brand" href="/">Ecommerce</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="/">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="/products">Products</a></li>
                    <li class="nav-item"><a class="nav-link" href="/cart">Cart</a></li>
                </ul>

                <ul class="navbar-nav">
                    @php
                    use App\Http\Controllers\AuthController;
                    $cartCount = count(session('cart', []));
                    @endphp

                    @if(AuthController::check())
                    <li class="nav-item position-relative">
                        <a class="nav-link" href="/cart">
                            <i class="fas fa-shopping-cart"></i>
                            @if($cartCount > 0)
                            <span class="badge bg-danger cart-badge">{{ $cartCount }}</span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            {{ session('user_name') }}
                        </a>
                        <ul class="dropdown-menu">
                            @if(AuthController::isAdmin())
                            <li><a class="dropdown-item" href="/admin/dashboard">Admin Dashboard</a></li>
                            @endif
                            <li><a class="dropdown-item" href="/logout">Logout</a></li>
                        </ul>
                    </li>
                    @else
                    <li class="nav-item"><a class="nav-link" href="/login">Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="/register">Register</a></li>
                    @endif
                </ul>

                <form class="d-flex ms-2" action="/products" method="GET">
                    <input class="form-control me-2" type="search" name="search" placeholder="Search products...">
                    <button class="btn btn-outline-primary" type="submit">Search</button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="py-4">
        <div class="container">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>Ecommerce</h5>
                    <p>Your one-stop shop for everything.</p>
                </div>
                <div class="col-md-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="/" class="text-white">Home</a></li>
                        <li><a href="/products" class="text-white">Products</a></li>
                        <li><a href="/cart" class="text-white">Cart</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Contact</h5>
                    <p>Email: support@ecommerce.com</p>
                    <p>Phone: +1 234 567 890</p>
                </div>
            </div>
            <hr class="bg-white">
            <p class="text-center mb-0">&copy; 2024 Ecommerce. All rights reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>

</html>