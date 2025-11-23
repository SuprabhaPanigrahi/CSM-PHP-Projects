<?php
require_once '../database/connection.php';
mysqli_select_db($conn, 'Products');

$sql = "SELECT * FROM Products_Table";
$result = mysqli_query($conn, $sql);

$products = [];

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        if (isset($row['image'])) {
            $row['image'] = $row['image'];
        }
        $products[] = $row;
    }
}

echo json_encode($products);
mysqli_close($conn);
