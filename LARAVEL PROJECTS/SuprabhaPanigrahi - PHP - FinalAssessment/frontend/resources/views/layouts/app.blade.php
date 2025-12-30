<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            padding-top: 20px;
        }

        .navbar-brand {
            font-weight: bold;
        }

        .card {
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .loader {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #3498db;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 20px auto;
            display: none;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        #authMenu {
            margin-left: auto;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="/">EMS</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto" id="mainMenu">
                    <li class="nav-item">
                        <a class="nav-link" href="/dashboard">Dashboard</a>
                    </li>
                </ul>
                <ul class="navbar-nav" id="authMenu">
                    <!-- Will be populated by JavaScript -->
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div id="loader" class="loader"></div>
        @yield('content')
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Global configuration
        const API_BASE_URL = 'http://localhost:8080/api';

        // Show/hide loader
        function showLoader() {
            $("#loader").show();
        }

        function hideLoader() {
            $("#loader").hide();
        }

        // Check if user is logged in
        function isLoggedIn() {
            return localStorage.getItem("token") !== null;
        }

        // Get user role from token
        function getUserRole() {
            const token = localStorage.getItem("token");
            if (!token) return null;

            try {
                const base64Url = token.split('.')[1];
                const base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
                const jsonPayload = decodeURIComponent(atob(base64).split('').map(function(c) {
                    return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
                }).join(''));

                const payload = JSON.parse(jsonPayload);
                return payload.role;
            } catch (e) {
                console.error("Error decoding token:", e);
                return null;
            }
        }

        // Get username from token
        function getUsername() {
            const token = localStorage.getItem("token");
            if (!token) return null;

            try {
                const base64Url = token.split('.')[1];
                const base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
                const jsonPayload = decodeURIComponent(atob(base64).split('').map(function(c) {
                    return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
                }).join(''));

                const payload = JSON.parse(jsonPayload);
                return payload.username;
            } catch (e) {
                console.error("Error decoding token:", e);
                return null;
            }
        }

        // Update menu based on authentication
        function updateMenu() {
            const authMenu = $("#authMenu");
            const mainMenu = $("#mainMenu");

            mainMenu.find("li:not(:first)").remove();
            authMenu.empty();

            if (isLoggedIn()) {
                const role = getUserRole();
                const username = getUsername();

                // ADMIN MENU
                if (role === 'admin') {
                    mainMenu.append(`
                        <li class="nav-item">
                            <a class="nav-link" href="/employees">View Employees</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/projects">View Projects</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/assign">Assign Project</a>
                        </li>
                    `);
                }

                // MANAGER MENU
                if (role === 'manager') {
                    mainMenu.append(`
                        <li class="nav-item">
                            <a class="nav-link" href="/assign">Assign Project</a>
                        </li>
                    `);
                }

    //             // EMPLOYEE MENU
    //             if (role === 'employee') {
    //                 mainMenu.append(`
    //     <li class="nav-item">
    //         <a class="nav-link" href="/dashboard">Dashboard</a>
    //     </li>
    // `);
    //             }

                // COMMON MENU FOR ALL ROLES
                mainMenu.append(`
                    <li class="nav-item">
                        <a class="nav-link" href="/assignments">View Assignments</a>
                    </li>
                `);

                // Add user dropdown
                authMenu.append(`
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            ${username || 'User'} (${role})
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#" onclick="logout()">Logout</a></li>
                        </ul>
                    </li>
                `);
            } else {
                authMenu.append(`
                    <li class="nav-item">
                        <a class="nav-link" href="/login">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/register">Register</a>
                    </li>
                `);
            }
        }

        // Logout function
        function logout() {
            localStorage.removeItem("token");
            localStorage.removeItem("username");
            localStorage.removeItem("userRole");
            localStorage.removeItem("userId");
            window.location.href = "/login";
        }

        // Setup AJAX to include token in all requests
        $(document).ajaxSend(function(event, xhr) {
            const token = localStorage.getItem("token");
            if (token) {
                xhr.setRequestHeader('Authorization', 'Bearer ' + token);
            }
        });

        // Handle AJAX errors globally
        $(document).ajaxError(function(event, xhr) {
            if (xhr.status === 401) {
                alert("Session expired. Please login again.");
                logout();
            }
        });

        // Update menu on page load
        $(document).ready(function() {
            updateMenu();
        });
    </script>
    @stack('scripts')
</body>

</html>