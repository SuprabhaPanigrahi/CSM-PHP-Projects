<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body class="bg-light">
<div class="container mt-5 col-md-4">

    <h2 class="text-center mb-4">Admin Login</h2>

    <form id="loginForm">
        <input type="hidden" name="role" value="admin">

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>

    <div id="message" class="mt-3"></div>

</div>

<script>
$(document).ready(function() {
    $('#loginForm').submit(function(e) {
        e.preventDefault();
        
        // Show loading state
        $('button[type="submit"]').prop('disabled', true).text('Logging in...');
        $('#message').html('').removeClass('alert alert-danger alert-success');
        
        $.ajax({
            url: '../handler/ajax_login.php',
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    $('#message').html('<div class="alert alert-success">' + response.message + '</div>');
                    // Redirect to dashboard
                    setTimeout(function() {
                        window.location.href = response.redirect;
                    }, 1000);
                } else {
                    $('#message').html('<div class="alert alert-danger">' + response.message + '</div>');
                    $('button[type="submit"]').prop('disabled', false).text('Login');
                }
            },
            error: function(xhr, status, error) {
                $('#message').html('<div class="alert alert-danger">Login failed. Please try again.</div>');
                $('button[type="submit"]').prop('disabled', false).text('Login');
                console.error('Login error:', error);
            }
        });
    });
});
</script>
</body>
</html>