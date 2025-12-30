@extends('layouts.app')

@section('title', 'Products')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2"><i class="fas fa-shopping-bag"></i> Products</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <button class="btn btn-sm btn-outline-secondary" onclick="refreshProducts()">
                <i class="fas fa-sync-alt"></i> Refresh
            </button>
        </div>
    </div>

    <!-- Products Filter -->
    <div class="row mb-3">
        <div class="col-md-4">
            <input type="text" id="searchProduct" class="form-control" placeholder="Search products...">
        </div>
        <div class="col-md-4">
            <select id="sortProducts" class="form-select">
                <option value="name_asc">Sort by Name (A-Z)</option>
                <option value="name_desc">Sort by Name (Z-A)</option>
                <option value="price_asc">Sort by Price (Low to High)</option>
                <option value="price_desc">Sort by Price (High to Low)</option>
            </select>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="row" id="productsContainer">
        <div class="col-12 text-center">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p>Loading products...</p>
        </div>
    </div>

    <!-- Product Detail Modal -->
    <div class="modal fade" id="productDetailModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Product Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="productDetailContent">
                    <!-- Content loaded via AJAX -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    let allProducts = [];

    $(document).ready(function() {
        loadProducts();
        
        // Search functionality
        $('#searchProduct').on('keyup', function() {
            filterProducts();
        });
        
        // Sort functionality
        $('#sortProducts').on('change', function() {
            filterProducts();
        });
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
                if (response.status === 'success') {
                    allProducts = response.data;
                    displayProducts(allProducts);
                } else {
                    showMessage('Failed to load products', 'error');
                }
            },
            error: function(xhr) {
                showMessage('Error loading products: ' + (xhr.responseJSON?.message || 'Network error'), 'error');
            }
        });
    }

    function displayProducts(products) {
        const container = $('#productsContainer');
        let html = '';
        
        if (products.length === 0) {
            html = '<div class="col-12"><p class="text-center text-muted">No products found.</p></div>';
        } else {
            products.forEach(product => {
                const hasOffer = product.offer_id !== null;
                const isDiamond = '{{ session("customer_type") }}' === 'diamond';
                
                html += `
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <div class="card-header bg-light">
                                <h5 class="card-title mb-0">${product.name}</h5>
                            </div>
                            <div class="card-body">
                                <p class="card-text">
                                    <strong>Price:</strong> ₹${product.rate}<br>
                                    <strong>Stock:</strong> ${product.qty} units<br>
                                    ${hasOffer ? '<span class="badge bg-success">Special Offer Available</span>' : ''}
                                </p>
                            </div>
                            <div class="card-footer">
                                <button class="btn btn-sm btn-info" onclick="viewProductDetail(${product.product_id})">
                                    <i class="fas fa-info-circle"></i> Details
                                </button>
                                ${isDiamond && hasOffer ? 
                                    '<span class="badge bg-warning text-dark float-end">Diamond Discount</span>' : ''}
                            </div>
                        </div>
                    </div>`;
            });
        }
        
        container.html(html);
    }

    function filterProducts() {
        const searchTerm = $('#searchProduct').val().toLowerCase();
        const sortValue = $('#sortProducts').val();
        
        let filteredProducts = allProducts.filter(product => 
            product.name.toLowerCase().includes(searchTerm) ||
            product.rate.toString().includes(searchTerm)
        );
        
        // Sort products
        filteredProducts.sort((a, b) => {
            switch(sortValue) {
                case 'name_asc':
                    return a.name.localeCompare(b.name);
                case 'name_desc':
                    return b.name.localeCompare(a.name);
                case 'price_asc':
                    return a.rate - b.rate;
                case 'price_desc':
                    return b.rate - a.rate;
                default:
                    return 0;
            }
        });
        
        displayProducts(filteredProducts);
    }

    function viewProductDetail(productId) {
        const token = '{{ session("token") }}';
        const isDiamond = '{{ session("customer_type") }}' === 'diamond';
        
        $.ajax({
            url: API_BASE_URL + '/products/' + productId,
            method: 'GET',
            headers: {
                'Authorization': 'Bearer ' + token
            },
            success: function(response) {
                if (response.status === 'success') {
                    const product = response.data.product;
                    const offer = product.offer;
                    const originalPrice = response.data.original_price;
                    const discountedPrice = response.data.discounted_price;
                    const hasDiscount = response.data.has_discount;
                    
                    let html = `
                        <div class="row">
                            <div class="col-md-12">
                                <h4>${product.name}</h4>
                                <hr>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Product ID:</strong> ${product.product_id}</p>
                                <p><strong>Stock Available:</strong> ${product.qty} units</p>
                                <p><strong>Original Price:</strong> ₹${originalPrice.toFixed(2)}</p>`;
                    
                    if (isDiamond && hasDiscount) {
                        html += `
                                <p><strong>Discounted Price:</strong> 
                                    <span class="text-success">₹${discountedPrice.toFixed(2)}</span>
                                    <span class="badge bg-danger ms-2">${response.data.discount_percentage}% OFF</span>
                                </p>
                                <p class="text-muted">You save: ₹${(originalPrice - discountedPrice).toFixed(2)}</p>`;
                    } else {
                        html += `<p><strong>Final Price:</strong> ₹${originalPrice.toFixed(2)}</p>`;
                    }
                    
                    if (offer) {
                        html += `
                                <div class="alert alert-info mt-3">
                                    <strong>Special Offer:</strong><br>
                                    ${offer.percentage}% Discount<br>
                                    <small>${offer.details}</small>
                                </div>`;
                    }
                    
                    html += `</div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <strong>Customer Information</strong>
                                    </div>
                                    <div class="card-body">
                                        <p>Customer Type: <span class="badge ${isDiamond ? 'diamond-badge' : 'silver-badge'}">
                                            {{ ucfirst(session('customer_type')) }}
                                        </span></p>`;
                    
                    if (isDiamond && hasDiscount) {
                        html += `<p class="text-success"><i class="fas fa-star"></i> You are eligible for special discount!</p>`;
                    } else if (isDiamond && !hasDiscount) {
                        html += `<p class="text-muted">This product has no special offers</p>`;
                    } else {
                        html += `<p class="text-muted">Only Diamond customers get special discounts</p>`;
                    }
                    
                    html += `</div></div></div></div>`;
                    
                    $('#productDetailContent').html(html);
                    $('#productDetailModal').modal('show');
                }
            },
            error: function(xhr) {
                showMessage('Error loading product details', 'error');
            }
        });
    }

    function refreshProducts() {
        $('#productsContainer').html(`
            <div class="col-12 text-center">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p>Refreshing products...</p>
            </div>
        `);
        loadProducts();
    }
</script>
@endsection
@endsection