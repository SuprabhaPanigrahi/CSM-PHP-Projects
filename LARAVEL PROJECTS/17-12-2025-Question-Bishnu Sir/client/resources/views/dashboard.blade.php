@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2"><i class="fas fa-tachometer-alt"></i> Dashboard</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <!-- <a href="javascript:void(0)" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#permissionsModal">
                        <i class="fas fa-key"></i> View Permissions
                    </a> -->
                </div>
            </div>
        </div>
    </div>

    <!-- Welcome Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h3 class="card-title">
                                Welcome, {{ session('customer_name') }}!
                            </h3>
                            <p class="card-text">
                                @php
                                    $type = session('customer_type');
                                    $benefits = [
                                        'silver' => 'As a Silver customer, you can view all available products. Upgrade to Gold or Diamond for more features.',
                                        'gold' => 'As a Gold customer, you can view all products and make purchases. Upgrade to Diamond for special discounts.',
                                        'diamond' => 'As a Diamond customer, you have full access including viewing special offers and making purchases with discounts.'
                                    ];
                                @endphp
                                {{ $benefits[$type] }}
                            </p>
                        </div>
                        <div class="col-md-4 text-end">
                            @php
                                $badgeClass = $type . '-badge';
                            @endphp
                            <span class="badge badge-customer {{ $badgeClass }}" style="font-size: 1.2em;">
                                {{ ucfirst($type) }} Customer
                            </span>
                            <br>
                            <small class="text-white-50">Customer ID: {{ session('customer_id') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h5><i class="fas fa-shopping-bag text-primary"></i> Products</h5>
                    <h3 id="productsCount">0</h3>
                    <a href="{{ route('products') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
            </div>
        </div>
        
        <!-- Offers Card - Only show if Diamond -->
        @if(session('customer_type') === 'diamond')
        <div class="col-md-3">
            <div class="card border-success">
                <div class="card-body text-center">
                    <h5><i class="fas fa-percent text-success"></i> Offers</h5>
                    <h3 id="offersCount">0</h3>
                    <a href="{{ route('offers') }}" class="btn btn-sm btn-outline-success">View All</a>
                </div>
            </div>
        </div>
        @else
        <div class="col-md-3">
            <div class="card border-secondary">
                <div class="card-body text-center">
                    <h5><i class="fas fa-percent text-secondary"></i> Offers</h5>
                    <h3><i class="fas fa-lock"></i></h3>
                    <button class="btn btn-sm btn-outline-secondary" disabled>Diamond Only</button>
                </div>
            </div>
        </div>
        @endif
        
        <!-- Purchase Card - Only show if Gold/Diamond -->
        @if(in_array(session('customer_type'), ['gold', 'diamond']))
        <div class="col-md-3">
            <div class="card border-warning">
                <div class="card-body text-center">
                    <h5><i class="fas fa-cart-plus text-warning"></i> Purchase</h5>
                    <p>Make new purchase</p>
                    <a href="{{ route('purchase') }}" class="btn btn-sm btn-outline-warning">Go to Purchase</a>
                </div>
            </div>
        </div>
        @else
        <div class="col-md-3">
            <div class="card border-secondary">
                <div class="card-body text-center">
                    <h5><i class="fas fa-cart-plus text-secondary"></i> Purchase</h5>
                    <p><i class="fas fa-lock"></i></p>
                    <button class="btn btn-sm btn-outline-secondary" disabled>Gold & Diamond</button>
                </div>
            </div>
        </div>
        @endif
        
        <!-- History Card - Only show if Gold/Diamond -->
        @if(in_array(session('customer_type'), ['gold', 'diamond']))
        <div class="col-md-3">
            <div class="card border-info">
                <div class="card-body text-center">
                    <h5><i class="fas fa-history text-info"></i> History</h5>
                    <h3 id="purchasesCount">0</h3>
                    <a href="{{ route('purchase.history') }}" class="btn btn-sm btn-outline-info">View History</a>
                </div>
            </div>
        </div>
        @else
        <div class="col-md-3">
            <div class="card border-secondary">
                <div class="card-body text-center">
                    <h5><i class="fas fa-history text-secondary"></i> History</h5>
                    <p><i class="fas fa-lock"></i></p>
                    <button class="btn btn-sm btn-outline-secondary" disabled>Gold & Diamond</button>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Recent Purchases - Only show if Gold/Diamond -->
    @if(in_array(session('customer_type'), ['gold', 'diamond']))
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-clock"></i> Recent Purchases</h5>
                </div>
                <div class="card-body">
                    <div id="recentPurchases">
                        <div class="text-center">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p>Loading recent purchases...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Permissions Modal -->
<div class="modal fade" id="permissionsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-key"></i> Your Permissions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Feature</th>
                                <th>Access</th>
                                <th>Required Plan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><i class="fas fa-shopping-bag"></i> View Products</td>
                                <td><span class="badge bg-success">Allowed</span></td>
                                <td>All Plans</td>
                            </tr>
                            <tr>
                                <td><i class="fas fa-percent"></i> View Offers</td>
                                <td>
                                    @if(session('customer_type') === 'diamond')
                                        <span class="badge bg-success">Allowed</span>
                                    @else
                                        <span class="badge bg-danger">Denied</span>
                                    @endif
                                </td>
                                <td>Diamond Only</td>
                            </tr>
                            <tr>
                                <td><i class="fas fa-cart-plus"></i> Make Purchase</td>
                                <td>
                                    @if(in_array(session('customer_type'), ['gold', 'diamond']))
                                        <span class="badge bg-success">Allowed</span>
                                    @else
                                        <span class="badge bg-danger">Denied</span>
                                    @endif
                                </td>
                                <td>Gold & Diamond</td>
                            </tr>
                            <tr>
                                <td><i class="fas fa-history"></i> Purchase History</td>
                                <td>
                                    @if(in_array(session('customer_type'), ['gold', 'diamond']))
                                        <span class="badge bg-success">Allowed</span>
                                    @else
                                        <span class="badge bg-danger">Denied</span>
                                    @endif
                                </td>
                                <td>Gold & Diamond</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="alert alert-info">
                    <h6><i class="fas fa-info-circle"></i> Plan Comparison</h6>
                    <ul class="mb-0">
                        <li><strong>Silver:</strong> View Products Only</li>
                        <li><strong>Gold:</strong> View Products + Make Purchases</li>
                        <li><strong>Diamond:</strong> All Features + Special Discounts</li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                @if(session('customer_type') !== 'diamond')
                    <button type="button" class="btn btn-primary" onclick="upgradePlan()">
                        <i class="fas fa-arrow-up"></i> Upgrade Plan
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    $(document).ready(function() {
        const token = '{{ session("token") }}';
        const customerType = '{{ session("customer_type") }}';
        
        // Load products count
        $.ajax({
            url: API_BASE_URL + '/products',
            method: 'GET',
            headers: {
                'Authorization': 'Bearer ' + token
            },
            success: function(response) {
                if (response.status === 'success') {
                    $('#productsCount').text(response.data.length);
                }
            },
            error: handleApiError
        });

        // Load offers count (only for diamond)
        if (customerType === 'diamond') {
            $.ajax({
                url: API_BASE_URL + '/offers',
                method: 'GET',
                headers: {
                    'Authorization': 'Bearer ' + token
                },
                success: function(response) {
                    if (response.status === 'success') {
                        $('#offersCount').text(response.data.length);
                    }
                },
                error: handleApiError
            });
        }

        // Load purchase history count
        if (['gold', 'diamond'].includes(customerType)) {
            $.ajax({
                url: API_BASE_URL + '/purchases/history',
                method: 'GET',
                headers: {
                    'Authorization': 'Bearer ' + token
                },
                success: function(response) {
                    if (response.status === 'success') {
                        $('#purchasesCount').text(response.data.length);
                        
                        // Display recent purchases
                        const purchases = response.data.slice(0, 5);
                        let html = '';
                        
                        if (purchases.length > 0) {
                            html = `
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Product</th>
                                                <th>Quantity</th>
                                                <th>Total Value</th>
                                            </tr>
                                        </thead>
                                        <tbody>`;
                            
                            purchases.forEach(purchase => {
                                const date = new Date(purchase.date).toLocaleDateString();
                                html += `
                                    <tr>
                                        <td>${date}</td>
                                        <td>${purchase.product ? purchase.product.name : 'N/A'}</td>
                                        <td>${purchase.qty}</td>
                                        <td>₹${parseFloat(purchase.total_value || 0).toFixed(2)}</td>
                                    </tr>`;
                            });
                            
                            html += `</tbody></table>`;
                        } else {
                            html = '<p class="text-center text-muted">No purchases made yet.</p>';
                        }
                        
                        $('#recentPurchases').html(html);
                    }
                },
                error: handleApiError
            });
        }
    });
    
    function upgradePlan() {
        showMessage('Please contact customer support to upgrade your plan.', 'info');
        $('#permissionsModal').modal('hide');
    }
</script>
@endsection
@endsection