<?php
require_once "../../core/database.php";
require_once "../../models/gateways/QuestionGateway.php";
require_once "../../models/gateways/StudentGateway.php";
require_once "../../models/gateways/ResultGateway.php";

if (!$conn) {
    die("Database connection failed");
}

// Get submitted data
$student_id = isset($_POST['student_id']) ? (int)$_POST['student_id'] : 0;
$tech_id = isset($_POST['tech_id']) ? (int)$_POST['tech_id'] : 0;
$answers = isset($_POST['answer']) ? $_POST['answer'] : [];

if ($student_id <= 0 || empty($answers)) {
    die("Invalid data submitted. Student ID: $student_id, Answers: " . count($answers));
}

// Get student info
$studentGateway = new StudentGateway($conn);
$student = $studentGateway->getById($student_id);

if (!$student) {
    die("Student not found with ID: $student_id");
}

// Calculate results
$questionGateway = new QuestionGateway($conn);
$total_questions = count($answers);
$correct_answers = 0;
$question_details = [];

foreach ($answers as $question_id => $selected_answer) {
    $question = $questionGateway->getById($question_id);
    
    if ($question) {
        // Use 'answer' column instead of 'correct_answer'
        $is_correct = ($selected_answer == $question['answer']);
        if ($is_correct) {
            $correct_answers++;
        }
        
        $question_details[] = [
            'question_text' => $question['question_text'],
            'selected_answer' => $selected_answer,
            'correct_answer' => $question['answer'], // Changed from 'correct_answer' to 'answer'
            'option1' => $question['option1'],
            'option2' => $question['option2'],
            'option3' => $question['option3'],
            'option4' => $question['option4'],
            'is_correct' => $is_correct
        ];
    }
}

$score_percentage = ($total_questions > 0) ? round(($correct_answers / $total_questions) * 100, 2) : 0;

// Save result to database
$resultGateway = new ResultGateway($conn);
$result_id = $resultGateway->addResult($student_id, $tech_id, $total_questions, $correct_answers, $score_percentage);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Quiz Results</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .correct-answer { background-color: #d4edda; border-left: 4px solid #28a745; }
        .wrong-answer { background-color: #f8d7da; border-left: 4px solid #dc3545; }
        .score-card { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
    </style>
</head>
<body>

<div class="container mt-4">
    <!-- Score Card -->
    <div class="card score-card mb-4">
        <div class="card-body text-center">
            <h1 class="display-4">Quiz Results</h1>
            <h2 class="display-1"><?php echo $correct_answers; ?>/<?php echo $total_questions; ?></h2>
            <h3 class="display-6"><?php echo $score_percentage; ?>%</h3>
            <p class="lead">Student: <?php echo htmlspecialchars($student['name']); ?></p>
            <p class="mb-0">Email: <?php echo htmlspecialchars($student['email']); ?></p>
        </div>
    </div>

    <!-- Performance Summary -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-white bg-success">
                <div class="card-body text-center">
                    <h4>Correct</h4>
                    <h2><?php echo $correct_answers; ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-danger">
                <div class="card-body text-center">
                    <h4>Wrong</h4>
                    <h2><?php echo $total_questions - $correct_answers; ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-info">
                <div class="card-body text-center">
                    <h4>Percentage</h4>
                    <h2><?php echo $score_percentage; ?>%</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Results -->
    <div class="card">
        <div class="card-header">
            <h3>Detailed Results</h3>
        </div>
        <div class="card-body">
            <?php foreach ($question_details as $index => $detail): ?>
                <div class="card mb-3 <?php echo $detail['is_correct'] ? 'correct-answer' : 'wrong-answer'; ?>">
                    <div class="card-body">
                        <h5 class="card-title">Question <?php echo $index + 1; ?></h5>
                        <p class="card-text"><strong><?php echo htmlspecialchars($detail['question_text']); ?></strong></p>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Your Answer:</h6>
                                <div class="alert <?php echo $detail['is_correct'] ? 'alert-success' : 'alert-danger'; ?>">
                                    <?php 
                                    $selected_option = 'option' . $detail['selected_answer'];
                                    echo htmlspecialchars($detail[$selected_option]);
                                    ?>
                                    <?php if (!$detail['is_correct']): ?>
                                        <br><small class="text-danger">❌ Wrong</small>
                                    <?php else: ?>
                                        <br><small class="text-success">✅ Correct</small>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <?php if (!$detail['is_correct']): ?>
                            <div class="col-md-6">
                                <h6>Correct Answer:</h6>
                                <div class="alert alert-success">
                                    <?php 
                                    $correct_option = 'option' . $detail['correct_answer'];
                                    echo htmlspecialchars($detail[$correct_option]);
                                    ?>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mt-3">
                            <small class="text-muted">
                                <strong>All Options:</strong><br>
                                1. <?php echo htmlspecialchars($detail['option1']); ?><br>
                                2. <?php echo htmlspecialchars($detail['option2']); ?><br>
                                3. <?php echo htmlspecialchars($detail['option3']); ?><br>
                                4. <?php echo htmlspecialchars($detail['option4']); ?>
                            </small>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="text-center mt-4">
        <a href="start.php" class="btn btn-primary btn-lg">Take Another Quiz</a>
        <a href="quiz.php?student_id=<?php echo $student_id; ?>&tech_id=<?php echo $tech_id; ?>" class="btn btn-warning btn-lg">Retry Same Quiz</a>
    </div>
</div>

</body>
</html>