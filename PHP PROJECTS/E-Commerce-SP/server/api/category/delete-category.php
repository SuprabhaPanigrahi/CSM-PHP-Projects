<?php
header('Content-Type: application/json');

include_once __DIR__ . '/../../inc/bootstrap.php';

$input = json_decode(file_get_contents('php://input'), true);
$id = $input['id'] ?? 0;

$sql = "CALL USP_CATEGORY($id, NULL, 'DELETE')";
$result = mysqli_query($conn, $sql);

if($result) {
    echo json_encode(['success' => true, 'message' => 'Category deleted successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error deleting category']);
}

mysqli_close($conn);
?>