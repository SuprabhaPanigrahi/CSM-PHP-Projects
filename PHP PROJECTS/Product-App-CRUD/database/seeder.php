<?php
include_once __DIR__ . '/connection.php';

//insert default users
$sql ="INSERT INTO users (username,password) VALUES 
('admin', 'password'),
('user1', 'user1pass'),
('user2', 'user2pass')";

if (mysqli_multi_query($conn, $sql)) {
    echo "Default users inserted successfully.";
} else {
    echo "Error inserting users: " . mysqli_error($conn);
}

// Insert default categories
$sql = "INSERT INTO categories (name) VALUES 
('Electronics'),
('Clothing'),
('Books'),
('Home & Kitchen')";
if (mysqli_multi_query($conn, $sql)) {
    echo "Default categories inserted successfully.";
} else {
    echo "Error inserting categories: " . mysqli_error($conn);
}

// Insert default products
$sql = "INSERT INTO products (category_id, product_name) VALUES 
(1, 'TV'),
(1, 'Refrigerator'),
(1, 'Washing Machine'),
(1, 'Microwave'),
(1, 'Smartphone'),
(2, 'T-Shirt'),
(2, 'Jeans'),
(2, 'Jacket'),
(3, 'Novel'),
(3, 'Textbook'),
(4, 'Cookware Set'),
(4, 'Vacuum Cleaner')";
if (mysqli_multi_query($conn, $sql)) {
    echo "Default products inserted successfully.";
} else {
    echo "Error inserting products: " . mysqli_error($conn);
}
