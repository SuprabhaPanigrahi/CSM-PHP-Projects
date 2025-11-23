<?php
header('Content-Type: application/json');
require_once "../core/database.php";

if (!$conn) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed']);
    exit;
}

if (!isset($_GET['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Question ID is required']);
    exit;
}

$id = (int)$_GET['id'];

$sql = "SELECT * FROM question WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $question = $result->fetch_assoc();
    echo json_encode(['status' => 'success', 'question' => $question]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Question not found']);
}

$stmt->close();
?>