<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

require_once "../../core/database.php";

if (!$conn) {
    echo json_encode(['success' => false, 'error' => 'No database connection']);
    exit;
}

// Check if it's a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Only POST method allowed. Current method: ' . $_SERVER['REQUEST_METHOD']]);
    exit;
}

// Get the input data
$input = json_decode(file_get_contents('php://input'), true);

// If JSON decode fails, try form data
if (json_last_error() !== JSON_ERROR_NONE) {
    $input = $_POST;
}

// Debug: Log the received data
error_log("Received data: " . print_r($input, true));

// Validate required fields
$required_fields = ['technology_id', 'question_text', 'option1', 'option2', 'option3', 'option4', 'answer'];
foreach ($required_fields as $field) {
    if (!isset($input[$field]) || empty(trim($input[$field]))) {
        echo json_encode(['success' => false, 'error' => "Missing required field: $field"]);
        exit;
    }
}

// Sanitize inputs
$technology_id = intval($input['technology_id']);
$question_text = $conn->real_escape_string(trim($input['question_text']));
$option1 = $conn->real_escape_string(trim($input['option1']));
$option2 = $conn->real_escape_string(trim($input['option2']));
$option3 = $conn->real_escape_string(trim($input['option3']));
$option4 = $conn->real_escape_string(trim($input['option4']));
$answer = intval($input['answer']);

// Validate answer range
if ($answer < 1 || $answer > 4) {
    echo json_encode(['success' => false, 'error' => 'Answer must be between 1 and 4']);
    exit;
}

// Check if question already exists
$check_sql = "SELECT id FROM question WHERE question_text = '$question_text'";
$result = $conn->query($check_sql);

if ($result && $result->num_rows > 0) {
    echo json_encode(['success' => false, 'error' => 'Question already exists']);
    exit;
}

// Insert the new question
$sql = "INSERT INTO question (technology_id, question_text, option1, option2, option3, option4, answer) 
        VALUES ($technology_id, '$question_text', '$option1', '$option2', '$option3', '$option4', $answer)";

if ($conn->query($sql)) {
    $question_id = $conn->insert_id;
    echo json_encode([
        'success' => true, 
        'message' => 'Question added successfully',
        'question_id' => $question_id
    ]);
} else {
    echo json_encode([
        'success' => false, 
        'error' => 'Database error: ' . $conn->error
    ]);
}

$conn->close();
?>