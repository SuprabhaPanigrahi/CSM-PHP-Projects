<?php
header('Content-Type: application/json');
require_once "../core/database.php";

if (!$conn) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed']);
    exit;
}

$result = $conn->query("SELECT id, name FROM batch WHERE status = 1 ORDER BY name");
$batches = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $batches[] = $row;
    }
}

echo json_encode(['status' => 'success', 'batches' => $batches]);
?>