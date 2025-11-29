@extends('layouts.app', ['title' => 'Products'])

@section('content')

<h2>Products</h2>

<a href="{{ route('products.create') }}" class="btn btn-success mb-3">Add Product</a>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="row">
    @forelse ($products as $product)
        <div class="col-md-3">
            <div class="card mb-4">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" 
                         class="card-img-top" style="height:200px;object-fit:cover;">
                @else
                    <div class="bg-light text-center p-5">No Image</div>
                @endif

                <div class="card-body">
                    <h5 class="card-title">{{ $product->name }}</h5>
                </div>
            </div>
        </div>
    @empty
        <p>No products found</p>
    @endforelse
</div>

@endsection
