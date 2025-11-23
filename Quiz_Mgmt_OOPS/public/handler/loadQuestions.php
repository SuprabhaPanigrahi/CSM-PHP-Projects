<?php
header('Content-Type: application/json');

require_once "../../core/database.php";
require_once "../../models/gateways/QuestionGateway.php";

if (!$conn) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed']);
    exit;
}

$tech_id = isset($_GET['tech_id']) ? (int)$_GET['tech_id'] : 0;

if ($tech_id <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid technology ID']);
    exit;
}

// Load 10 random questions from the SELECTED technology
$questionGateway = new QuestionGateway($conn);
$questions = $questionGateway->getQuestionsByTechnology($tech_id);

echo json_encode([
    'status' => 'success',
    'tech_id' => $tech_id,
    'questions_count' => count($questions),
    'questions' => $questions
]);
?>