<?php
require_once '../database/connection.php';

// Get student ID from URL
$student_id = $_GET['id'] ?? '';

if (empty($student_id)) {
    echo json_encode(['error' => 'No student ID provided']);
    exit();
}

// Fetch student data
$sql = "SELECT * FROM students WHERE id = '$student_id'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    $student = mysqli_fetch_assoc($result);
    echo json_encode($student);
} else {
    echo json_encode(['error' => 'Student not found']);
}

mysqli_close($conn);
?>