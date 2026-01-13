<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product CRUD</title>

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container vh-100 d-flex justify-content-center align-items-center">
        <div class="card shadow p-4 text-center" style="max-width: 400px;">
            <h1 class="mb-3">Welcome</h1>
            <p class="text-muted">Welcome to Product CRUD Application</p>

            <a href="{{ route('login') }}" class="btn btn-primary w-100">
                Go to Login Page
            </a>
        </div>
    </div>

</body>
</html>
