<?php
include_once 'connection.php';

mysqli_select_db($conn, 'exam_db');

// Insert 5 default products
$productSql = "INSERT INTO products (productName) VALUES
('Television'),
('Shirt'),
('Snacks'),
('Trousers'),
('Smartphone')";
if (mysqli_query($conn, $productSql)) {
    echo "5 products inserted successfully\n";
} else {
    echo "Error inserting products: " . mysqli_error($conn);
}

// Insert sample orders
$orderSql = "INSERT INTO orders (category, productId, quantity) VALUES
('Electronics', 1, 2),
('Electronics', 2, 1),
('Electronics', 3, 3)";
if (mysqli_query($conn, $orderSql)) {
    echo "Sample orders inserted successfully\n";
} else {
    echo "Error inserting orders: " . mysqli_error($conn);
}

mysqli_close($conn);
?>
