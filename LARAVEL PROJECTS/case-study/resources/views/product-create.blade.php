@extends('layouts.app', ['title' => 'Add Product'])

@section('content')

<h2>Add New Product</h2>

<form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">

    @csrf

    <div class="mb-3">
        <label class="form-label">Product Name</label>
        <input type="text" name="name" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Product Image</label>
        <input type="file" name="image" class="form-control" required>
    </div>

    <button class="btn btn-primary">Save</button>
</form>

@endsection