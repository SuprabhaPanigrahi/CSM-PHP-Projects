<?php
header('Content-Type: application/json');
require_once "../core/database.php";

if (!$conn) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed']);
    exit;
}

if (!isset($_POST['id'], $_POST['technology_id'], $_POST['question_text'], $_POST['option1'], $_POST['option2'], $_POST['option3'], $_POST['option4'], $_POST['answer'])) {
    echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
    exit;
}

$id = (int)$_POST['id'];
$technology_id = (int)$_POST['technology_id'];
$question_text = trim($_POST['question_text']);
$option1 = trim($_POST['option1']);
$option2 = trim($_POST['option2']);
$option3 = trim($_POST['option3']);
$option4 = trim($_POST['option4']);
$answer = (int)$_POST['answer'];

$sql = "UPDATE question SET technology_id = ?, question_text = ?, option1 = ?, option2 = ?, option3 = ?, option4 = ?, answer = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("isssssii", $technology_id, $question_text, $option1, $option2, $option3, $option4, $answer, $id);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Question updated successfully']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to update question: ' . $conn->error]);
}

$stmt->close();
?>