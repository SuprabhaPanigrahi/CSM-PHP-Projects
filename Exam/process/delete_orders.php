<?php
require_once '../database/connection.php';

$orderId = $_GET['orderId'] ?? '';

if (empty($orderId)) {
    echo "error: No order ID provided.";
    exit();
}

$checkSql = "SELECT * FROM orders WHERE orderId = '$orderId'";
$result = mysqli_query($conn, $checkSql);

if (!$result || mysqli_num_rows($result) === 0) {
    echo "error: order not found.";
    exit();
}

$deleteSql = "DELETE FROM orders WHERE orderId = '$orderId'";

if (mysqli_query($conn, $deleteSql)) {
    echo "success";
} else {
    echo "error: " . mysqli_error($conn);
}

mysqli_close($conn);
?>
