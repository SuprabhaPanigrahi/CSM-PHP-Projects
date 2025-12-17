@extends('layouts.app')

@section('title', $categoryName . ' - ShopEasy')

@section('content')
<div class="container mt-4">
    <!-- Category Header -->
    <div class="row align-items-center mb-5">
        <div class="col-md-8">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $categoryName }}</li>
                </ol>
            </nav>
            <h1 class="fw-bold mb-3">
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
                    $icon = $icons[$categoryName] ?? 'bi-tag';
                    $color = $colors[$categoryName] ?? 'primary';
                @endphp
                <i class="bi {{ str_replace('text-'.$color, '', $icon) }} text-{{ $color }} me-2"></i>
                {{ $categoryName }}
            </h1>
            <p class="text-muted">Browse our collection of {{ $categoryName }} products</p>
        </div>
        <div class="col-md-4 text-md-end">
            <div class="badge bg-{{ $color }} fs-6 p-3">
                {{ $totalProducts }} Products
            </div>
        </div>
    </div>

    <!-- Search Results Info -->
    @if(request()->has('search') && request()->get('search'))
    <div class="alert alert-info mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <i class="bi bi-search me-2"></i>
                Search results in {{ $categoryName }} for: "<strong>{{ request()->get('search') }}</strong>"
                <span class="ms-3">Found {{ $totalProducts }} products</span>
            </div>
            <a href="{{ route('category.show', $categoryName) }}" class="btn btn-sm btn-outline-info">Clear Search</a>
        </div>
    </div>
    @endif

    <!-- Category Products -->
    <div class="row">
        <!-- Sidebar Filters -->
        <div class="col-lg-3 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">Categories</h5>
                    <div class="list-group list-group-flush">
                        @foreach($categories as $cat)
                        @php
                            $catColors = [
                                'Electronics' => 'primary',
                                'Fashion' => 'success',
                                'Consumables' => 'warning',
                                'Home & Kitchen' => 'info',
                                'Books' => 'secondary',
                                'Sports' => 'danger'
                            ];
                            $catColor = $catColors[$cat] ?? 'primary';
                        @endphp
                        <a href="{{ route('category.show', $cat) }}" 
                           class="list-group-item list-group-item-action d-flex align-items-center 
                                  {{ $categoryName == $cat ? 'active' : '' }}">
                            <i class="bi {{ str_replace('text-'.$catColor, '', $icons[$cat] ?? 'bi-tag') }} me-3 text-{{ $catColor }}"></i>
                            {{ $cat }}
                            <span class="badge bg-{{ $catColor }} rounded-pill ms-auto">
                                @php
                                    $count = count(array_filter($products, function($product) use ($cat) {
                                        return $product['category'] == $cat;
                                    }));
                                @endphp
                                {{ $count }}
                            </span>
                        </a>
                        @endforeach
                    </div>
                    
                    <!-- Category Search -->
                    <div class="mt-4">
                        <h6 class="mb-3">Search in {{ $categoryName }}</h6>
                        <form action="{{ route('category.show', $categoryName) }}" method="GET">
                            <div class="input-group mb-3">
                                <input type="text" 
                                       name="search" 
                                       class="form-control form-control-sm" 
                                       placeholder="Search..." 
                                       value="{{ request()->get('search') }}">
                                <button class="btn btn-outline-{{ $color }} btn-sm" type="submit">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="col-lg-9">
            @if(count($paginatedProducts) > 0)
            <div class="row">
                @foreach($paginatedProducts as $product)
                <div class="col-lg-4 col-md-6 mb-4">
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
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="badge bg-{{ $color }}">
                                    {{ $product['category'] }}
                                </span>
                                <span class="text-success fw-bold">${{ number_format($product['price'], 2) }}</span>
                            </div>
                            <h5 class="card-title">{{ $product['name'] }}</h5>
                            <p class="card-text flex-grow-1 text-muted">
                                <small>{{ Str::limit($product['description'], 60) }}</small>
                            </p>
                            <div class="d-flex justify-content-between align-items-center mt-auto">
                                <a href="#" class="btn btn-outline-{{ $color }} btn-sm px-3">
                                    <i class="bi bi-eye me-1"></i> View
                                </a>
                                <button class="btn btn-{{ $color }} btn-sm px-3 add-to-cart"
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
            <div class="text-center py-5">
                @if(request()->has('search') && request()->get('search'))
                <div class="mb-4">
                    <i class="bi bi-search display-4 d-block mb-3"></i>
                    <h4>No products found</h4>
                    <p class="text-muted mb-4">No {{ strtolower($categoryName) }} products match your search for "<strong>{{ request()->get('search') }}</strong>"</p>
                    <a href="{{ route('category.show', $categoryName) }}" class="btn btn-{{ $color }}">
                        <i class="bi bi-arrow-left me-2"></i> Back to {{ $categoryName }}
                    </a>
                </div>
                @else
                <div class="mb-4">
                    @php
                        $catIcons = [
                            'Electronics' => 'bi-phone',
                            'Fashion' => 'bi-tshirt', 
                            'Consumables' => 'bi-cup',
                            'Home & Kitchen' => 'bi-house-door',
                            'Books' => 'bi-book',
                            'Sports' => 'bi-bicycle'
                        ];
                    @endphp
                    <i class="bi {{ $catIcons[$categoryName] ?? 'bi-tag' }} display-1 text-{{ $color }}"></i>
                </div>
                <h3 class="fw-bold mb-3">No {{ $categoryName }} Products Yet</h3>
                <p class="text-muted mb-4">We don't have any {{ strtolower($categoryName) }} products at the moment.</p>
                <a href="{{ route('products.create') }}" class="btn btn-{{ $color }}">
                    <i class="bi bi-plus-circle me-2"></i> Add {{ $categoryName }} Product
                </a>
                <a href="{{ route('home') }}" class="btn btn-outline-secondary ms-2">
                    <i class="bi bi-arrow-left me-2"></i> Back to Home
                </a>
                @endif
            </div>
            @endif
        </div>
    </div>
</div>

<style>
    .list-group-item.active {
        background-color: var(--bs-{{ $color }});
        border-color: var(--bs-{{ $color }});
    }
    
    .product-card {
        border: 1px solid #e5e7eb;
        transition: all 0.3s ease;
    }
    
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
    }
    
    .page-item.active .page-link {
        background-color: var(--bs-{{ $color }});
        border-color: var(--bs-{{ $color }});
    }
    
    .page-link {
        color: var(--bs-{{ $color }});
    }
    
    .page-link:hover {
        color: var(--bs-{{ $color }});
        opacity: 0.8;
    }
</style>
@endsection