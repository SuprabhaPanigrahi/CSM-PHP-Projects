<?php
header('Content-Type: application/json');
require_once "../core/database.php";

if (!$conn) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed']);
    exit;
}

$sql = "SELECT q.*, t.name as tech_name 
        FROM question q 
        LEFT JOIN technology t ON q.technology_id = t.id 
        ORDER BY q.id DESC";

$result = $conn->query($sql);
$questions = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $questions[] = $row;
    }
}

echo json_encode(['status' => 'success', 'questions' => $questions]);
?>