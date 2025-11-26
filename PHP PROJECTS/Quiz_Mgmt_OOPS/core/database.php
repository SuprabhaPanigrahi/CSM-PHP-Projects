<?php
// core/database.php

$servername = "localhost";
$username = "root"; 
$password = "csmpl@123"; 
$dbname = "quiz_mgmt"; 

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Optional: Set charset to utf8
$conn->set_charset("utf8mb4");

// echo "Database connected successfully"; // Remove this after testing
?>