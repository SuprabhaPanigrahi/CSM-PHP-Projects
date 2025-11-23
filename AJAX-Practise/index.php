<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>Login Form</h1>
    <form action="">
        <label for="username">Username :</label>
        <input type="text" id="username" name="name" placeholder="enter your username">
        <br>
        <label for="password">Password :</label>
        <input type="password" id="password" name="password" placeholder="enter your password">
        <br>
        <input type="submit">
    </form>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            $('form').on('submit', function(e) {
                e.preventDefault();
                let username = $('#username').val();
                let password = $('#password').val();
                $.ajax({
                    url: 'server_action/process_login.php',
                    method: 'POST',
                    data: {
                        username: username,
                        password: password
                    },
                    success: function(response) {
                        // console.log("login successful", response);
                        window.location.href = 'dashboard.php';
                    },
                    error: function(error) {
                        console.log("login failed", error);
                    }
                })
            })
        })
    </script>
</body>

</html>