@extends('layouts.app')

@section('content')
<h3>Add Product</h3>

<form method="POST" action="/products">
    @csrf

    <div class="mb-3">
        <label>Name</label>
        <input type="text" name="name" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Price</label>
        <input type="number" step="0.01" name="price" class="form-control" required>
    </div>

    <button class="btn btn-success">Save</button>
    <a href="/products" class="btn btn-secondary">Back</a>
</form>
@endsection
