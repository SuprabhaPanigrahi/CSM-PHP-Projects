<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center text-primary">Login</h1>
        <form action="#" id="formID">
            <input type="text" id="username" name="username" class="form-control mb-3" placeholder="Username">
            <input type="password" id="password" name="password" class="form-control mb-3" placeholder="Password">
            <input type="submit" class="btn btn-success w-100" value="Login">
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(document).ready(function(){
            $('#formID').on('submit', function(e){
                e.preventDefault();
                
                var username = $('#username').val();
                var password = $('#password').val();
                
                $.ajax({
                    url: '../process/process-login.php',
                    method: 'POST',
                    data: {username: username, password: password},
                    success: function(response){
                        if(response.trim() === 'login successful') {
                            alert('Login successful');
                            window.location.href = '../pages/adminPanel.php';
                        } else {
                            alert('Invalid credentials');
                        }
                    },
                    error: function(){
                        alert('Login failed - server error');
                    }
                });
            });
        });
    </script>
</body>
</html>