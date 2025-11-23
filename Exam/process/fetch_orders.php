<?php
include_once '../database/connection.php';
mysqli_select_db($conn, 'exam_db');

$sql = "SELECT o.orderId, 
               o.category, 
               p.productName AS product, 
               o.quantity, 
               o.created_at AS orderDate
        FROM orders o
        JOIN products p ON o.productId = p.productId
        ORDER BY o.orderId DESC";

$result = mysqli_query($conn, $sql);

$orders = [];

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $orders[] = $row;
    }
}

echo json_encode($orders);

mysqli_close($conn);
?>
