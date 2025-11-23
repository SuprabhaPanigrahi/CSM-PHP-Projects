<?php
// Simple delete-product.php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Get the raw input
$input = file_get_contents('php://input');
$data = json_decode($input, true);

$id = intval($data['id'] ?? 0);

include_once __DIR__ . '/../../inc/bootstrap.php';

if($id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid product ID']);
    exit;
}

$sql = "CALL USP_PRODUCTS($id, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'DELETE')";
$result = mysqli_query($conn, $sql);

if($result) {
    echo json_encode(['success' => true, 'message' => 'Product deleted successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Delete failed']);
}

mysqli_close($conn);
exit;
?>