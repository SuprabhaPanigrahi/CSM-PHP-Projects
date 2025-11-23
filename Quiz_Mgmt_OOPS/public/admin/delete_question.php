<?php
header('Content-Type: application/json');
require_once "../core/database.php";

if (!$conn) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed']);
    exit;
}

if (!isset($_POST['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Question ID is required']);
    exit;
}

$id = (int)$_POST['id'];

$sql = "DELETE FROM question WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Question deleted successfully']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to delete question: ' . $conn->error]);
}

$stmt->close();
?>