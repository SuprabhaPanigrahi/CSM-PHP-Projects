@extends('layouts.app')

@section('title', 'Offers')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2"><i class="fas fa-percent"></i> Special Offers</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <span class="badge diamond-badge">
                <i class="fas fa-gem"></i> Diamond Customer Exclusive
            </span>
        </div>
    </div>

    <!-- Offers Grid -->
    <div class="row" id="offersContainer">
        <div class="col-12 text-center">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p>Loading special offers...</p>
        </div>
    </div>

    <!-- Products with this offer -->
    <div class="row mt-4 d-none" id="offerProductsSection">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-shopping-bag"></i> Products with this Offer</h5>
                </div>
                <div class="card-body">
                    <div id="offerProductsContainer"></div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    $(document).ready(function() {
        loadOffers();
    });

    function loadOffers() {
        const token = '{{ session("token") }}';
        
        $.ajax({
            url: API_BASE_URL + '/offers',
            method: 'GET',
            headers: {
                'Authorization': 'Bearer ' + token
            },
            success: function(response) {
                if (response.status === 'success') {
                    displayOffers(response.data);
                } else {
                    showMessage('Failed to load offers', 'error');
                }
            },
            error: function(xhr) {
                if (xhr.status === 403) {
                    showMessage('Only Diamond customers can view offers', 'warning');
                    setTimeout(() => {
                        window.location.href = '{{ route("dashboard") }}';
                    }, 2000);
                } else {
                    showMessage('Error loading offers', 'error');
                }
            }
        });
    }

    function displayOffers(offers) {
        const container = $('#offersContainer');
        let html = '';
        
        if (offers.length === 0) {
            html = '<div class="col-12"><p class="text-center text-muted">No special offers available.</p></div>';
        } else {
            offers.forEach(offer => {
                html += `
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <div class="card-header bg-warning">
                                <h5 class="card-title mb-0 text-dark">
                                    <i class="fas fa-tag"></i> ${offer.percentage}% OFF
                                </h5>
                            </div>
                            <div class="card-body">
                                <p class="card-text">${offer.details}</p>
                                <div class="mt-3">
                                    <span class="badge bg-info">Offer ID: ${offer.id}</span>
                                    <span class="badge bg-dark ms-1">Diamond Exclusive</span>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button class="btn btn-sm btn-outline-primary" onclick="viewOfferProducts(${offer.id})">
                                    <i class="fas fa-eye"></i> View Products
                                </button>
                            </div>
                        </div>
                    </div>`;
            });
        }
        
        container.html(html);
    }

    function viewOfferProducts(offerId) {
        const token = '{{ session("token") }}';
        
        // First load all products
        $.ajax({
            url: API_BASE_URL + '/products',
            method: 'GET',
            headers: {
                'Authorization': 'Bearer ' + token
            },
            success: function(response) {
                if (response.status === 'success') {
                    // Filter products with this offer
                    const productsWithOffer = response.data.filter(product => 
                        product.offer_id == offerId
                    );
                    
                    displayOfferProducts(productsWithOffer);
                    $('#offerProductsSection').removeClass('d-none');
                    $('html, body').animate({
                        scrollTop: $('#offerProductsSection').offset().top
                    }, 1000);
                }
            },
            error: function() {
                showMessage('Error loading products', 'error');
            }
        });
    }

    function displayOfferProducts(products) {
        const container = $('#offerProductsContainer');
        
        if (products.length === 0) {
            container.html('<p class="text-center text-muted">No products available with this offer.</p>');
            return;
        }
        
        let html = '<div class="table-responsive"><table class="table table-hover">';
        html += `
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Original Price</th>
                    <th>Discounted Price</th>
                    <th>You Save</th>
                    <th>Stock</th>
                </tr>
            </thead>
            <tbody>`;
        
        products.forEach(product => {
            const discount = (product.rate * product.offer.percentage) / 100;
            const discountedPrice = product.rate - discount;
            
            html += `
                <tr>
                    <td><strong>${product.name}</strong></td>
                    <td>₹${product.rate.toFixed(2)}</td>
                    <td class="text-success">₹${discountedPrice.toFixed(2)}</td>
                    <td class="text-danger">₹${discount.toFixed(2)} (${product.offer.percentage}%)</td>
                    <td>${product.qty} units</td>
                </tr>`;
        });
        
        html += '</tbody></table></div>';
        container.html(html);
    }
</script>
@endsection
@endsection