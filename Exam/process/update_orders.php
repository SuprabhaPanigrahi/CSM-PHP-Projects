<?php
require_once '../database/connection.php';

$orderId = $_POST['orderId'] ?? '';
$category = $_POST['category'] ?? '';
$productId = $_POST['product'] ?? '';
$quantity = $_POST['quantity'] ?? '';

if(!$orderId || !$category || !$productId || !$quantity){
    echo "Please fill all fields";
    exit;
}

$sql = "UPDATE orders 
        SET category='$category', productId='$productId', quantity='$quantity', updated_at=NOW() 
        WHERE orderId='$orderId'";

if(mysqli_query($conn, $sql)){
    echo "Order updated successfully";
} else {
    echo "Error updating order: " . mysqli_error($conn);
}

mysqli_close($conn);
?>
