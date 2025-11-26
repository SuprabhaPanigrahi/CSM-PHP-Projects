<?php
header('Content-Type: application/json');

require_once "../../core/database.php";
require_once "../../models/gateways/StudentGateway.php";

if (!$conn) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed']);
    exit;
}

if (!isset($_POST['student_name'], $_POST['student_email'], $_POST['batch_id'], $_POST['tech_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
    exit;
}

$name = trim($_POST['student_name']);
$email = trim($_POST['student_email']);
$batch_id = (int)$_POST['batch_id'];
$tech_id = (int)$_POST['tech_id'];

if (empty($name) || empty($email) || $batch_id <= 0 || $tech_id <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Please fill all fields with valid data']);
    exit;
}

$studentGateway = new StudentGateway($conn);
$student_id = $studentGateway->addStudent($name, $email, '', '', $batch_id, $tech_id);

if ($student_id) {
    echo json_encode([
        'status' => 'success', 
        'student_id' => $student_id,
        'tech_id' => $tech_id, // Return tech_id in response
        'message' => 'Quiz started successfully'
    ]);
} else {
    echo json_encode([
        'status' => 'error', 
        'message' => 'Failed to register student. Please try again.'
    ]);
}
?>