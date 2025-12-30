<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <form id="loginForm">
        <h1>Login Form</h1><br>
        Email:
        <input type="email" name="email" id="email"><br>
        Password:
        <input type="password" name="password" id="password"><br>
        <button type="submit" id="btn">Login</button>
    </form>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <script>
        $(document).ready(function() {
            // console.log("ready!");
            $("#loginForm").submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: "http://localhost:8080/api/auth/login",
                    method: "POST",
                    data: {
                        email: $("#email").val(),
                        password: $("#password").val(),
                    },

                    dataType: "json",

                    success: function(response) {
                        console.log(response);
                        if (response) {
                            let token = response.access_token;
                            localStorage.setItem("token", token);
                            alert("User logged in Successfully");
                            setTimeout(function() {
                                window.location.href = "/dashboard";
                            }, 1000)
                        }
                        // return response;
                    },
                    error: function(error) {
                        // console.log("error message",error.responseJSON.errors.password[0]);

                        // alert(error.responseJSON.errors.password[0]);
                        error.responseJSON.errors.name && alert(error.responseJSON.errors.name[0]);
                        error.responseJSON.errors.email && alert(error.responseJSON.errors.email[0]);
                        error.responseJSON.errors.password && alert(error.responseJSON.errors.password[0]);
                        // return error;
                    }
                })
            });
        });
    </script>
</body>

</html>