<?php
header('Content-Type: application/json');
require_once "../core/database.php";
require_once "../models/gateways/StudentGateway.php";

if (!$conn) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed']);
    exit;
}

if (!isset($_POST['name'], $_POST['email'], $_POST['batch_id'], $_POST['tech_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Required fields missing']);
    exit;
}

$name = trim($_POST['name']);
$email = trim($_POST['email']);
$phone = trim($_POST['phone'] ?? '');
$gender = trim($_POST['gender'] ?? '');
$batch_id = (int)$_POST['batch_id'];
$tech_id = (int)$_POST['tech_id'];

$studentGateway = new StudentGateway($conn);
$student_id = $studentGateway->addStudent($name, $email, $phone, $gender, $batch_id, $tech_id);

if ($student_id) {
    echo json_encode(['status' => 'success', 'message' => 'Student added successfully']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to add student']);
}
?>