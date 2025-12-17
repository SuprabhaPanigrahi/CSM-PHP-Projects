<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Form Application')</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary-color: #667eea;
            --secondary-color: #764ba2;
            --success-color: #28a745;
        }

        body {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            min-height: 100vh;
            padding-top: 20px;
            padding-bottom: 20px;
        }

        .app-container {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            margin-bottom: 30px;
        }

        .nav-custom {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 30px;
        }

        .nav-custom .nav-link {
            color: white;
            font-weight: 500;
            padding: 10px 20px;
            border-radius: 8px;
            margin: 0 5px;
            transition: all 0.3s;
        }

        .nav-custom .nav-link:hover,
        .nav-custom .nav-link.active {
            background: rgba(255, 255, 255, 0.2);
            color: white;
        }

        .file-upload-area {
            border: 3px dashed #ddd;
            border-radius: 10px;
            padding: 40px 20px;
            text-align: center;
            background: #f8f9fa;
            transition: all 0.3s;
            cursor: pointer;
            position: relative;
        }

        .file-upload-area:hover {
            border-color: var(--primary-color);
            background: #f0f2ff;
        }

        .file-upload-input {
            position: absolute;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
            top: 0;
            left: 0;
        }

        .image-preview {
            max-width: 200px;
            max-height: 150px;
            border-radius: 8px;
            display: none;
            margin: 0 auto 10px;
        }

        .checkbox-group,
        .radio-group {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }

        .checkbox-item,
        .radio-item {
            flex: 1;
            min-width: 120px;
        }

        .custom-checkbox,
        .custom-radio {
            padding: 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
            text-align: center;
        }

        .custom-checkbox:hover,
        .custom-radio:hover {
            border-color: var(--primary-color);
            background: #f0f2ff;
        }

        .custom-checkbox input:checked~.form-check-label,
        .custom-radio input:checked~.form-check-label {
            color: var(--primary-color);
            font-weight: bold;
        }

        .custom-checkbox input:checked,
        .custom-radio input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .submission-card {
            transition: transform 0.3s, box-shadow 0.3s;
            border: 1px solid #dee2e6;
            border-radius: 10px;
            overflow: hidden;
        }

        .submission-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .interest-badge {
            margin-right: 5px;
            margin-bottom: 5px;
            background: var(--primary-color);
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s;
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .btn-success-custom {
            background: var(--success-color);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s;
        }

        .btn-success-custom:hover {
            background: #218838;
            color: white;
            transform: translateY(-2px);
        }

        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
        }

        .toast {
            border-radius: 10px;
            border: none;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
    </style>

    @stack('styles')
</head>

<body>
    <div class="container">
        <!-- Navigation -->
        <nav class="nav-custom">
            <ul class="nav justify-content-center">
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">
                        <i class="fas fa-home me-2"></i>Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('form') ? 'active' : '' }}" href="{{ url('/form') }}">
                        <i class="fas fa-edit me-2"></i>Submit Form
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('submissions') ? 'active' : '' }}" href="{{ url('/submissions') }}">
                        <i class="fas fa-database me-2"></i>View Submissions
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Toast Notifications -->
        <div class="toast-container"></div>

        <!-- Main Content -->
        <div class="app-container">
            @yield('content')
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery (for AJAX) -->
    @php
    $backendUrl = env('BACKEND_API_URL', 'http://127.0.0.1:5000/api');
    @endphp

    <script>
        // Backend API URL - Now using port 5000
        const API_BASE_URL = '{{ $backendUrl }}';

        // Toast notification function
        function showToast(message, type = 'success') {
            const toastContainer = document.querySelector('.toast-container');
            const toastId = 'toast-' + Date.now();

            const toastHTML = `
            <div id="${toastId}" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header bg-${type === 'success' ? 'success' : 'danger'} text-white">
                    <strong class="me-auto">
                        <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
                        ${type === 'success' ? 'Success' : 'Error'}
                    </strong>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
                </div>
                <div class="toast-body">
                    ${message}
                </div>
            </div>
        `;

            toastContainer.insertAdjacentHTML('beforeend', toastHTML);
            const toastElement = document.getElementById(toastId);
            const toast = new bootstrap.Toast(toastElement, {
                delay: 5000
            });
            toast.show();
        }

        // Handle API errors
        function handleApiError(error) {
            console.error('API Error:', error);
            if (error.response) {
                return `Server Error: ${error.response.status}`;
            } else if (error.request) {
                return 'Network Error: Cannot connect to backend API at ' + API_BASE_URL;
            } else {
                return `Error: ${error.message}`;
            }
        }
    </script>

    @stack('scripts')
</body>

</html>