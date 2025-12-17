<header>
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm py-3">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="{{ route('home') }}">
                <i class="bi bi-shop me-2"></i>ShopEasy
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item mx-2">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active fw-bold' : '' }}" 
                           href="{{ route('home') }}">
                            <i class="bi bi-house-door me-1"></i> Home
                        </a>
                    </li>
                    <li class="nav-item dropdown mx-2">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-grid-3x3-gap me-1"></i> Categories
                        </a>
                        <ul class="dropdown-menu">
                            @foreach($categories ?? [] as $category)
                            <li>
                                <a class="dropdown-item" href="{{ route('category.show', $category) }}">
                                    @php
                                        $icons = [
                                            'Electronics' => 'bi-phone text-primary',
                                            'Fashion' => 'bi-tshirt text-success', 
                                            'Consumables' => 'bi-cup text-warning',
                                            'Home & Kitchen' => 'bi-house-door text-info',
                                            'Books' => 'bi-book text-secondary',
                                            'Sports' => 'bi-bicycle text-danger'
                                        ];
                                        $colors = [
                                            'Electronics' => 'primary',
                                            'Fashion' => 'success',
                                            'Consumables' => 'warning',
                                            'Home & Kitchen' => 'info',
                                            'Books' => 'secondary',
                                            'Sports' => 'danger'
                                        ];
                                        $icon = $icons[$category] ?? 'bi-tag';
                                        $color = $colors[$category] ?? 'primary';
                                    @endphp
                                    <i class="bi {{ str_replace('text-'.$color, '', $icon) }} me-2 text-{{ $color }}"></i>
                                    {{ $category }}
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </li>
                    <li class="nav-item mx-2">
                        <a class="nav-link {{ request()->routeIs('products.*') ? 'active fw-bold' : '' }}" 
                           href="{{ route('products.index') }}">
                            <i class="bi bi-tags me-1"></i> Products
                        </a>
                    </li>
                </ul>
                
                <!-- Search Bar -->
                <form action="{{ request()->routeIs('category.show') ? route('category.show', request()->route('categoryName')) : route('home') }}" 
                      method="GET" class="d-flex me-3 search-form">
                    <div class="input-group">
                        <input type="text" 
                               name="search" 
                               class="form-control" 
                               placeholder="Search products..." 
                               value="{{ request()->get('search') }}"
                               style="min-width: 250px;">
                        <button class="btn btn-outline-primary" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                        @if(request()->has('search'))
                        <a href="{{ request()->url() }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x"></i>
                        </a>
                        @endif
                    </div>
                </form>
                
                <div class="d-flex align-items-center">
                    <!-- Cart Icon with Badge -->
                    <div class="position-relative me-4">
                        <a href="{{ route('cart.index') }}" class="btn btn-outline-primary position-relative">
                            <i class="bi bi-cart3 fs-5"></i>
                            <span id="cart-count" class="cart-badge badge bg-danger rounded-pill" 
                                  style="display: none;">0</span>
                        </a>
                    </div>
                    
                    @auth
                        <!-- User dropdown -->
                        <div class="dropdown">
                            <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" 
                               data-bs-toggle="dropdown">
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" 
                                     style="width: 40px; height: 40px;">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </div>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i> Profile</a></li>
                                <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i> Settings</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#"><i class="bi bi-box-arrow-right me-2"></i> Logout</a></li>
                            </ul>
                        </div>
                    @else
                        <!-- Login/Register buttons -->
                        <div class="d-flex gap-2">
                            <a href="#" class="btn btn-outline-primary">
                                <i class="bi bi-box-arrow-in-right me-1"></i> Login
                            </a>
                            <a href="#" class="btn btn-primary">
                                <i class="bi bi-person-plus me-1"></i> Register
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>
</header>

<style>
    .search-form .input-group {
        min-width: 300px;
    }
    
    @media (max-width: 992px) {
        .search-form {
            order: 3;
            width: 100%;
            margin-top: 1rem;
        }
        .search-form .input-group {
            width: 100%;
            min-width: auto;
        }
    }
</style>