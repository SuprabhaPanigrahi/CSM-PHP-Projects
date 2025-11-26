<!DOCTYPE html>
<html>
<head>
    <title>Student Quiz Start</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
<div class="container mt-5" style="max-width: 600px;">
    <h2 class="text-center">Start Quiz</h2>

    <form id="startQuizForm">
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="student_name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="student_email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Select Batch</label>
            <select name="batch_id" class="form-control" required id="batchSelect">
                <option value="">Loading batches...</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Select Technology</label>
            <select name="tech_id" class="form-control" required id="techSelect">
                <option value="">Loading technologies...</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success w-100">Start Quiz</button>
    </form>
    
    <div id="errorMessage" class="alert alert-danger mt-3" style="display: none;"></div>
</div>

<script>
$(document).ready(function() {
    // Fetch batches and technologies via AJAX
    $.ajax({
        url: '../handler/fetchData.php',
        method: 'GET',
        dataType: 'json',
        success: function(data) {
            if(data.batches && data.technologies) {
                $('#batchSelect').empty();
                $('#batchSelect').append('<option value="">Select Batch</option>');
                data.batches.forEach(function(batch) {
                    $('#batchSelect').append('<option value="'+batch.id+'">'+batch.name+'</option>');
                });

                $('#techSelect').empty();
                $('#techSelect').append('<option value="">Select Technology</option>');
                data.technologies.forEach(function(tech) {
                    $('#techSelect').append('<option value="'+tech.id+'">'+tech.name+'</option>');
                });
            } else {
                showError('Failed to load batches and technologies');
            }
        },
        error: function(xhr, status, error) {
            showError('Error loading data: ' + error);
        }
    });

    // Handle form submission via AJAX
    $('#startQuizForm').submit(function(e) {
        e.preventDefault();
        
        // Show loading state
        $('button[type="submit"]').prop('disabled', true).text('Starting Quiz...');
        $('#errorMessage').hide();

        $.ajax({
            url: '../handler/startQuiz.php',
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if(response.status == 'success') {
                    window.location.href = 'quiz.php?student_id=' + response.student_id + '&tech_id=' + $('#techSelect').val();
                } else {
                    showError(response.message || 'Failed to start quiz');
                }
            },
            error: function(xhr, status, error) {
                showError('Error starting quiz: ' + error);
            },
            complete: function() {
                $('button[type="submit"]').prop('disabled', false).text('Start Quiz');
            }
        });
    });

    function showError(message) {
        $('#errorMessage').text(message).show();
    }
});
</script>
</body>
</html>