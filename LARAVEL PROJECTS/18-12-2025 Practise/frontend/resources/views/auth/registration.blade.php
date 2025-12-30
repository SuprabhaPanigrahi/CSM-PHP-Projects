<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        /* Styles for the container div */
        .loader-container {
            /* Set size and position properties for the containing div */
            width: 100%;
            height: 200px;
            /* Example height */
            position: relative;
            border: 1px solid #ccc;
            /* Optional: just for visualization */
        }

        img {
            position: absolute;
            top: 50%;
            left: 40%;
            margin-top: -30px;
            margin-left: -30px;
        }

        /* Styles for the loader itself */
        /* .loader {
            border: 8px solid #f3f3f3;
            border-top: 8px solid #3498db;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            animation: spin 1s linear infinite;

            position: absolute;
            top: 50%;
            left: 50%;
            margin-top: -30px;
            margin-left: -30px;
        } */

        /* The animation keyframes */
        /* @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        } */
    </style>
</head>

<body>
    <div class="loader-container" id="loader">
        <div class="loader">
            <img src="{{asset('images/Loading_2.gif')}}" alt="" srcset="">
        </div>
    </div>
    <form id="registerForm">
        <h1>User Registration form</h1><br>
        Name:
        <input type="text" name="name" id="name"><br>
        Email:
        <input type="email" name="email" id="email"><br>
        Password:
        <input type="password" name="password" id="password"><br>
        Confirm Password:
        <input type="password" name="password_confirmation" id="password_confirmation"><br>
        Role:
        <select name="role" id="role">
            <option value="customer">Customer</option>
            <option value="vendor">Vendor</option>
        </select><br><br>
        <button type="submit" id="btn">Register</button>
        <button type="submit" id="btn">Login</button>
    </form>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            // console.log("ready!");
            $("#loader").hide();
            $("#registerForm").submit(function(e) {
                e.preventDefault();
                $("#loader").show();
                $.ajax({
                    url: "http://localhost:8080/api/auth/register",
                    method: "POST",
                    data: {
                        name: $("#name").val(),
                        email: $("#email").val(),
                        password: $("#password").val(),
                        password_confirmation: $("#password_confirmation").val(),
                        role: $("#role").val()
                    },

                    dataType: "json",
                    success: function(response) {
                        console.log(response);
                        if (response) {
                            let token = response.access_token;
                            localStorage.setItem("token", token);
                            alert("User Registred Successfully");
                            $("#loader").hide();
                            setTimeout(function() {
                                window.location.href = "/login";
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