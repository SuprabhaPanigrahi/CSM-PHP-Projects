<?php

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if($username == 'admin' && $password == 'password'){
        echo "login successful";
    }
    else{
        echo "invalid credentials";
    }
}

else{
    echo "invalid request method";
}

?>