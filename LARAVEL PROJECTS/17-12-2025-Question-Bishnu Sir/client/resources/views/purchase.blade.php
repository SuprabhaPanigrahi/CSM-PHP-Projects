@extends('layouts.app')

@section('title', 'Make Purchase')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Make Purchase</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            @php
                $type = session('customer_type');
                $badgeClass = $type . '-badge';
            @endphp
            <span class="badge {{ $badgeClass }}">
                {{ ucfirst($type) }} Customer
            </span>
        </div>
    </div>

    <!-- Check if customer can purchase -->
    @if(!in_array(session('customer_type'), ['gold', 'diamond']))
        <div class="alert alert-warning">
            <h4>Purchase Restricted</h4>
            <p>Only <strong>Gold</strong> and <strong>Diamond</strong> customers can make purchases. 
               Your current customer type is: <strong>{{ ucfirst(session('customer_type')) }}</strong>.</p>
            <p>Please contact support to upgrade your account.</p>
        </div>
    @else
    <!-- Purchase Form -->
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>Purchase Details</h5>
                </div>
                <div class="card-body">
                    <div id="purchaseMessage"></div>
                    <form id="purchaseForm">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="product_id" class="form-label">Select Product *</label>
                                <select class="form-select" id="product_id" required>
                                    <option value="">Loading products...</option>
                                </select>
                                <div id="productLoading" class="text-primary">
                                    <small> Loading products...</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="qty" class="form-label">Quantity *</label>
                                <input type="number" class="form-control" id="qty" 
                                       min="1" value="1" required>
                                <small id="stockInfo" class="text-muted"></small>
                            </div>
                        </div>

                        <!-- Price Information -->
                        <div class="row mb-3" id="priceInfo" style="display: none;">
                            <div class="col-md-12">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <p><strong>Original Price:</strong><br>
                                                   <span id="originalPrice">₹0.00</span></p>
                                            </div>
                                            <div class="col-md-4">
                                                <p><strong>Discount:</strong><br>
                                                   <span id="discountAmount">₹0.00</span>
                                                   (<span id="discountPercent">0%</span>)</p>
                                            </div>
                                            <div class="col-md-4">
                                                <p><strong>Final Price:</strong><br>
                                                   <span id="finalPrice" class="text-success">₹0.00</span></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Customer Info -->
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header bg-info text-white">
                                        <strong>Customer Information</strong>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>Name:</strong> {{ session('customer_name') }}</p>
                                        <p><strong>Email:</strong> {{ session('email') }}</p>
                                        <p><strong>Customer Type:</strong> 
                                            <span class="badge {{ $badgeClass }}">
                                                {{ ucfirst(session('customer_type')) }}
                                            </span>
                                        </p>
                                        @if(session('customer_type') === 'diamond')
                                            <p class="text-success"><i class="fas fa-gem"></i> You are eligible for special discounts!</p>
                                        @elseif(session('customer_type') === 'gold')
                                            <p class="text-warning">You can make purchases but no discounts are applied.</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-success btn-lg" id="purchaseBtn">
                               Confirm Purchase
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Summary -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Purchase Summary</h5>
                </div>
                <div class="card-body">
                    <div id="summaryContent">
                        <p class="text-center text-muted">
                            Select a product to see purchase summary
                        </p>
                    </div>
                </div>
            </div>

            <!-- Recent Purchases -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5>Recent Purchases</h5>
                </div>
                <div class="card-body">
                    <div id="recentPurchases">
                        <div class="text-center">
                            <div class="spinner-border spinner-border-sm" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p>Loading...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

@section('scripts')
<script>
    let products = [];
    let selectedProduct = null;

    $(document).ready(function() {
        @if(in_array(session('customer_type'), ['gold', 'diamond']))
            loadProducts();
            loadRecentPurchases();
            
            $('#product_id').change(function() {
                const productId = $(this).val();
                if (productId) {
                    selectedProduct = products.find(p => p.product_id == productId);
                    updatePriceInfo();
                    updateSummary();
                } else {
                    $('#priceInfo').hide();
                }
            });
            
            $('#qty').on('input', function() {
                if (selectedProduct) {
                    updatePriceInfo();
                    updateSummary();
                }
            });
            
            $('#purchaseForm').submit(function(e) {
                e.preventDefault();
                makePurchase();
            });
        @endif
    });

    function loadProducts() {
        const token = '{{ session("token") }}';
        
        $.ajax({
            url: API_BASE_URL + '/products',
            method: 'GET',
            headers: {
                'Authorization': 'Bearer ' + token
            },
            success: function(response) {
                $('#productLoading').hide();
                if (response.status === 'success') {
                    products = response.data;
                    console.log('Products loaded:', products);
                    populateProductDropdown();
                } else {
                    showMessage('Failed to load products: ' + (response.message || 'Unknown error'), 'error');
                }
            },
            error: function(xhr) {
                $('#productLoading').html('<small class="text-danger"><i class="fas fa-exclamation-circle"></i> Failed to load products</small>');
                console.error('Error loading products:', xhr.responseText);
            }
        });
    }

    function populateProductDropdown() {
        const dropdown = $('#product_id');
        dropdown.empty();
        
        if (products.length === 0) {
            dropdown.append('<option value="">No products available</option>');
            return;
        }
        
        dropdown.append('<option value="">Select a product</option>');
        
        products.forEach(product => {
            // Convert rate and qty to numbers
            const rate = parseFloat(product.rate) || 0;
            const qty = parseInt(product.qty) || 0;
            
            if (qty > 0) { // Only show products in stock
                dropdown.append(`<option value="${product.product_id}" data-price="${rate}" data-stock="${qty}">${product.name} - ₹${rate.toFixed(2)} (Stock: ${qty})</option>`);
            } else {
                dropdown.append(`<option value="${product.product_id}" data-price="${rate}" data-stock="0" disabled>${product.name} - ₹${rate.toFixed(2)} (Out of Stock)</option>`);
            }
        });
        
        // Add change event to update stock info
        dropdown.change(function() {
            const selectedOption = $(this).find('option:selected');
            const stock = parseInt(selectedOption.data('stock')) || 0;
            if (stock !== undefined) {
                $('#stockInfo').html(`Available stock: ${stock} units`);
                $('#qty').attr('max', stock);
                if (stock === 0) {
                    $('#qty').prop('disabled', true);
                    $('#purchaseBtn').prop('disabled', true).html('<i class="fas fa-times-circle"></i> Out of Stock');
                } else {
                    $('#qty').prop('disabled', false);
                    $('#purchaseBtn').prop('disabled', false).html('<i class="fas fa-check-circle"></i> Confirm Purchase');
                }
            }
        });
    }

    function updatePriceInfo() {
        const qty = parseInt($('#qty').val()) || 1;
        const customerType = '{{ session("customer_type") }}';
        
        if (!selectedProduct) return;
        
        // Convert rate to number
        const originalPrice = parseFloat(selectedProduct.rate) || 0;
        let finalPrice = originalPrice;
        let discount = 0;
        let discountPercent = 0;
        
        // Check if diamond customer and product has offer
        if (customerType === 'diamond' && selectedProduct.offer_id) {
            // Get offer details
            const token = '{{ session("token") }}';
            $.ajax({
                url: API_BASE_URL + '/products/' + selectedProduct.product_id,
                method: 'GET',
                headers: {
                    'Authorization': 'Bearer ' + token
                },
                success: function(response) {
                    if (response.status === 'success') {
                        discountPercent = parseFloat(response.data.discount_percentage) || 0;
                        discount = (originalPrice * discountPercent) / 100;
                        finalPrice = originalPrice - discount;
                        
                        displayPriceInfo(originalPrice, discount, discountPercent, finalPrice, qty);
                    }
                },
                error: function() {
                    // If error, show without discount
                    displayPriceInfo(originalPrice, 0, 0, originalPrice, qty);
                }
            });
        } else {
            displayPriceInfo(originalPrice, 0, 0, originalPrice, qty);
        }
    }

    function displayPriceInfo(originalPrice, discount, discountPercent, finalPrice, qty) {
        $('#originalPrice').text('₹' + (originalPrice * qty).toFixed(2));
        $('#discountAmount').text('₹' + (discount * qty).toFixed(2));
        $('#discountPercent').text(discountPercent + '%');
        $('#finalPrice').text('₹' + (finalPrice * qty).toFixed(2));
        $('#priceInfo').show();
    }

    function updateSummary() {
        const qty = parseInt($('#qty').val()) || 1;
        const selectedOption = $('#product_id').find('option:selected');
        const stock = parseInt(selectedOption.data('stock')) || 0;
        
        if (!selectedProduct) return;
        
        // Convert rate to number
        const productRate = parseFloat(selectedProduct.rate) || 0;
        
        let finalPrice = 0;
        if ($('#finalPrice').text().includes('₹')) {
            finalPrice = parseFloat($('#finalPrice').text().replace('₹', '')) || 0;
        } else {
            finalPrice = productRate * qty;
        }
        
        const html = `
            <table class="table table-sm">
                <tr>
                    <td>Product:</td>
                    <td><strong>${selectedProduct.name}</strong></td>
                </tr>
                <tr>
                    <td>Quantity:</td>
                    <td><strong>${qty}</strong></td>
                </tr>
                <tr>
                    <td>Unit Price:</td>
                    <td>₹${productRate.toFixed(2)}</td>
                </tr>
                <tr>
                    <td>Total Price:</td>
                    <td><strong class="text-success">₹${finalPrice.toFixed(2)}</strong></td>
                </tr>
                <tr>
                    <td>Available Stock:</td>
                    <td>${stock} units</td>
                </tr>
                @if(session('customer_type') === 'diamond')
                <tr>
                    <td colspan="2" class="text-center">
                        <span class="badge diamond-badge">
                            <i class="fas fa-gem"></i> Diamond Discount Applied
                        </span>
                    </td>
                </tr>
                @endif
            </table>
            <div class="alert alert-info">
                <small><i class="fas fa-info-circle"></i> 
                @if(session('customer_type') === 'diamond')
                    You will receive special discounts as a Diamond customer.
                @else
                    As a Gold customer, you can make purchases but no discounts are applied.
                @endif
                </small>
            </div>`;
        
        $('#summaryContent').html(html);
    }

    function makePurchase() {
        const productId = $('#product_id').val();
        const qty = $('#qty').val();
        
        if (!productId) {
            showMessage('Please select a product', 'warning');
            return;
        }
        
        if (qty < 1) {
            showMessage('Quantity must be at least 1', 'warning');
            return;
        }
        
        const selectedOption = $('#product_id').find('option:selected');
        const stock = parseInt(selectedOption.data('stock')) || 0;
        
        if (qty > stock) {
            showMessage('Quantity exceeds available stock. Available: ' + stock + ' units', 'warning');
            return;
        }
        
        const token = '{{ session("token") }}';
        const submitBtn = $('#purchaseBtn');
        const originalText = submitBtn.html();
        
        submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Processing...').prop('disabled', true);
        $('#purchaseMessage').empty();
        
        $.ajax({
            url: API_BASE_URL + '/purchase',
            method: 'POST',
            headers: {
                'Authorization': 'Bearer ' + token,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            data: JSON.stringify({
                product_id: parseInt(productId),
                qty: parseInt(qty)
            }),
            success: function(response) {
                if (response.status === 'success') {
                    showMessage('Purchase successful! Purchase ID: ' + response.data.id, 'success');
                    
                    // Reset form
                    $('#purchaseForm')[0].reset();
                    $('#priceInfo').hide();
                    $('#summaryContent').html('<p class="text-center text-muted">Select a product to see purchase summary</p>');
                    $('#stockInfo').empty();
                    
                    // Reload products and recent purchases
                    loadProducts();
                    loadRecentPurchases();
                    
                    // Show receipt details
                    if (selectedProduct) {
                        const receiptHtml = `
                            <div class="alert alert-success">
                                <h5><i class="fas fa-check-circle"></i> Purchase Confirmed!</h5>
                                <p><strong>Product:</strong> ${selectedProduct.name}</p>
                                <p><strong>Quantity:</strong> ${qty}</p>
                                <p><strong>Total Amount:</strong> ₹${parseFloat(response.data.total_value || 0).toFixed(2)}</p>
                                <p><strong>Purchase ID:</strong> ${response.data.id}</p>
                                <p><strong>Date:</strong> ${new Date().toLocaleDateString()}</p>
                            </div>`;
                        $('#purchaseMessage').html(receiptHtml);
                    }
                } else {
                    $('#purchaseMessage').html(`
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            ${response.message || 'Purchase failed'}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    `);
                }
                submitBtn.html(originalText).prop('disabled', false);
            },
            error: function(xhr) {
                let errorMessage = 'An error occurred during purchase';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                    const errors = xhr.responseJSON.errors;
                    errorMessage = '';
                    for (const field in errors) {
                        errorMessage += errors[field].join('<br>') + '<br>';
                    }
                } else if (xhr.status === 403) {
                    errorMessage = 'You are not authorized to make purchases. Only Gold and Diamond customers can purchase.';
                } else if (xhr.status === 400) {
                    errorMessage = 'Bad request. Please check your input.';
                }
                
                $('#purchaseMessage').html(`
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        ${errorMessage}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `);
                submitBtn.html(originalText).prop('disabled', false);
            }
        });
    }

    function loadRecentPurchases() {
        const token = '{{ session("token") }}';
        
        $.ajax({
            url: API_BASE_URL + '/purchases/history',
            method: 'GET',
            headers: {
                'Authorization': 'Bearer ' + token
            },
            success: function(response) {
                if (response.status === 'success') {
                    const purchases = response.data.slice(0, 5);
                    let html = '';
                    
                    if (purchases.length > 0) {
                        html = '<div class="list-group list-group-flush">';
                        purchases.forEach(purchase => {
                            const date = new Date(purchase.date).toLocaleDateString();
                            const time = new Date(purchase.date).toLocaleTimeString();
                            html += `
                                <div class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">${purchase.product ? purchase.product.name : 'Product'}</h6>
                                        <small>${date}</small>
                                    </div>
                                    <p class="mb-1">Qty: ${purchase.qty} | ₹${parseFloat(purchase.total_value || 0).toFixed(2)}</p>
                                    <small class="text-muted">${time}</small>
                                </div>`;
                        });
                        html += '</div>';
                    } else {
                        html = '<p class="text-center text-muted">No purchases yet. Make your first purchase!</p>';
                    }
        
                    $('#recentPurchases').html(html);
                }
            },
            error: function(xhr) {
                $('#recentPurchases').html('<p class="text-center text-danger">Failed to load recent purchases</p>');
            }
        });
    }
</script>
@endsection
@endsection