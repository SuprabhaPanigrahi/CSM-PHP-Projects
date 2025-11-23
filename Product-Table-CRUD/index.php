<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (
        ($username === 'ram' && $password === 'ram123') ||
        ($username === 'sita' && $password === 'sita123') ||
        ($username === 'shree' && $password === 'shree123')
    ) {
        header('Location: pages/dashboard.php');
        exit;
    } else {
        $error = "Invalid username or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Inventory Login</title>
    <link rel="stylesheet" href="bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container mt-5 p-4 bg-white shadow-sm rounded" style="max-width: 400px;">
        <h2 class="text-center mb-4">Product Inventory Login</h2>

        <!-- Show error if login fails -->
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger text-center"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form action="" method="post" id="formID">
            <div class="mb-3">
                <label for="username" class="form-label">Username:</label>
                <input type="text" id="username" name="username" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password:</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>

            <div class="form-check mb-3">
                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                <label class="form-check-label" for="remember">Remember Me</label>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-info">Login</button>
            </div>
        </form>
    </div>
</body>
</html>
