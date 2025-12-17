@extends('layouts.app')

@section('title', 'Home - ShopEasy')

@section('content')
<div class="container mt-4">
    <!-- Hero Section -->
    <div class="hero-section mb-5 p-5 rounded-4" 
         style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-5 fw-bold mb-3">Welcome to ShopEasy</h1>
                <p class="lead mb-4">Discover amazing products at unbeatable prices. Quality guaranteed with fast delivery.</p>
                <a href="#products" class="btn btn-light btn-lg px-4">
                    <i class="bi bi-arrow-right me-2"></i> Shop Now
                </a>
            </div>
            <div class="col-lg-6">
                <img src="https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" 
                     class="img-fluid rounded-4 shadow" alt="Shopping">
            </div>
        </div>
    </div>

    <!-- Search Results Info -->
    @if(request()->has('search') && request()->get('search'))
    <div class="alert alert-info mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <i class="bi bi-search me-2"></i>
                Search results for: "<strong>{{ request()->get('search') }}</strong>"
                <span class="ms-3">Found {{ $totalProducts }} products</span>
            </div>
            <a href="{{ route('home') }}" class="btn btn-sm btn-outline-info">Clear Search</a>
        </div>
    </div>
    @endif

    <!-- Categories Section -->
    <section class="mb-5">
        <h2 class="text-center mb-4">Shop By Category</h2>
        <div class="row g-4">
            @foreach($categories as $category)
            <div class="col-md-4 col-lg-2">
                <a href="{{ route('category.show', $category) }}" class="text-decoration-none">
                    <div class="category-card text-center p-4 rounded-3 bg-white shadow-sm hover-lift">
                        <div class="mb-3">
                            @php
                                $icons = [
                                    'Electronics' => 'bi-phone text-primary',
                                    'Fashion' => 'bi-tshirt text-success', 
                                    'Consumables' => 'bi-cup text-warning',
                                    'Home & Kitchen' => 'bi-house-door text-info',
                                    'Books' => 'bi-book text-secondary',
                                    'Sports' => 'bi-bicycle text-danger'
                                ];
                                $icon = $icons[$category] ?? 'bi-tag';
                                $colors = [
                                    'Electronics' => 'primary',
                                    'Fashion' => 'success',
                                    'Consumables' => 'warning',
                                    'Home & Kitchen' => 'info',
                                    'Books' => 'secondary',
                                    'Sports' => 'danger'
                                ];
                                $color = $colors[$category] ?? 'primary';
                            @endphp
                            <div class="rounded-circle bg-{{ $color }} bg-opacity-10 d-inline-flex align-items-center justify-content-center" 
                                 style="width: 80px; height: 80px;">
                                <i class="bi {{ $icon }} fs-3"></i>
                            </div>
                        </div>
                        <h5 class="fw-bold mb-0">{{ $category }}</h5>
                        <p class="text-muted small mt-1">
                            @php
                                $count = count(array_filter($products, function($product) use ($category) {
                                    return $product['category'] == $category;
                                }));
                            @endphp
                            {{ $count }} products
                        </p>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </section>

    <!-- Products Section -->
    <section id="products" class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Featured Products</h2>
            <div class="d-flex align-items-center">
                @if(request()->has('search') && request()->get('search'))
                <div class="me-3">
                    <span class="text-muted">{{ $totalProducts }} results found</span>
                </div>
                @endif
                <a href="{{ route('products.index') }}" class="btn btn-outline-primary">
                    View All <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
        
        @if(count($paginatedProducts) > 0)
        <div class="row">
            @foreach($paginatedProducts as $product)
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="card h-100 shadow-sm product-card">
                    <div class="card-img-top" style="height: 200px; overflow: hidden;">
                        @if(isset($product['image']) && $product['image'] != 'default.jpg')
                        <img src="{{ asset('storage/' . $product['image']) }}"
                            class="w-100 h-100 object-fit-cover"
                            alt="{{ $product['name'] }}"
                            style="object-fit: cover;">
                        @else
                        <div class="w-100 h-100 bg-light d-flex align-items-center justify-content-center">
                            <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                        </div>
                        @endif
                    </div>
                    <div class="card-body d-flex flex-column">
                        @php
                            $colors = [
                                'Electronics' => 'primary',
                                'Fashion' => 'success',
                                'Consumables' => 'warning',
                                'Home & Kitchen' => 'info',
                                'Books' => 'secondary',
                                'Sports' => 'danger'
                            ];
                            $color = $colors[$product['category']] ?? 'primary';
                        @endphp
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="badge bg-{{ $color }}">
                                {{ $product['category'] }}
                            </span>
                            <span class="text-success fw-bold">${{ number_format($product['price'], 2) }}</span>
                        </div>
                        <h5 class="card-title">{{ $product['name'] }}</h5>
                        <p class="card-text flex-grow-1 text-muted">
                            <small>{{ Str::limit($product['description'], 80) }}</small>
                        </p>
                        <div class="d-flex justify-content-between align-items-center mt-auto">
                            <button class="btn btn-outline-primary btn-sm px-3">
                                <i class="bi bi-eye me-1"></i> View
                            </button>
                            <button class="btn btn-primary btn-sm px-3 add-to-cart"
                                data-product-id="{{ $product['id'] }}"
                                data-product-name="{{ $product['name'] }}"
                                data-product-price="{{ $product['price'] }}"
                                data-product-image="{{ asset('storage/' . $product['image']) }}">
                                <i class="bi bi-cart-plus me-1"></i> Add to Cart
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        @if($totalPages > 1)
        <nav aria-label="Page navigation" class="mt-5">
            <ul class="pagination justify-content-center">
                <!-- Previous Page Link -->
                <li class="page-item {{ $currentPage == 1 ? 'disabled' : '' }}">
                    <a class="page-link" 
                       href="{{ request()->fullUrlWithQuery(['page' => $currentPage - 1]) }}"
                       aria-label="Previous">
                        <i class="bi bi-chevron-left"></i>
                    </a>
                </li>
                
                <!-- Page Numbers -->
                @for($i = 1; $i <= $totalPages; $i++)
                    @if($i == 1 || $i == $totalPages || ($i >= $currentPage - 2 && $i <= $currentPage + 2))
                    <li class="page-item {{ $currentPage == $i ? 'active' : '' }}">
                        <a class="page-link" 
                           href="{{ request()->fullUrlWithQuery(['page' => $i]) }}">
                            {{ $i }}
                        </a>
                    </li>
                    @elseif($i == $currentPage - 3 || $i == $currentPage + 3)
                    <li class="page-item disabled">
                        <span class="page-link">...</span>
                    </li>
                    @endif
                @endfor
                
                <!-- Next Page Link -->
                <li class="page-item {{ $currentPage == $totalPages ? 'disabled' : '' }}">
                    <a class="page-link" 
                       href="{{ request()->fullUrlWithQuery(['page' => $currentPage + 1]) }}"
                       aria-label="Next">
                        <i class="bi bi-chevron-right"></i>
                    </a>
                </li>
            </ul>
            
            <!-- Page Info -->
            <div class="text-center mt-2 text-muted">
                Showing {{ (($currentPage - 1) * 8) + 1 }} to {{ min($currentPage * 8, $totalProducts) }} of {{ $totalProducts }} products
            </div>
        </nav>
        @endif
        
        @else
        <div class="alert alert-warning text-center py-5">
            @if(request()->has('search') && request()->get('search'))
            <i class="bi bi-search display-4 d-block mb-3"></i>
            <h4>No products found</h4>
            <p class="mb-3">No products match your search for "<strong>{{ request()->get('search') }}</strong>"</p>
            <a href="{{ route('home') }}" class="btn btn-primary">
                <i class="bi bi-house me-2"></i> Back to Home
            </a>
            @else
            <i class="bi bi-exclamation-triangle display-4 d-block mb-3"></i>
            <h4>No products available</h4>
            <p class="mb-3">Products will appear here once added.</p>
            <a href="{{ route('products.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Add Products
            </a>
            @endif
        </div>
        @endif
    </section>
</div>

<style>
    .hover-lift {
        transition: all 0.3s ease;
    }
    
    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
    }
    
    .category-card {
        border: 1px solid #e5e7eb;
    }
    
    .hero-section {
        overflow: hidden;
    }
    
    .page-item.active .page-link {
        background-color: #6366f1;
        border-color: #6366f1;
    }
    
    .page-link {
        color: #6366f1;
    }
    
    .page-link:hover {
        color: #4f46e5;
    }
</style>
@endsection