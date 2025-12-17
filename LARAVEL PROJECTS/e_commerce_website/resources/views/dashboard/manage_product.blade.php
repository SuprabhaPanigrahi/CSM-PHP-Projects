@extends('dashboard.main')

@section('content')
<h2 class="mb-4">Manage Products</h2>

<div class="card p-4 shadow-sm">
    <form>
        <div class="mb-3">
            <label class="form-label">Product Name</label>
            <input type="text" class="form-control" placeholder="Enter Product Name">
        </div>

        <div class="mb-3">
            <label class="form-label">Category</label>
            <select class="form-select">
                <option>Select Category</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Price</label>
            <input type="number" class="form-control" placeholder="Enter Price">
        </div>

        <button class="btn btn-success">Add Product</button>
    </form>
</div>

@endsection
