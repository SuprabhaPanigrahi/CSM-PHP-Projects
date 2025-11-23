<!DOCTYPE html>
<html>
<head>
    <title>Quiz</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<div class="container mt-4">
    <?php
    // Fix the require path - it should be relative to student folder
    require_once "../../core/database.php";
    
    $student_id = isset($_GET['student_id']) ? (int)$_GET['student_id'] : 0;
    $tech_id = isset($_GET['tech_id']) ? (int)$_GET['tech_id'] : 0;
    
    if ($student_id <= 0 || $tech_id <= 0) {
        echo '<div class="alert alert-danger">Invalid student or technology. Please start the quiz again.</div>';
        echo '<a href="start.php" class="btn btn-primary">Go Back to Start</a>';
        exit;
    }
    
    // Get technology name
    if ($conn) {
        $result = $conn->query("SELECT name FROM technology WHERE id = $tech_id");
        $tech_name = $result && $result->num_rows > 0 ? $result->fetch_assoc()['name'] : 'Unknown Technology';
    } else {
        $tech_name = 'Unknown Technology';
        echo '<div class="alert alert-danger">Database connection failed.</div>';
    }
    ?>

    <h2 class="text-center"><?php echo htmlspecialchars($tech_name); ?> Quiz</h2>
    <p class="text-center text-muted">10 Random Questions</p>

    <form method="POST" action="result.php">
        <input type="hidden" name="student_id" value="<?php echo $student_id; ?>">
        <input type="hidden" name="tech_id" value="<?php echo $tech_id; ?>">

        <div id="question_area">
            <div class="text-center">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading questions...</span>
                </div>
                <p>Loading 10 random <?php echo htmlspecialchars($tech_name); ?> questions...</p>
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100 mt-3">Submit Quiz</button>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const techId = <?php echo $tech_id; ?>;
    
    console.log("Loading questions for technology ID:", techId);
    
    fetch(`../handler/loadQuestions.php?tech_id=${techId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            console.log("Questions data received:", data);
            const questionArea = document.getElementById('question_area');
            
            if (data.status === 'success' && data.questions && data.questions.length > 0) {
                let questionsHTML = '';
                
                data.questions.forEach((question, index) => {
                    // Validate that all required fields exist
                    if (question.id && question.question_text && question.option1 && question.option2 && question.option3 && question.option4) {
                        questionsHTML += `
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">Question ${index + 1}</h5>
                                    <p class="card-text">${question.question_text}</p>
                                    
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="answer[${question.id}]" value="1" id="q${question.id}_1" required>
                                        <label class="form-check-label" for="q${question.id}_1">${question.option1}</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="answer[${question.id}]" value="2" id="q${question.id}_2">
                                        <label class="form-check-label" for="q${question.id}_2">${question.option2}</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="answer[${question.id}]" value="3" id="q${question.id}_3">
                                        <label class="form-check-label" for="q${question.id}_3">${question.option3}</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="answer[${question.id}]" value="4" id="q${question.id}_4">
                                        <label class="form-check-label" for="q${question.id}_4">${question.option4}</label>
                                    </div>
                                </div>
                            </div>
                        `;
                    } else {
                        console.error("Invalid question data:", question);
                    }
                });
                
                if (questionsHTML === '') {
                    questionArea.innerHTML = `
                        <div class="alert alert-warning">
                            <h5>Invalid Questions</h5>
                            <p>Some questions have missing data. Please contact administrator.</p>
                        </div>
                    `;
                } else {
                    questionArea.innerHTML = questionsHTML;
                }
            } else {
                questionArea.innerHTML = `
                    <div class="alert alert-warning">
                        <h5>No Questions Available</h5>
                        <p>Status: ${data.status || 'unknown'}</p>
                        <p>Message: ${data.message || 'No questions found'}</p>
                        <p>Questions found: ${data.questions ? data.questions.length : 0}</p>
                        <p>Please try another technology or contact administrator.</p>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error loading questions:', error);
            document.getElementById('question_area').innerHTML = `
                <div class="alert alert-danger">
                    <h5>Error loading questions</h5>
                    <p>${error.message}</p>
                    <p>Please try again or contact administrator.</p>
                </div>
            `;
        });
});
</script>

</body>
</html>