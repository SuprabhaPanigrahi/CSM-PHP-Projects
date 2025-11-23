<?php
header('Content-Type: application/json');

require_once "../../core/database.php";

if (!$conn) {
    echo json_encode(['error' => 'No database connection']);
    exit;
}

$sql = "CREATE TABLE IF NOT EXISTS results (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    tech_id INT NOT NULL,
    total_questions INT NOT NULL,
    correct_answers INT NOT NULL,
    score_percentage DECIMAL(5,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES student(id),
    FOREIGN KEY (tech_id) REFERENCES technology(id)
)";

if ($conn->query($sql)) {
    echo json_encode(['success' => 'Results table created successfully']);
} else {
    echo json_encode(['error' => 'Failed to create table: ' . $conn->error]);
}
?>