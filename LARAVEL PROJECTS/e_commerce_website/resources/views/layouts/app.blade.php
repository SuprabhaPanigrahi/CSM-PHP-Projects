<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'ShopEasy' }}</title>
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- CSRF Token for AJAX -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <style>
        :root {
            --primary-color: #6366f1;
            --secondary-color: #f59e0b;
            --dark-color: #1f2937;
            --light-color: #f9fafb;
        }
        
        * {
            font-family: 'Poppins', sans-serif;
        }
        
        body {
            background-color: #f8fafc;
            color: #374151;
        }
        
        /* Cart badge */
        .cart-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            font-size: 0.7rem;
            padding: 3px 8px;
        }
        
        /* Product card styling */
        .product-card {
            transition: all 0.3s ease;
            border: 1px solid #e5e7eb;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        /* Add to cart button */
        .add-to-cart {
            padding: 5px 12px;
            font-size: 0.875rem;
        }
        
        /* Notification alert */
        .cart-notification {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
            animation: slideIn 0.3s ease;
        }
        
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
    </style>
</head>
<body>
    @include('layouts.header')
    
    <main class="py-4">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="bi bi-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="bi bi-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @yield('content')
        </div>
    </main>
    
    @include('layouts.footer')
    
    <script>
        // Update cart count
        function updateCartCount() {
            fetch('{{ route("cart.count") }}')
                .then(response => response.json())
                .then(data => {
                    const cartCount = document.getElementById('cart-count');
                    if (cartCount) {
                        cartCount.textContent = data.count;
                        cartCount.style.display = data.count > 0 ? 'block' : 'none';
                    }
                })
                .catch(error => console.error('Error:', error));
        }
        
        // Show notification
        function showNotification(message, type = 'success') {
            const notification = document.createElement('div');
            notification.className = `alert alert-${type} alert-dismissible fade show cart-notification`;
            notification.innerHTML = `
                <i class="bi bi-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            document.body.appendChild(notification);
            
            // Remove notification after 3 seconds
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, 3000);
        }
        
        // Add to cart functionality
        function addToCart(productId, productName, productPrice, productImage) {
            const formData = new FormData();
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
            formData.append('name', productName);
            formData.append('price', productPrice);
            formData.append('image', productImage);
            
            fetch(`/cart/add/${productId}`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    updateCartCount();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Failed to add product to cart', 'danger');
            });
        }
        
        // Initialize when page loads
        document.addEventListener('DOMContentLoaded', function() {
            // Update cart count initially
            updateCartCount();
            
            // Handle add to cart buttons
            document.querySelectorAll('.add-to-cart').forEach(button => {
                button.addEventListener('click', function() {
                    const productId = this.dataset.productId;
                    const productName = this.dataset.productName;
                    const productPrice = this.dataset.productPrice;
                    const productImage = this.dataset.productImage;
                    
                    // Add loading state
                    const originalHTML = this.innerHTML;
                    this.innerHTML = '<i class="bi bi-arrow-repeat spin me-1"></i> Adding...';
                    this.disabled = true;
                    
                    addToCart(productId, productName, productPrice, productImage);
                    
                    // Reset button after 1 second
                    setTimeout(() => {
                        this.innerHTML = originalHTML;
                        this.disabled = false;
                    }, 1000);
                });
            });
        });
    </script>
    
    @yield('scripts')
</body>
</html>