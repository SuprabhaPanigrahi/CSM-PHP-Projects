@extends('layouts.app')

@section('title', $category->name . ' Products')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h2>{{ $category->name }} Products</h2>
        <p class="text-muted">{{ $products->total() }} products found</p>
    </div>
</div>

@if($products->count() > 0)
<div class="row">
    @foreach($products as $product)
    <div class="col-md-3 mb-4">
        <div class="card product-card h-100">
            <img src="{{ asset('storage/' . $product->image) }}" 
                 class="card-img-top" 
                 style="height: 200px; object-fit: cover;">
            <div class="card-body">
                <h5 class="card-title">{{ $product->name }}</h5>
                <p class="card-text">
                    @if($product->discount_price)
                        <span class="text-danger fs-5"><strong>${{ $product->discount_price }}</strong></span>
                        <span class="text-muted text-decoration-line-through">${{ $product->price }}</span>
                    @else
                        <span class="fs-5"><strong>${{ $product->price }}</strong></span>
                    @endif
                </p>
                <div class="d-flex justify-content-between">
                    <a href="/products/{{ $product->id }}" class="btn btn-primary btn-sm">View</a>
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
    No products found in this category.
</div>
@endif

<div class="mt-3">
    <a href="/products" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left"></i> Back to All Products
    </a>
</div>
@endsection