<?php
require_once '../database/connection.php';

$orderId = $_GET['orderId'] ?? '';

if(!$orderId){
    echo json_encode(['error' => 'No order ID provided']);
    exit;
}

$sql = "SELECT * FROM orders WHERE orderId='$orderId'";
$result = mysqli_query($conn, $sql);

if(mysqli_num_rows($result) > 0){
    $row = mysqli_fetch_assoc($result);
    echo json_encode($row);
} else {
    echo json_encode(['error' => 'Order not found']);
}
?>
