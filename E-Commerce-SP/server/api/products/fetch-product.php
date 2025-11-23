<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

include_once __DIR__ . '/../../inc/bootstrap.php';

$sql = "CALL USP_PRODUCTS(NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'SELECT')";

$result = mysqli_query($conn, $sql);

if(!$result) {
    echo json_encode(['success' => false, 'message' => 'Error fetching products: ' . mysqli_error($conn)]);
} else {
    $products = [];
    while($row = mysqli_fetch_assoc($result)) {
        $products[] = $row;
    }
    echo json_encode($products);
}

mysqli_close($conn);
?>