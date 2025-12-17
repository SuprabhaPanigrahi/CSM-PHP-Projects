<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Not Found</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .error-container {
            background: white;
            border-radius: 20px;
            padding: 50px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            max-width: 600px;
        }
        .error-icon {
            font-size: 80px;
            color: #dc3545;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-icon">❌</div>
        <h1 class="text-danger mb-4">Oops! Page Not Found</h1>
        
        <div class="alert alert-warning mb-4">
            <p class="mb-2">The page you are looking for does not exist.</p>
            <p class="mb-0"><strong>Invalid URL requested.</strong></p>
        </div>
        
        <p class="text-muted mb-4">
            Please check the URL or go back to the home page.
        </p>
        
        <a href="{{ route('home') }}" class="btn btn-primary btn-lg">
            Go to Home Page
        </a>
    </div>
</body>
</html>