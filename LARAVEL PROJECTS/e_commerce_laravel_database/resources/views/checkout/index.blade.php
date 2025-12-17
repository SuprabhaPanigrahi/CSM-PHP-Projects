@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<h2>Checkout</h2>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5>Shipping Details</h5>
            </div>
            <div class="card-body">
                <form action="/checkout" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="shipping_fullname" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phone Number</label>
                            <input type="text" name="shipping_phone" class="form-control" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <input type="text" name="shipping_address" class="form-control" required>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">City</label>
                            <input type="text" name="shipping_city" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">State</label>
                            <input type="text" name="shipping_state" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Zip Code</label>
                            <input type="text" name="shipping_zip" class="form-control" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Payment Method</label>
                        <select name="payment_method" class="form-select" required>
                            <option value="">Select Payment Method</option>
                            <option value="cash">Cash on Delivery</option>
                            <option value="card">Credit/Debit Card</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Order Notes (Optional)</label>
                        <textarea name="notes" class="form-control" rows="3"></textarea>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-check"></i> Place Order
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5>Order Summary</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <h6>Items in Cart:</h6>
                    <ul class="list-group">
                        @foreach($items as $item)
                        <li class="list-group-item d-flex justify-content-between">
                            <span>{{ $item['name'] }} x {{ $item['quantity'] }}</span>
                            <span>${{ number_format($item['subtotal'], 2) }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
                
                <table class="table table-borderless">
                    <tr>
                        <td>Subtotal:</td>
                        <td class="text-end">${{ number_format($total, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Shipping:</td>
                        <td class="text-end">$5.00</td>
                    </tr>
                    <tr class="border-top">
                        <td><strong>Total:</strong></td>
                        <td class="text-end"><strong>${{ number_format($total + 5, 2) }}</strong></td>
                    </tr>
                </table>
                
                <div class="alert alert-info">
                    <small>
                        <i class="fas fa-info-circle"></i>
                        You will pay upon delivery for Cash on Delivery orders.
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection