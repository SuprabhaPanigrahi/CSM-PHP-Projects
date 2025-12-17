@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="row">
    <div class="col-md-6">
        <img src="{{ asset('storage/' . $product->image) }}" 
             class="img-fluid rounded" 
             alt="{{ $product->name }}">
    </div>
    <div class="col-md-6">
        <h2>{{ $product->name }}</h2>
        <p class="text-muted">Category: {{ $product->category_name }}</p>
        
        <div class="mb-3">
            <h4 class="text-primary">
                @if($product->discount_price)
                    <span class="text-danger">${{ $product->discount_price }}</span>
                    <small class="text-muted text-decoration-line-through">${{ $product->price }}</small>
                @else
                    ${{ $product->price }}
                @endif
            </h4>
        </div>

        <div class="mb-4">
            <h5>Description</h5>
            <p>{{ $product->description }}</p>
        </div>

        <div class="mb-4">
            <p><strong>Stock:</strong> {{ $product->stock }} available</p>
            <p><strong>Status:</strong> 
                <span class="badge {{ $product->status ? 'bg-success' : 'bg-danger' }}">
                    {{ $product->status ? 'Available' : 'Out of Stock' }}
                </span>
            </p>
        </div>

        @if($product->status && $product->stock > 0)
        <form action="/cart/add/{{ $product->id }}" method="GET">
            <div class="row mb-3">
                <div class="col-md-3">
                    <label for="quantity" class="form-label">Quantity</label>
                    <input type="number" name="quantity" id="quantity" 
                           class="form-control" value="1" min="1" max="{{ $product->stock }}">
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="fas fa-cart-plus"></i> Add to Cart
            </button>
            <a href="/cart" class="btn btn-success btn-lg">
                <i class="fas fa-shopping-cart"></i> Buy Now
            </a>
        </form>
        @else
        <button class="btn btn-secondary btn-lg" disabled>
            Out of Stock
        </button>
        @endif

        <div class="mt-4">
            <a href="/products" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back to Products
            </a>
        </div>
    </div>
</div>

<!-- Related Products -->
@if($related->count() > 0)
<div class="row mt-5">
    <div class="col">
        <h3>Related Products</h3>
        <div class="row">
            @foreach($related as $relatedProduct)
            <div class="col-md-3 mb-4">
                <div class="card product-card h-100">
                    <img src="{{ asset('storage/' . $relatedProduct->image) }}" 
                         class="card-img-top" 
                         style="height: 150px; object-fit: cover;">
                    <div class="card-body">
                        <h6 class="card-title">{{ $relatedProduct->name }}</h6>
                        <p class="card-text">
                            @if($relatedProduct->discount_price)
                                <span class="text-danger">${{ $relatedProduct->discount_price }}</span>
                            @else
                                ${{ $relatedProduct->price }}
                            @endif
                        </p>
                        <a href="/products/{{ $relatedProduct->id }}" class="btn btn-primary btn-sm">View</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif
@endsection