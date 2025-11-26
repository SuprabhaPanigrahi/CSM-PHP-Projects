<?php
require_once '../database/connection.php';

// Get student ID from URL parameter
$studentId = $_GET['id'] ?? '';

// Validate student ID
if (empty($studentId)) {
    echo "error: No student ID provided.";
    exit();
}

// Check if student exists
$checkSql = "SELECT * FROM students WHERE id = '$studentId'";
$result = mysqli_query($conn, $checkSql);

if (mysqli_num_rows($result) === 0) {
    echo "error: Student not found.";
    exit();
}

// Delete student from database
$deleteSql = "DELETE FROM students WHERE id = '$studentId'";

if (mysqli_query($conn, $deleteSql)) {
    echo "success";
} else {
    echo "error: " . mysqli_error($conn);
}

mysqli_close($conn);
?>