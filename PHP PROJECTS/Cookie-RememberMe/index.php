<?php
ob_start();

// AUTO-LOGIN IF COOKIE EXISTS
if (isset($_COOKIE['login_user'])) {
    header("Location: dashboard.php");
    exit();
}

// LOGIN SUBMISSION
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $valid_user = "user@example.com";
    $valid_pass = "123456";

    $email = $_POST['email'];
    $pass  = $_POST['password'];
    $remember = isset($_POST['remember']);

    if ($email === $valid_user && $pass === $valid_pass) {

        if ($remember) {
            setcookie("login_user", $email, time() + 3600, "/");
        }

        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid email or password!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-4">

            <div class="card p-4">
                <h3 class="text-center">Login</h3>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="text" name="email" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <div class="form-check mb-3">
                        <input type="checkbox" name="remember" class="form-check-input" id="remember">
                        <label for="remember" class="form-check-label">Remember Me</label>
                    </div>

                    <button class="btn btn-primary w-100">Login</button>
                </form>

            </div>
        </div>
    </div>
</div>

</body>
</html>
<?php ob_end_flush(); ?>
