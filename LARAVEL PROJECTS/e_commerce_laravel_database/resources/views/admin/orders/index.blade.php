@extends('layouts.app')

@section('title', 'Orders - Admin')

@section('content')
<h2>Orders</h2>

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Payment Method</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr>
                        <td>{{ $order->order_number }}</td>
                        <td>{{ $order->customer_name }}</td>
                        <td>{{ date('M d, Y', strtotime($order->created_at)) }}</td>
                        <td>${{ number_format($order->total_amount, 2) }}</td>
                        <td>
                            {{ $order->payment_method == 'cash' ? 'Cash on Delivery' : 'Card' }}
                        </td>
                        <td>
                            <span class="badge bg-{{ $order->status == 'completed' ? 'success' : 
                                                  ($order->status == 'processing' ? 'info' : 
                                                  ($order->status == 'pending' ? 'warning' : 'danger')) }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td>
                            <a href="/admin/orders/{{ $order->id }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-eye"></i> View
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection