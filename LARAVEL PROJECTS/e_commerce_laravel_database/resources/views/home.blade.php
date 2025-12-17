@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <!-- Slider -->
    @if($sliders->count() > 0)
    <div id="carouselExample" class="carousel slide mb-4" data-bs-ride="carousel">
        <div class="carousel-inner">
            @foreach($sliders as $key => $slider)
            <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                <img src="{{ asset('storage/' . $slider->image) }}" class="d-block w-100" style="height: 400px; object-fit: cover;" alt="{{ $slider->title }}">
                <div class="carousel-caption d-none d-md-block">
                    <h5>{{ $slider->title }}</h5>
                    <p>{{ $slider->description }}</p>
                </div>
            </div>
            @endforeach
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>
    @endif

    <!-- Categories -->
    <h2 class="mb-4">Shop by Category</h2>
    <div class="row mb-5">
        @foreach($categories as $category)
        <div class="col-md-3 mb-3">
            <div class="card category-card">
                <a href="/category/{{ $category->id }}" class="text-decoration-none text-dark">
                    <img src="{{ $category->image ? asset('storage/' . $category->image) : 'https://via.placeholder.com/300x200' }}" 
                         class="card-img-top" style="height: 150px; object-fit: cover;">
                    <div class="card-body text-center">
                        <h5 class="card-title">{{ $category->name }}</h5>
                    </div>
                </a>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Featured Products -->
    <h2 class="mb-4">Featured Products</h2>
    <div class="row mb-5">
        @foreach($featured as $product)
        <div class="col-md-3 mb-4">
            <div class="card product-card h-100">
                <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                <div class="card-body">
                    <h5 class="card-title">{{ $product->name }}</h5>
                    <p class="card-text">
                        @if($product->discount_price)
                            <span class="text-danger"><strong>${{ $product->discount_price }}</strong></span>
                            <span class="text-muted text-decoration-line-through">${{ $product->price }}</span>
                        @else
                            <span><strong>${{ $product->price }}</strong></span>
                        @endif
                    </p>
                    <a href="/products/{{ $product->id }}" class="btn btn-primary btn-sm">View Details</a>
                    <a href="/cart/add/{{ $product->id }}" class="btn btn-success btn-sm">Add to Cart</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- New Arrivals -->
    <h2 class="mb-4">New Arrivals</h2>
    <div class="row">
        @foreach($new as $product)
        <div class="col-md-3 mb-4">
            <div class="card product-card h-100">
                <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                <div class="card-body">
                    <h5 class="card-title">{{ $product->name }}</h5>
                    <p class="card-text">
                        @if($product->discount_price)
                            <span class="text-danger"><strong>${{ $product->discount_price }}</strong></span>
                            <span class="text-muted text-decoration-line-through">${{ $product->price }}</span>
                        @else
                            <span><strong>${{ $product->price }}</strong></span>
                        @endif
                    </p>
                    <a href="/products/{{ $product->id }}" class="btn btn-primary btn-sm">View Details</a>
                    <a href="/cart/add/{{ $product->id }}" class="btn btn-success btn-sm">Add to Cart</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
@endsection