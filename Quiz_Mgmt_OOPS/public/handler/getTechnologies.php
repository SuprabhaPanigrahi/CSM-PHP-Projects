<?php
header('Content-Type: application/json');
require_once "../../core/database.php";

if (!$conn) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed']);
    exit;
}

// Remove the WHERE status = 1 condition since status column doesn't exist
$result = $conn->query("SELECT id, name FROM technology ORDER BY name");
$technologies = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $technologies[] = $row;
    }
    echo json_encode(['status' => 'success', 'technologies' => $technologies]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'No technologies found', 'technologies' => []]);
}
?>