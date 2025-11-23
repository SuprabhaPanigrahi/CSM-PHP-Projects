<?php
include_once 'connection.php';

$sql = "CREATE DATABASE IF NOT EXISTS exam_db";
if (mysqli_query($conn, $sql)) {
    echo "Database created or already exists.\n";
} else {
    die("Error creating database: " . mysqli_error($conn));
}

mysqli_select_db($conn, 'exam_db');

// Create products table
$productsTable = "CREATE TABLE IF NOT EXISTS products (
    productId INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    productName VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";
if (mysqli_query($conn, $productsTable)) {
    echo "Table products created successfully.\n";
} else {
    die("Error creating products table: " . mysqli_error($conn));
}

// Create orders table
$ordersTable = "CREATE TABLE IF NOT EXISTS orders (
    orderId INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category VARCHAR(50) NOT NULL,
    productId INT(6) UNSIGNED NOT NULL,
    quantity INT(6) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (productId) REFERENCES products(productId)
)";
if (mysqli_query($conn, $ordersTable)) {
    echo "Table orders created successfully.\n";
} else {
    die("Error creating orders table: " . mysqli_error($conn));
}

mysqli_close($conn);
?>
