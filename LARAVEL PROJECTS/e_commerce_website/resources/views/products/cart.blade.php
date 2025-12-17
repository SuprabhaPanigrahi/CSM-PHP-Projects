@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">Shopping Cart</h2>
            
            {{-- Cart Items (Hardcoded) --}}
            <div class="row mb-4">
                {{-- Item 1 --}}
                <div class="col-md-8 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-3">
                                    <img src="{{ asset('build/assets/images/zeb-iconic-lite-pic3.webp') }}" class="img-fluid rounded">
                                </div>
                                <div class="col-md-5">
                                    <h5>Wireless Headphones</h5>
                                    <p class="text-muted">Premium quality wireless headphones</p>
                                </div>
                                <div class="col-md-2">
                                    <input type="number" class="form-control" value="1" min="1">
                                </div>
                                <div class="col-md-2 text-end">
                                    <h5>$149.99</h5>
                                    <button class="btn btn-sm btn-danger mt-2">Remove</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                {{-- Item 2 --}}
                <div class="col-md-8 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-3">
                                    <img src="{{ asset('build/assets/images/premium_photo-1678099940967-73fe30680949.jpg') }}" class="img-fluid rounded">
                                </div>
                                <div class="col-md-5">
                                    <h5>Smart Watch</h5>
                                    <p class="text-muted">Latest smart watch with fitness tracking</p>
                                </div>
                                <div class="col-md-2">
                                    <input type="number" class="form-control" value="1" min="1">
                                </div>
                                <div class="col-md-2 text-end">
                                    <h5>$299.99</h5>
                                    <button class="btn btn-sm btn-danger mt-2">Remove</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Order Summary --}}
            <div class="row">
                <div class="col-md-4 offset-md-8">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Order Summary</h5>
                            <div class="mb-2">
                                <div class="d-flex justify-content-between">
                                    <span>Subtotal:</span>
                                    <span>$449.98</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>Shipping:</span>
                                    <span>$5.99</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>Tax:</span>
                                    <span>$36.00</span>
                                </div>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between mb-3">
                                <strong>Total:</strong>
                                <strong class="h5">$491.97</strong>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Discount Code</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="SAVE10">
                                    <button class="btn btn-outline-primary">Apply</button>
                                </div>
                            </div>
                            
                            <a href="/checkout" class="btn btn-primary w-100 mb-2">Checkout</a>
                            <a href="/products" class="btn btn-outline-secondary w-100">Continue Shopping</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection