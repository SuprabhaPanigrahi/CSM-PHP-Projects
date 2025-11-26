<?php
include_once __DIR__ . '/connection.php';

//Create Database if not exists
$sql = "CREATE DATABASE IF NOT EXISTS " . DB_NAME;

if(mysqli_query($conn, $sql)){
    echo "Database created successfully or already exists.";
} else {
    echo "Error creating database: " . mysqli_error($conn);
}


//use the database
mysqli_select_db($conn, DB_NAME); 

// Create user table

$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";     

if (mysqli_query($conn, $sql)) {
    echo "User table created successfully.";
} else {
    echo "Error creating user table: " . mysqli_error($conn);
}

//create Category table
$sql = "CREATE TABLE IF NOT EXISTS categories (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
if (mysqli_query($conn, $sql)) {
    echo "Category table created successfully.";
} else {
    echo "Error creating category table: " . mysqli_error($conn);
}

// Create Products table
$sql = "CREATE TABLE IF NOT EXISTS products (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    category_id INT(11) NOT NULL,
    product_name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
)";
if (mysqli_query($conn, $sql)) {
    echo "Products table created successfully.";
} else {
    echo "Error creating products table: " . mysqli_error($conn);
}

// Create Product Details table
$sql = "CREATE TABLE IF NOT EXISTS product_details (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    category_id INT(11) NOT NULL,
    product_id INT(11) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    quantity INT(11) NOT NULL,
    isactive TINYINT(1) DEFAULT 0,
    isdeleted TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
)";
if (mysqli_query($conn, $sql)) {
    echo "Product Details table created successfully.";
} else {
    echo "Error creating Product Details table: " . mysqli_error($conn);
}
mysqli_close($conn);