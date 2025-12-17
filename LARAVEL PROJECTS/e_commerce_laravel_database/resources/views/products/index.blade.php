@extends('layouts.app')

@section('title', 'Products')

@section('content')
<div class="row">
    <!-- Sidebar Filters -->
    <div class="col-md-3">
        <div class="card mb-4">
            <div class="card-header">
                <h5>Filters</h5>
            </div>
            <div class="card-body">
                <form action="/products" method="GET">
                    <!-- Search -->
                    <div class="mb-3">
                        <label class="form-label">Search</label>
                        <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Search products...">
                    </div>

                    <!-- Category Filter -->
                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <select name="category" class="form-select">
                            <option value="all">All Categories</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Price Range -->
                    <div class="mb-3">
                        <label class="form-label">Price Range</label>
                        <div class="row">
                            <div class="col">
                                <input type="number" name="min_price" class="form-control" placeholder="Min" value="{{ request('min_price') }}">
                            </div>
                            <div class="col">
                                <input type="number" name="max_price" class="form-control" placeholder="Max" value="{{ request('max_price') }}">
                            </div>
                        </div>
                    </div>

                    <!-- Sort -->
                    <div class="mb-3">
                        <label class="form-label">Sort By</label>
                        <select name="sort" class="form-select">
                            <option value="new" {{ request('sort') == 'new' ? 'selected' : '' }}>Newest First</option>
                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Apply Filters</button>
                    <a href="/products" class="btn btn-secondary w-100 mt-2">Clear Filters</a>
                </form>
            </div>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="col-md-9">
        <div class="row mb-3">
            <div class="col">
                <h2>Products</h2>
                <p class="text-muted">Showing {{ $products->count() }} of {{ $products->total() }} products</p>
            </div>
        </div>

        @if($products->count() > 0)
        <div class="row">
            @foreach($products as $product)
            <div class="col-md-4 mb-4">
                <div class="card product-card h-100">
                    <img src="{{ asset('storage/' . $product->image) }}" 
                         class="card-img-top" 
                         style="height: 200px; object-fit: cover;"
                         alt="{{ $product->name }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <p class="card-text">
                            Category: <strong>{{ $product->category_name }}</strong>
                        </p>
                        <p class="card-text">
                            @if($product->discount_price)
                                <span class="text-danger fs-5"><strong>${{ $product->discount_price }}</strong></span>
                                <span class="text-muted text-decoration-line-through">${{ $product->price }}</span>
                            @else
                                <span class="fs-5"><strong>${{ $product->price }}</strong></span>
                            @endif
                        </p>
                        <div class="d-flex justify-content-between">
                            <a href="/products/{{ $product->id }}" class="btn btn-primary btn-sm">View Details</a>
                            <a href="/cart/add/{{ $product->id }}" class="btn btn-success btn-sm">Add to Cart</a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="row mt-4">
            <div class="col">
                {{ $products->links() }}
            </div>
        </div>
        @else
        <div class="alert alert-info">
            No products found. Try different filters.
        </div>
        @endif
    </div>
</div>
@endsection