@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h3>Products</h3>
    <a href="/products/create" class="btn btn-primary">Add Product</a>
</div>

<table class="table table-bordered">
    <tr>
        <th>Name</th>
        <th>Price</th>
        <th width="200">Actions</th>
    </tr>

    @foreach($products as $product)
    <tr>
        <td>{{ $product->name }}</td>
        <td>{{ $product->price }}</td>
        <td>
            <a href="/products/{{ $product->id }}/edit" class="btn btn-sm btn-warning">Edit</a>

            <form action="/products/{{ $product->id }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button class="btn btn-sm btn-danger"
                        onclick="return confirm('Delete this product?')">
                    Delete
                </button>
            </form>
        </td>
    </tr>
    @endforeach
</table>
@endsection
