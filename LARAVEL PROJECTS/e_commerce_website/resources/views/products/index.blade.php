@extends('layouts.app')

@section('title', 'Product Management - ShopEasy')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Product Management</h2>
        <a href="{{ route('products.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>Add New Product
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Search and Filter Section -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <form action="{{ route('products.index') }}" method="GET">
                        <div class="input-group">
                            <input type="text" 
                                   name="search" 
                                   class="form-control" 
                                   placeholder="Search products by name, description or category..." 
                                   value="{{ request()->get('search') }}">
                            <button class="btn btn-primary" type="submit">
                                <i class="bi bi-search"></i> Search
                            </button>
                            @if(request()->has('search') || request()->has('category'))
                            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x"></i> Clear
                            </a>
                            @endif
                        </div>
                    </form>
                </div>
                <div class="col-md-6">
                    <div class="btn-group" role="group">
                        <a href="{{ route('products.index') }}" 
                           class="btn btn-outline-primary {{ !request()->has('category') || request()->category == 'all' ? 'active' : '' }}">
                            All Products
                        </a>
                        @foreach($categories as $category)
                        @php
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
                        <a href="{{ route('products.index', ['category' => $category]) }}" 
                           class="btn btn-outline-{{ $color }} 
                                  {{ request()->get('category') == $category ? 'active' : '' }}">
                            {{ $category }}
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
            
            @if(request()->has('search') && request()->get('search'))
            <div class="mt-3">
                <small class="text-muted">
                    <i class="bi bi-search me-1"></i>
                    Searching for: "<strong>{{ request()->get('search') }}</strong>"
                    @if(request()->has('category') && request()->get('category') != 'all')
                    in category: <span class="badge bg-{{ $colors[request()->get('category')] ?? 'primary' }}">
                        {{ request()->get('category') }}
                    </span>
                    @endif
                    <span class="ms-2">Found {{ $totalProducts }} products</span>
                </small>
            </div>
            @endif
        </div>
    </div>

    @if(count($paginatedProducts) > 0)
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($paginatedProducts as $product)
                        <tr>
                            <td>{{ $product['id'] }}</td>
                            <td>
                                @if(isset($product['image']) && $product['image'] != 'default.jpg')
                                    <img src="{{ asset('storage/' . $product['image']) }}" 
                                         alt="{{ $product['name'] }}" 
                                         width="50" height="50" 
                                         class="rounded object-fit-cover">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                         style="width: 50px; height: 50px;">
                                        <i class="bi bi-image text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td>{{ $product['name'] }}</td>
                            <td>
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
                                <span class="badge bg-{{ $color }}">{{ $product['category'] }}</span>
                            </td>
                            <td>${{ number_format($product['price'], 2) }}</td>
                            <td>
                                <small>{{ Str::limit($product['description'], 50) }}</small>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('products.edit', $product['id']) }}" 
                                       class="btn btn-warning btn-sm">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    
                                    <form action="{{ route('products.destroy', $product['id']) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('Are you sure you want to delete this product?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($totalPages > 1)
        <nav aria-label="Page navigation" class="mt-4">
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
                Showing {{ (($currentPage - 1) * 6) + 1 }} to {{ min($currentPage * 6, $totalProducts) }} of {{ $totalProducts }} products
            </div>
        </nav>
        @endif
    @else
        <div class="alert alert-info text-center py-5">
            @if(request()->has('search') || request()->has('category'))
            <i class="bi bi-search display-4 d-block mb-3"></i>
            <h4>No products found</h4>
            <p class="mb-3">
                @if(request()->has('search') && request()->has('category'))
                No products match your search for "<strong>{{ request()->get('search') }}</strong>" in category 
                <span class="badge bg-{{ $colors[request()->get('category')] ?? 'primary' }}">
                    {{ request()->get('category') }}
                </span>
                @elseif(request()->has('search'))
                No products match your search for "<strong>{{ request()->get('search') }}</strong>"
                @elseif(request()->has('category'))
                No products found in category 
                <span class="badge bg-{{ $colors[request()->get('category')] ?? 'primary' }}">
                    {{ request()->get('category') }}
                </span>
                @endif
            </p>
            <a href="{{ route('products.index') }}" class="btn btn-primary">
                <i class="bi bi-list me-2"></i> View All Products
            </a>
            @else
            <i class="bi bi-info-circle display-4 d-block mb-3"></i>
            <h4>No products found</h4>
            <p class="mb-0">Get started by adding your first product!</p>
            <a href="{{ route('products.create') }}" class="btn btn-primary mt-3">
                <i class="bi bi-plus-circle me-2"></i>Add First Product
            </a>
            @endif
        </div>
    @endif
</div>
@endsection