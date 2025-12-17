@extends('layouts.app')

@section('title', 'Shopping Cart - ShopEasy')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Shopping Cart</h1>
    
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    @if(count($cart) > 0)
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th width="100">Image</th>
                                    <th>Product</th>
                                    <th class="text-center">Price</th>
                                    <th class="text-center">Quantity</th>
                                    <th class="text-center">Total</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cart as $item)
                                <tr>
                                    <td>
                                        @if($item['image'])
                                            <img src="{{ $item['image'] }}" 
                                                 class="img-fluid rounded" 
                                                 width="70" height="70"
                                                 style="object-fit: cover;"
                                                 alt="{{ $item['name'] }}">
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                                 style="width: 70px; height: 70px;">
                                                <i class="bi bi-image text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <h6 class="mb-1">{{ $item['name'] }}</h6>
                                        <small class="text-muted">ID: {{ $item['id'] }}</small>
                                    </td>
                                    <td class="text-center">
                                        <span class="fw-bold">${{ number_format($item['price'], 2) }}</span>
                                    </td>
                                    <td class="text-center">
                                        <form action="{{ route('cart.update', $item['id']) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            <div class="input-group input-group-sm" style="width: 120px;">
                                                <button class="btn btn-outline-secondary decrement" type="button">-</button>
                                                <input type="number" 
                                                       name="quantity" 
                                                       value="{{ $item['quantity'] }}" 
                                                       min="1"
                                                       class="form-control text-center quantity-input">
                                                <button class="btn btn-outline-secondary increment" type="button">+</button>
                                            </div>
                                            <button type="submit" class="btn btn-link btn-sm mt-1">Update</button>
                                        </form>
                                    </td>
                                    <td class="text-center">
                                        <span class="text-success fw-bold">
                                            ${{ number_format($item['price'] * $item['quantity'], 2) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <form action="{{ route('cart.remove', $item['id']) }}" 
                                              method="POST" 
                                              onsubmit="return confirm('Remove this item from cart?')">
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('home') }}" class="btn btn-outline-primary">
                            <i class="bi bi-arrow-left me-2"></i> Continue Shopping
                        </a>
                        <form action="{{ route('cart.clear') }}" method="POST"
                              onsubmit="return confirm('Clear entire cart?')">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger">
                                <i class="bi bi-trash me-2"></i> Clear Cart
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4 mt-4 mt-lg-0">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-4">Order Summary</h5>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <span>${{ number_format($total, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Shipping:</span>
                        <span>${{ $total > 0 ? '5.00' : '0.00' }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tax (10%):</span>
                        <span>${{ number_format($total * 0.1, 2) }}</span>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between mb-4">
                        <span class="fw-bold">Total:</span>
                        <span class="fw-bold fs-5 text-success">
                            ${{ number_format($total + ($total > 0 ? 5 : 0) + ($total * 0.1), 2) }}
                        </span>
                    </div>
                    
                    <button class="btn btn-primary btn-lg w-100 py-3">
                        <i class="bi bi-lock me-2"></i> Proceed to Checkout
                    </button>
                    
                    <div class="text-center mt-3">
                        <img src="https://img.icons8.com/color/48/000000/visa.png" alt="Visa" class="me-2" style="height: 30px;">
                        <img src="https://img.icons8.com/color/48/000000/mastercard.png" alt="Mastercard" class="me-2" style="height: 30px;">
                        <img src="https://img.icons8.com/color/48/000000/paypal.png" alt="PayPal" style="height: 30px;">
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="text-center py-5">
        <div class="mb-4">
            <i class="bi bi-cart-x display-1 text-muted"></i>
        </div>
        <h3 class="fw-bold mb-3">Your cart is empty</h3>
        <p class="text-muted mb-4">Looks like you haven't added any products to your cart yet.</p>
        <a href="{{ route('home') }}" class="btn btn-primary btn-lg">
            <i class="bi bi-arrow-left me-2"></i> Start Shopping
        </a>
    </div>
    @endif
</div>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Quantity increment/decrement
    document.querySelectorAll('.increment').forEach(button => {
        button.addEventListener('click', function() {
            const input = this.parentElement.querySelector('.quantity-input');
            input.value = parseInt(input.value) + 1;
        });
    });
    
    document.querySelectorAll('.decrement').forEach(button => {
        button.addEventListener('click', function() {
            const input = this.parentElement.querySelector('.quantity-input');
            if (parseInt(input.value) > 1) {
                input.value = parseInt(input.value) - 1;
            }
        });
    });
});
</script>
@endsection
@endsection