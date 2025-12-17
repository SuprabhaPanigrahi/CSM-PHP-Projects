@extends('layouts.app')

@section('title', 'Order Details - Admin')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4>Order Details - {{ $order->order_number }}</h4>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6>Order Information</h6>
                        <p><strong>Order Number:</strong> {{ $order->order_number }}</p>
                        <p><strong>Order Date:</strong> {{ date('M d, Y H:i', strtotime($order->created_at)) }}</p>
                        <p><strong>Payment Method:</strong> 
                            {{ $order->payment_method == 'cash' ? 'Cash on Delivery' : 'Credit/Debit Card' }}
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6>Customer Information</h6>
                        <p><strong>Name:</strong> {{ $order->customer_name }}</p>
                        <p><strong>Email:</strong> {{ $order->customer_email }}</p>
                    </div>
                </div>
                
                <h6>Shipping Information</h6>
                <div class="card mb-4">
                    <div class="card-body">
                        <p><strong>Address:</strong> {{ $order->shipping_address }}</p>
                        <p><strong>City:</strong> {{ $order->shipping_city }}</p>
                        <p><strong>State:</strong> {{ $order->shipping_state }}</p>
                        <p><strong>Zip Code:</strong> {{ $order->shipping_zip }}</p>
                        <p><strong>Phone:</strong> {{ $order->shipping_phone }}</p>
                    </div>
                </div>
                
                <h6>Order Items</h6>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $item)
                            <tr>
                                <td>{{ $item->product_name }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>${{ number_format($item->price, 2) }}</td>
                                <td>${{ number_format($item->price * $item->quantity, 2) }}</td>
                            </tr>
                            @endforeach
                            <tr>
                                <td colspan="3" class="text-end"><strong>Shipping:</strong></td>
                                <td><strong>$5.00</strong></td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                <td><strong>${{ number_format($order->total_amount, 2) }}</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4">
                    <a href="/admin/orders" class="btn btn-secondary">Back to Orders</a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5>Update Order Status</h5>
            </div>
            <div class="card-body">
                <form action="/admin/orders/{{ $order->id }}/status" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Current Status:</label>
                        <div class="alert alert-{{ $order->status == 'completed' ? 'success' : 
                                                  ($order->status == 'processing' ? 'info' : 
                                                  ($order->status == 'pending' ? 'warning' : 'danger')) }}">
                            <strong>{{ ucfirst($order->status) }}</strong>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="status" class="form-label">Change Status To:</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100">Update Status</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection