<?php
header('Content-Type: application/json');
require_once "../core/database.php";

if (!$conn) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed']);
    exit;
}

$sql = "SELECT s.*, b.name as batch_name, t.name as tech_name 
        FROM student s 
        LEFT JOIN batch b ON s.batch_id = b.id 
        LEFT JOIN technology t ON s.tech_id = t.id 
        ORDER BY s.id DESC";

$result = $conn->query($sql);
$students = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }
}

echo json_encode(['status' => 'success', 'students' => $students]);
?>