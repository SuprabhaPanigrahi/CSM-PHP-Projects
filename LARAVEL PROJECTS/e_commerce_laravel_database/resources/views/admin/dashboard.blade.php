@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<h2 class="text-center">Admin Dashboard</h2>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h5 class="card-title">Products</h5>
                <h2>{{ $totalProducts }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h5 class="card-title">Categories</h5>
                <h2>{{ $totalCategories }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <h5 class="card-title">Orders</h5>
                <h2>{{ $totalOrders }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h5 class="card-title">Customers</h5>
                <h2>{{ $totalUsers }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Recent Orders</h5>
            </div>
            <div class="card-body">
                @if($recentOrders->count() > 0)
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Customer</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentOrders as $order)
                            <tr>
                                <td>{{ $order->order_number }}</td>
                                <td>{{ $order->customer_name ?? 'N/A' }}</td>
                                <td>${{ number_format($order->total_amount, 2) }}</td>
                                <td>
                                    <span class="badge bg-{{ $order->status == 'completed' ? 'success' : ($order->status == 'pending' ? 'warning' : 'info') }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <a href="/admin/orders" class="btn btn-sm btn-outline-primary">View All Orders</a>
                @else
                <p class="text-muted">No orders yet.</p>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Quick Links</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <a href="/admin/categories" class="btn btn-outline-primary w-100">
                            <i class="fas fa-tags"></i> Categories
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="/admin/products" class="btn btn-outline-success w-100">
                            <i class="fas fa-box"></i> Products
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="/admin/sliders" class="btn btn-outline-warning w-100">
                            <i class="fas fa-images"></i> Sliders
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="/admin/orders" class="btn btn-outline-info w-100">
                            <i class="fas fa-shopping-cart"></i> Orders
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection