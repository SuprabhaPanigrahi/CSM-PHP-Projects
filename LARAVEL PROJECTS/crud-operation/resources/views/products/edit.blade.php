@extends('layouts.app')

@section('content')
<h3>Edit Product</h3>

<form method="POST" action="/products/{{ $product->id }}">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label>Name</label>
        <input type="text" name="name" value="{{ $product->name }}" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Price</label>
        <input type="number" step="0.01" name="price" value="{{ $product->price }}" class="form-control" required>
    </div>

    <button class="btn btn-primary">Update</button>
    <a href="/products" class="btn btn-secondary">Back</a>
</form>
@endsection
