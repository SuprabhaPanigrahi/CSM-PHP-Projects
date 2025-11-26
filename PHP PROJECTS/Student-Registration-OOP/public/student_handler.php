<?php
// public/student_handler.php

require_once '../core/Database.php';
require_once '../core/Validator.php';
require_once '../models/Student.php';
require_once '../models/StudentGateway.php';

$db       = new Database();
$conn     = $db->getConnection();
$gateway  = new StudentGateway($conn);
$validator = new Validator();

$action = $_GET['action'] ?? '';

if ($action === 'save') {

    header('Content-Type: application/json');

    $name  = $_POST['name']  ?? '';
    $email = $_POST['email'] ?? '';
    $age   = $_POST['age']   ?? '';

    // ---------- Validation ----------
    $validator->required('name', $name, 'Name');
    $validator->required('email', $email, 'Email');
    $validator->required('age', $age, 'Age');
    $validator->email('email', $email, 'Email');
    $validator->integer('age', $age, 'Age');
    $validator->min('age', $age, 1, 'Age');

    if ($validator->hasErrors()) {
        echo json_encode([
            'status' => 'error',
            'errors' => $validator->getErrors()
        ]);
        exit;
    }

    // Create Student object
    $student = new Student($name, $email, (int)$age);

    // Save via gateway
    $ok = $gateway->insert($student);

    if ($ok) {
        echo json_encode([
            'status'  => 'success',
            'message' => 'Student registered successfully.'
        ]);
    } else {
        echo json_encode([
            'status'  => 'error',
            'errors'  => [
                'general' => ['Failed to save student.']
            ]
        ]);
    }

} elseif ($action === 'list') {

    $students = $gateway->all();

    foreach ($students as $s) {
        echo "<tr>
                <td>{$s['id']}</td>
                <td>" . htmlspecialchars($s['name']) . "</td>
                <td>" . htmlspecialchars($s['email']) . "</td>
                <td>{$s['age']}</td>
             </tr>";
    }

} else {

    http_response_code(400);
    echo "Invalid action.";

}
