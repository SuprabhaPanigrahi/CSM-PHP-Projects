<!DOCTYPE html>
<html>
<head>
    <title>Manage Questions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .action-btns .btn { margin-right: 5px; }
        .table th { background-color: #f8f9fa; }
        .question-text { max-width: 300px; word-wrap: break-word; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="dashboard.php">Admin Panel</a>
        <div class="navbar-nav ms-auto">
            <a class="nav-link" href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            <a class="nav-link" href="manage_students.php"><i class="fas fa-users"></i> Students</a>
            <a class="nav-link" href="../handler/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-question-circle"></i> Manage Questions</h2>
    </div>

    <!-- Add Question Form -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Add New Question</h5>
        </div>
        <div class="card-body">
            <form id="addQuestionForm">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Select Technology *</label>
                        <select name="technology_id" class="form-control" required id="technologySelect">
                            <option value="">Select Technology</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Question Text *</label>
                    <textarea name="question_text" class="form-control" rows="3" required placeholder="Enter the question..."></textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Option 1 *</label>
                        <input type="text" name="option1" class="form-control" required placeholder="Enter option 1">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Option 2 *</label>
                        <input type="text" name="option2" class="form-control" required placeholder="Enter option 2">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Option 3 *</label>
                        <input type="text" name="option3" class="form-control" required placeholder="Enter option 3">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Option 4 *</label>
                        <input type="text" name="option4" class="form-control" required placeholder="Enter option 4">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Correct Answer *</label>
                        <select name="answer" class="form-control" required>
                            <option value="">Select Correct Answer</option>
                            <option value="1">Option 1</option>
                            <option value="2">Option 2</option>
                            <option value="3">Option 3</option>
                            <option value="4">Option 4</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-save"></i> Save Question
                        </button>
                    </div>
                </div>
            </form>
            <div id="formMessage" class="mt-3"></div>
        </div>
    </div>

    <!-- Questions List -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">All Questions</h5>
            <div class="d-flex">
                <select id="techFilter" class="form-control me-2" style="width: 200px;">
                    <option value="">All Technologies</option>
                </select>
                <input type="text" id="searchInput" class="form-control" placeholder="Search questions..." style="width: 200px;">
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Technology</th>
                            <th>Question</th>
                            <th>Options</th>
                            <th>Correct Answer</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="question_list">
                        <!-- Dynamic content will be loaded here -->
                    </tbody>
                </table>
            </div>
            <div id="loading" class="text-center">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
            <div id="noQuestions" class="text-center" style="display: none;">
                <p class="text-muted">No questions found.</p>
            </div>
        </div>
    </div>
</div>

<!-- Edit Question Modal -->
<div class="modal fade" id="editQuestionModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Question</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editQuestionForm">
                    <input type="hidden" name="id" id="editQuestionId">
                    <div class="mb-3">
                        <label class="form-label">Select Technology *</label>
                        <select name="technology_id" class="form-control" required id="editTechnologySelect">
                            <option value="">Select Technology</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Question Text *</label>
                        <textarea name="question_text" class="form-control" rows="3" required id="editQuestionText"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Option 1 *</label>
                            <input type="text" name="option1" class="form-control" required id="editOption1">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Option 2 *</label>
                            <input type="text" name="option2" class="form-control" required id="editOption2">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Option 3 *</label>
                            <input type="text" name="option3" class="form-control" required id="editOption3">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Option 4 *</label>
                            <input type="text" name="option4" class="form-control" required id="editOption4">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Correct Answer *</label>
                        <select name="answer" class="form-control" required id="editAnswer">
                            <option value="">Select Correct Answer</option>
                            <option value="1">Option 1</option>
                            <option value="2">Option 2</option>
                            <option value="3">Option 3</option>
                            <option value="4">Option 4</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="updateQuestion()">Update Question</button>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function() {
    loadTechnologies();
    loadQuestions();
    
    // Form submission
    $('#addQuestionForm').submit(function(e) {
        e.preventDefault();
        addQuestion();
    });
});

function loadTechnologies() {
    $.ajax({
        url: '../handler/getTechnologies.php',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                // Populate add form dropdown
                $('#technologySelect').html('<option value="">Select Technology</option>');
                response.technologies.forEach(tech => {
                    $('#technologySelect').append(`<option value="${tech.id}">${tech.name}</option>`);
                });

                // Populate filter dropdown
                $('#techFilter').html('<option value="">All Technologies</option>');
                response.technologies.forEach(tech => {
                    $('#techFilter').append(`<option value="${tech.id}">${tech.name}</option>`);
                });

                // Populate edit form dropdown
                $('#editTechnologySelect').html('<option value="">Select Technology</option>');
                response.technologies.forEach(tech => {
                    $('#editTechnologySelect').append(`<option value="${tech.id}">${tech.name}</option>`);
                });
            }
        },
        error: function() {
            alert('Error loading technologies');
        }
    });
}

function loadQuestions() {
    $('#loading').show();
    $('#question_list').html('');
    $('#noQuestions').hide();

    $.ajax({
        url: '../handler/get_questions.php',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            $('#loading').hide();
            if (response.status === 'success' && response.questions.length > 0) {
                displayQuestions(response.questions);
            } else {
                $('#noQuestions').show();
            }
        },
        error: function() {
            $('#loading').hide();
            $('#noQuestions').show();
        }
    });
}

function displayQuestions(questions) {
    let html = '';
    questions.forEach(question => {
        html += `
            <tr>
                <td>${question.id}</td>
                <td>${question.tech_name || 'N/A'}</td>
                <td class="question-text">${question.question_text}</td>
                <td>
                    <small>
                        <strong>1:</strong> ${question.option1}<br>
                        <strong>2:</strong> ${question.option2}<br>
                        <strong>3:</strong> ${question.option3}<br>
                        <strong>4:</strong> ${question.option4}
                    </small>
                </td>
                <td>
                    <span class="badge bg-success">Option ${question.answer}</span>
                    <br>
                    <small>${question['option' + question.answer]}</small>
                </td>
                <td class="action-btns">
                    <button class="btn btn-sm btn-warning" onclick="editQuestion(${question.id})">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-danger" onclick="deleteQuestion(${question.id})">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
    });
    $('#question_list').html(html);
}

function addQuestion() {
    const formData = $('#addQuestionForm').serialize();
    
    $.ajax({
        url: '../handler/insert_question.php',
        method: 'POST',
        data: formData,
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                $('#formMessage').html('<div class="alert alert-success">Question added successfully!</div>');
                $('#addQuestionForm')[0].reset();
                loadQuestions();
                setTimeout(() => $('#formMessage').html(''), 3000);
            } else {
                $('#formMessage').html('<div class="alert alert-danger">' + response.message + '</div>');
            }
        },
        error: function() {
            $('#formMessage').html('<div class="alert alert-danger">Error adding question. Please try again.</div>');
        }
    });
}

function editQuestion(questionId) {
    $.ajax({
        url: '../handler/get_question.php',
        method: 'GET',
        data: { id: questionId },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                const question = response.question;
                $('#editQuestionId').val(question.id);
                $('#editTechnologySelect').val(question.technology_id);
                $('#editQuestionText').val(question.question_text);
                $('#editOption1').val(question.option1);
                $('#editOption2').val(question.option2);
                $('#editOption3').val(question.option3);
                $('#editOption4').val(question.option4);
                $('#editAnswer').val(question.answer);
                $('#editQuestionModal').modal('show');
            } else {
                alert('Error loading question data');
            }
        }
    });
}

function updateQuestion() {
    const formData = $('#editQuestionForm').serialize();
    
    $.ajax({
        url: '../handler/update_question.php',
        method: 'POST',
        data: formData,
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                $('#editQuestionModal').modal('hide');
                loadQuestions();
                alert('Question updated successfully!');
            } else {
                alert('Error: ' + response.message);
            }
        },
        error: function() {
            alert('Error updating question. Please try again.');
        }
    });
}

function deleteQuestion(questionId) {
    if (confirm('Are you sure you want to delete this question?')) {
        $.ajax({
            url: '../handler/delete_question.php',
            method: 'POST',
            data: { id: questionId },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    loadQuestions();
                    alert('Question deleted successfully!');
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function() {
                alert('Error deleting question. Please try again.');
            }
        });
    }
}

// Filter functionality
$('#techFilter').change(function() {
    const techId = $(this).val();
    // Implement filter logic here or reload questions with filter
});

// Search functionality
$('#searchInput').on('input', function() {
    const searchTerm = $(this).val().toLowerCase();
    // Implement search logic here
});
</script>

</body>
</html>