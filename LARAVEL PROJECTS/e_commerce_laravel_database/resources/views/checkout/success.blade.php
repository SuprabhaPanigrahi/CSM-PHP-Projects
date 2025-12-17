@extends('layouts.app')

@section('title', 'Order Success')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card text-center">
            <div class="card-body py-5">
                <div class="mb-4">
                    <i class="fas fa-check-circle fa-5x text-success"></i>
                </div>
                
                <h2 class="card-title mb-3">Order Placed Successfully!</h2>
                
                <div class="alert alert-success mb-4">
                    <h4>Order Number: {{ $order->order_number }}</h4>
                    <p>Thank you for your order. We'll process it shortly.</p>
                </div>
                
                <div class="card mb-4">
                    <div class="card-body text-start">
                        <h5>Order Details:</h5>
                        <p><strong>Total Amount:</strong> ${{ number_format($order->total_amount, 2) }}</p>
                        <p><strong>Shipping Address:</strong> {{ $order->shipping_address }}, {{ $order->shipping_city }}</p>
                        <p><strong>Payment Method:</strong> 
                            {{ $order->payment_method == 'cash' ? 'Cash on Delivery' : 'Credit/Debit Card' }}
                        </p>
                        <p><strong>Status:</strong> 
                            <span class="badge bg-info">{{ ucfirst($order->status) }}</span>
                        </p>
                    </div>
                </div>
                
                <div class="d-grid gap-2">
                    <a href="/products" class="btn btn-primary">Continue Shopping</a>
                    <a href="/" class="btn btn-outline-secondary">Go to Home</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection