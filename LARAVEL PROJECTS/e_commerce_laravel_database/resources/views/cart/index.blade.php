@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('content')
<h2>Shopping Cart</h2>

@if(count($items) > 0)
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Subtotal</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                        <tr>
                            <td>
                                <div class="d-flex">
                                    <img src="{{ asset('storage/' . $item['image']) }}" 
                                         style="width: 60px; height: 60px; object-fit: cover; margin-right: 10px;">
                                    <div>
                                        <h6>{{ $item['name'] }}</h6>
                                        <small class="text-muted">Stock: {{ $item['stock'] }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>${{ number_format($item['price'], 2) }}</td>
                            <td>
                                <form action="/cart/update/{{ $item['id'] }}" method="POST" class="d-flex">
                                    @csrf
                                    <input type="number" name="quantity" value="{{ $item['quantity'] }}" 
                                           min="1" max="{{ $item['stock'] }}" 
                                           class="form-control form-control-sm" style="width: 80px;">
                                    <button type="submit" class="btn btn-sm btn-outline-primary ms-2">
                                        <i class="fas fa-sync"></i>
                                    </button>
                                </form>
                            </td>
                            <td>${{ number_format($item['subtotal'], 2) }}</td>
                            <td>
                                <a href="/cart/remove/{{ $item['id'] }}" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="mt-3">
            <a href="/products" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Continue Shopping
            </a>
            <a href="/cart/clear" class="btn btn-outline-danger" 
               onclick="return confirm('Clear entire cart?')">
                <i class="fas fa-trash"></i> Clear Cart
            </a>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5>Order Summary</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td>Subtotal:</td>
                        <td class="text-end">${{ number_format($total, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Shipping:</td>
                        <td class="text-end">$5.00</td>
                    </tr>
                    <tr>
                        <td><strong>Total:</strong></td>
                        <td class="text-end"><strong>${{ number_format($total + 5, 2) }}</strong></td>
                    </tr>
                </table>
                
                @if(session('user_id'))
                <a href="/checkout" class="btn btn-primary w-100 btn-lg">
                    <i class="fas fa-lock"></i> Proceed to Checkout
                </a>
                @else
                <div class="alert alert-warning">
                    <p>Please <a href="/login">login</a> to checkout</p>
                    <a href="/login" class="btn btn-warning w-100">
                        <i class="fas fa-sign-in-alt"></i> Login to Checkout
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@else
<div class="alert alert-info">
    <h4>Your cart is empty</h4>
    <p>Add some products to your cart!</p>
    <a href="/products" class="btn btn-primary">Browse Products</a>
</div>
@endif
@endsection