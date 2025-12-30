@extends('layouts.app')

@section('title', 'Access Denied')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center mt-5">
        <div class="col-md-6">
            <div class="card border-danger">
                <div class="card-header bg-danger text-white">
                    <h4><i class="fas fa-ban"></i> Access Denied</h4>
                </div>
                <div class="card-body text-center">
                    <div class="mb-4">
                        <i class="fas fa-lock fa-5x text-danger"></i>
                    </div>
                    <h3 class="text-danger mb-3">Access Restricted</h3>
                    <p class="lead" id="errorMessage">You don't have permission to access this page.</p>
                    
                    <div class="alert alert-info">
                        <h5><i class="fas fa-info-circle"></i> Your Account Type: 
                            <span class="badge {{ session('customer_type') }}-badge">
                                {{ ucfirst(session('customer_type')) }} Customer
                            </span>
                        </h5>
                        <p class="mb-0" id="typeInfo">
                            @php
                                $type = session('customer_type');
                                $permissions = [
                                    'silver' => 'Silver customers can only view products.',
                                    'gold' => 'Gold customers can view products and make purchases.',
                                    'diamond' => 'Diamond customers can view products, see offers, and make purchases with discounts.'
                                ];
                                echo $permissions[$type] ?? 'Unknown customer type.';
                            @endphp
                        </p>
                    </div>
                    
                    <div class="mt-4">
                        <a href="{{ route('dashboard') }}" class="btn btn-primary">
                            <i class="fas fa-tachometer-alt"></i> Back to Dashboard
                        </a>
                        <a href="{{ route('products') }}" class="btn btn-outline-primary ms-2">
                            <i class="fas fa-shopping-bag"></i> View Products
                        </a>
                        @if(in_array(session('customer_type'), ['gold', 'diamond']))
                        <a href="{{ route('purchase') }}" class="btn btn-outline-success ms-2">
                            <i class="fas fa-cart-plus"></i> Make Purchase
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    $(document).ready(function() {
        // Check if there's a specific error message from API
        const urlParams = new URLSearchParams(window.location.search);
        const message = urlParams.get('message');
        if (message) {
            $('#errorMessage').text(decodeURIComponent(message));
        }
    });
</script>
@endsection
@endsection