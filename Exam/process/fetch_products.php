<?php
include_once '../database/connection.php';
mysqli_select_db($conn, 'exam_db');

$sql = "SELECT productId, productName FROM products ORDER BY productName ASC";
$result = mysqli_query($conn, $sql);

$products = [];

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $products[] = $row;
    }
}

echo json_encode($products);

mysqli_close($conn);
?>
