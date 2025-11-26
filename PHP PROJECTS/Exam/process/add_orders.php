<?php
include_once '../database/connection.php';
mysqli_select_db($conn, 'exam_db');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $productId = intval($_POST['product']);
    $quantity = intval($_POST['quantity']);

    if (empty($category) || $productId <= 0 || $quantity <= 0) {
        echo "Invalid input data.";
        exit;
    }

    $sql = "INSERT INTO orders (category, productId, quantity)
            VALUES ('$category', '$productId', '$quantity')";

    if (mysqli_query($conn, $sql)) {
        echo "Order added successfully!";
    } else {
        echo "Error adding order: " . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>
