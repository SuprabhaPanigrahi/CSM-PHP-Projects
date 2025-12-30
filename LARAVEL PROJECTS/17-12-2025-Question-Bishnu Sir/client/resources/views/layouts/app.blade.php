<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Customer Portal')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        body {
            background-color: #f8f9fa;
        }
        .sidebar {
            min-height: 100vh;
            background-color: #343a40;
            color: white;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            display: block;
            transition: all 0.3s;
        }
        .sidebar a:hover {
            background-color: #495057;
            padding-left: 20px;
        }
        .sidebar a.disabled {
            color: #6c757d;
            cursor: not-allowed;
        }
        .sidebar a.disabled:hover {
            background-color: transparent;
            padding-left: 15px;
        }
        .main-content {
            padding: 20px;
        }
        .card {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            margin-bottom: 20px;
        }
        .badge-customer {
            font-size: 0.8em;
            padding: 5px 10px;
        }
        .silver-badge { background-color: #c0c0c0; color: #000; }
        .gold-badge { background-color: #ffd700; color: #000; }
        .diamond-badge { background-color: #b9f2ff; color: #000; }
        .message-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
        }
        .permission-hint {
            font-size: 0.8em;
            color: #6c757d;
            margin-left: 5px;
        }
        .access-icon {
            width: 20px;
            display: inline-block;
            text-align: center;
            margin-right: 5px;
        }
        .access-allowed { color: #28a745; }
        .access-denied { color: #dc3545; }
        .menu-loading {
            text-align: center;
            padding: 20px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    @if(session()->has('token'))
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-2 d-md-block sidebar bg-dark">
                <div class="position-sticky pt-3">
                    <h4 class="text-center mb-4">
                        <i class="fas fa-user-circle"></i> 
                        <span id="customerName">{{ session('customer_name', 'Customer') }}</span>
                    </h4>
                    <p class="text-center">
                        @php
                            $type = session('customer_type', 'silver');
                            $badgeClass = $type . '-badge';
                        @endphp
                        <span class="badge badge-customer {{ $badgeClass }}">
                            {{ ucfirst($type) }} Customer
                        </span>
                    </p>
                    <hr class="bg-light">
                    
                    <!-- Menu will be loaded dynamically -->
                    <ul class="nav flex-column" id="sidebarMenu">
                        <div class="menu-loading">
                            <div class="spinner-border spinner-border-sm text-light" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p>Loading menu...</p>
                        </div>
                    </ul>
                    
                    <!-- Logout Button (Always visible) -->
                    <hr class="bg-light">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a href="#" class="nav-link" onclick="logout()">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="col-md-10 ms-sm-auto main-content">
                <div class="message-container"></div>
                @yield('content')
            </main>
        </div>
    </div>
    @else
        @yield('auth-content')
    @endif

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
        // CSRF Token setup for AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Global API base URL
        const API_BASE_URL = 'http://localhost:8080/api';
        
        // Global variables
        let customerType = '{{ session("customer_type") }}';
        let menuItems = [];

        // Global function to show messages
        function showMessage(message, type = 'success') {
            const container = $('.message-container');
            const alertClass = type === 'success' ? 'alert-success' : 
                              type === 'error' ? 'alert-danger' : 
                              type === 'warning' ? 'alert-warning' : 'alert-info';
            
            const alertDiv = $(`
                <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `);
            
            container.append(alertDiv);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                alertDiv.alert('close');
            }, 5000);
        }

        // Handle API errors globally
        function handleApiError(xhr) {
            console.error('API Error:', xhr);
            
            if (xhr.status === 401) {
                // Unauthorized - redirect to login
                window.location.href = '/?message=' + encodeURIComponent('Session expired. Please login again.');
            } else if (xhr.status === 403) {
                // Forbidden - show access denied page
                const message = xhr.responseJSON?.message || 'Access denied';
                window.location.href = '/error/403?message=' + encodeURIComponent(message);
            } else if (xhr.status === 404) {
                showMessage('Resource not found', 'error');
            } else if (xhr.status === 500) {
                showMessage('Server error. Please try again later.', 'error');
            } else {
                showMessage('An error occurred: ' + (xhr.responseJSON?.message || 'Unknown error'), 'error');
            }
        }

        // Load menu from API
        function loadMenu() {
            const token = '{{ session("token") }}';
            
            $.ajax({
                url: API_BASE_URL + '/menu',
                method: 'GET',
                headers: {
                    'Authorization': 'Bearer ' + token
                },
                success: function(response) {
                    if (response.status === 'success') {
                        menuItems = response.data;
                        renderMenu(menuItems);
                    } else {
                        showMessage('Failed to load menu', 'error');
                    }
                },
                error: function(xhr) {
                    handleApiError(xhr);
                }
            });
        }

        // Render menu items
        function renderMenu(menuItems) {
            const menuContainer = $('#sidebarMenu');
            menuContainer.empty();
            
            if (menuItems.length === 0) {
                menuContainer.html('<p class="text-center text-muted">No menu items available</p>');
                return;
            }
            
            let html = '';
            menuItems.forEach(menu => {
                const routeName = menu.route;
                const routeUrl = getRouteUrl(routeName);
                
                html += `
                    <li class="nav-item">
                        <a href="${routeUrl}" class="nav-link menu-link" data-route="${routeName}">
                            <i class="${menu.icon}"></i> ${menu.name}
                            <span class="permission-hint">${getPermissionHint(menu.customer_types)}</span>
                        </a>
                    </li>`;
            });
            
            menuContainer.html(html);
            
            // Add click handlers for menu items
            $('.menu-link').click(function(e) {
                e.preventDefault();
                const route = $(this).data('route');
                const url = $(this).attr('href');
                checkAndNavigate(route, url);
            });
        }

        // Get route URL from route name
        function getRouteUrl(routeName) {
            const routes = {
                'dashboard': '{{ route("dashboard") }}',
                'products': '{{ route("products") }}',
                'offers': '{{ route("offers") }}',
                'purchase': '{{ route("purchase") }}',
                'purchase.history': '{{ route("purchase.history") }}'
            };
            return routes[routeName] || '#';
        }

        // Get permission hint text
        function getPermissionHint(customerTypes) {
            const types = customerTypes.split(',');
            if (types.length === 3) return 'All';
            if (types.includes('diamond') && types.length === 1) return 'Diamond';
            if (types.includes('gold') && types.includes('diamond')) return 'Gold & Diamond';
            return types.join(', ');
        }

        // Check access before navigation
        function checkAndNavigate(route, url) {
            const token = '{{ session("token") }}';
            
            // Show loading
            showMessage('Checking access...', 'info');
            
            $.ajax({
                url: API_BASE_URL + '/check-access',
                method: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Content-Type': 'application/json'
                },
                data: JSON.stringify({ route: route }),
                success: function(response) {
                    if (response.status === 'success' && response.data.can_access) {
                        // Access granted - navigate to page
                        window.location.href = url;
                    } else {
                        // Access denied - show error page
                        const message = 'You do not have permission to access this page.';
                        window.location.href = '/error/403?message=' + encodeURIComponent(message);
                    }
                },
                error: function(xhr) {
                    handleApiError(xhr);
                }
            });
        }

        // Logout function
        function logout() {
            const token = '{{ session("token") }}';
            
            $.ajax({
                url: API_BASE_URL + '/logout',
                method: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + token
                },
                success: function(response) {
                    // Clear client session
                    $.post('/clear-session', function() {
                        window.location.href = '/';
                    });
                },
                error: function(xhr) {
                    console.error('Logout error:', xhr.responseText);
                    // Clear session anyway
                    $.post('/clear-session', function() {
                        window.location.href = '/';
                    });
                }
            });
        }

        // Check session on page load
        $(document).ready(function() {
            @if(session()->has('token'))
                // Verify token on page load
                const token = '{{ session("token") }}';
                $.ajax({
                    url: API_BASE_URL + '/me',
                    method: 'GET',
                    headers: {
                        'Authorization': 'Bearer ' + token
                    },
                    success: function(response) {
                        // Token is valid
                        if (response.status === 'success') {
                            $('#customerName').text(response.customer.customer_name);
                            // Load menu after verifying token
                            loadMenu();
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 401) {
                            // Token expired or invalid
                            showMessage('Session expired. Please login again.', 'warning');
                            setTimeout(() => {
                                $.post('/clear-session', function() {
                                    window.location.href = '/';
                                });
                            }, 2000);
                        } else {
                            handleApiError(xhr);
                        }
                    }
                });
            @endif
        });
    </script>
    
    @yield('scripts')
</body>
</html>