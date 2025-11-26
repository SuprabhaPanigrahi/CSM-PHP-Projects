<?php
require_once '../database/connection.php';

// Set header
header('Content-Type: text/plain');

// Check connection and select database
if (!$conn || !mysqli_select_db($conn, 'customer_db')) {
    echo "Database connection failed";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $code = mysqli_real_escape_string($conn, $_POST['code']);
    
    // Validate input
    if (empty($name) || empty($code)) {
        echo "Please enter both country name and code";
        exit;
    }
    
    // Insert into database
    $sql = "INSERT INTO countries (name, code) VALUES ('$name', '$code')";
    
    if (mysqli_query($conn, $sql)) {
        echo "success";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    echo "Invalid request method";
}
?>