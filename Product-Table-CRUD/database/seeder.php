<?php

require_once 'connection.php';

mysqli_select_db($conn, 'product_inventory');

$categories = [
    ['categoryName' => 'Electronics'],
    ['categoryName' => 'Clothing'],
    ['categoryName' => 'Home Good'],
    ['categoryName' => 'Toys'],
    ['categoryName' => 'Consumables'],
];

foreach ($categories as $category) {
    $categoryName = mysqli_real_escape_string($conn, $category['categoryName']);
    $insert_sql = "INSERT INTO categories (categoryName) VALUES ('$categoryName')";
    if (mysqli_query($conn, $insert_sql)) {
        echo "Inserted Category: $categoryName\n";
    } else {
        echo "Error inserting Category $categoryName: " . mysqli_error($conn) . "\n";
    }
}

$products = [
    ['categoryID' => 1, 'productName' => 'Laptop', 'price' => 50000.00, 'quantity' => 10],
    ['categoryID' => 2, 'productName' => 'Shirts', 'price' => 500.00, 'quantity' => 50],
    ['categoryID' => 3, 'productName' => 'Washing Machine', 'price' => 12000.45, 'quantity' => 5],
    ['categoryID' => 4, 'productName' => 'Toy', 'price' => 200.00, 'quantity' => 20],
    ['categoryID' => 5, 'productName' => 'Chocolate', 'price' => 100.00, 'quantity' => 100],
];

foreach ($products as $product) {
    $categoryID = mysqli_real_escape_string($conn, $product['categoryID']);
    $productName = mysqli_real_escape_string($conn, $product['productName']);
    $price = mysqli_real_escape_string($conn, $product['price']);
    $quantity = mysqli_real_escape_string($conn, $product['quantity']);

    $insert_sql = "INSERT INTO products (categoryID, productName, price, quantity)
                   VALUES ($categoryID, '$productName', $price, $quantity)";

    if (mysqli_query($conn, $insert_sql)) {
        echo "Inserted Product: $productName\n";
    } else {
        echo "Error inserting Product $productName: " . mysqli_error($conn) . "\n";
    }
}

mysqli_close($conn);
