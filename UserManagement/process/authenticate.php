<?php
session_start();

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

//echo "Username: " .$username . "<br>";
//echo "Password: " .$password . "<br>";

if($username === 'admin' && $password === 'password123'){
    //echo "Authentication successful. Welcome, " . $username . "!";
    header("Location: ../pages/registration.php");
    exit();
} else {
    //echo "Authentication failed. Invalid username or password.";
    header("Location: ../pages/login.php?error=Invalid credentials");
    exit();
} 
