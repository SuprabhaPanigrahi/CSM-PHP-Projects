<?php
session_start();

// Database connection
$host = "localhost";
$user = "root";
$pass = "csmpl@123";
$db   = "e_commerce";

$conn = mysqli_connect($host, $user, $pass, $db);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get form inputs safely
$username = $_POST['username'];
$password = $_POST['password'];

// Escape inputs to prevent SQL injection
$username = mysqli_real_escape_string($conn, $username);
$password = mysqli_real_escape_string($conn, $password);

// Query to check user credentials
$sql = "SELECT * FROM user WHERE username = '$username' AND password = '$password'";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

if (mysqli_num_rows($result) === 1) {
    $user = mysqli_fetch_assoc($result);

    // Store session variables
    $_SESSION['username'] = $user['username'];
    $_SESSION['role'] = $user['role'];

    // Redirect to dashboard
    header("Location: ../../../public/dashboard.html");
    exit();
} else {
    // Invalid credentials
    echo "<script>alert('Invalid username or password!'); 
    window.location.href='login.html';</script>";
}

// Close connection
mysqli_close($conn);
?>
