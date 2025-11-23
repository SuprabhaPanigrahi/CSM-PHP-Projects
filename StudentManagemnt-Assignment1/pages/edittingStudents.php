<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student - EduTrack</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fb;
            color: #212529;
        }
        
        .container {
            max-width: 800px;
            margin: 30px auto;
            background-color: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }
        
        h1 {
            color: #2c3e50;
            font-weight: 600;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 1px solid #dee2e6;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #495057;
        }
        
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ced4da;
            border-radius: 6px;
            font-size: 1rem;
        }
        
        .form-control:focus {
            border-color: #4361ee;
            box-shadow: 0 0 0 0.2rem rgba(67, 97, 238, 0.25);
        }
        
        .file-upload {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .file-input {
            display: none;
        }
        
        .file-label {
            background-color: #e9ecef;
            border: 1px solid #ced4da;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .file-label:hover {
            background-color: #dee2e6;
        }
        
        .file-name {
            color: #6c757d;
            font-size: 0.9rem;
        }
        
        .button-group {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }
        
        .btn-save {
            background-color: #4361ee;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-save:hover {
            background-color: #3a0ca3;
        }
        
        .btn-cancel {
            background-color: #6c757d;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-cancel:hover {
            background-color: #5a6268;
        }
        
        .alert {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Student</h1>   
        <div id="message"></div>
        
        <form id="editForm" method="post" action="../process/editStudents.php" enctype="multipart/form-data">
            <input type="hidden" id="student_id" name="student_id">
            
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Enter student name" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Enter student email" required>
            </div>
            
            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="tel" class="form-control" id="phone" name="phone" placeholder="Enter student phone">
            </div>
            
            <div class="form-group">
                <label for="photo">Photo</label>
                <div class="file-upload">
                    <input type="file" class="file-input" id="photo" name="photo">
                    <label for="photo" class="file-label">Choose File</label>
                    <span class="file-name">No file chosen</span>
                </div>
                <div id="current-photo" class="mt-2"></div>
            </div>
            
            <div class="button-group">
                <button type="submit" class="btn-save">
                    <i class="fas fa-save me-2"></i>Update
                </button>
                <a href="dashboard.php" class="btn-cancel" style="text-decoration: none; text-align: center;">
                    <i class="fas fa-times me-2"></i>Cancel
                </a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        // Get student ID from URL
        function getStudentIdFromUrl() {
            const urlParams = new URLSearchParams(window.location.search);
            return urlParams.get('id');
        }

        // Show message
        function showMessage(message, type) {
            const messageDiv = document.getElementById('message');
            messageDiv.innerHTML = `<div class="alert alert-${type}">${message}</div>`;
        }

        // Load student data
        function loadStudentData(studentId) {
            $.ajax({
                url: '../process/getStudentsById.php?id=' + studentId,
                method: 'GET',
                success: function(response) {
                    try {
                        const student = JSON.parse(response);
                        if (student.error) {
                            showMessage(student.error, 'danger');
                            return;
                        }
                        
                        // Populate form fields
                        $('#student_id').val(student.id);
                        $('#name').val(student.full_name);
                        $('#email').val(student.email);
                        $('#phone').val(student.phone);
                        
                        // Show current photo if exists
                        if (student.photo) {
                            $('#current-photo').html(`
                                <small class="text-muted">Current Photo: ${student.photo}</small>
                            `);
                        }
                    } catch (e) {
                        console.log("Error parsing student data:", e);
                        showMessage("Error loading student data.", 'danger');
                    }
                },
                error: function(error) {
                    console.log("Error loading student data:", error);
                    showMessage("Error loading student data.", 'danger');
                }
            });
        }

        // File input functionality
        document.getElementById('photo').addEventListener('change', function(e) {
            const fileName = e.target.files[0] ? e.target.files[0].name : 'No file chosen';
            document.querySelector('.file-name').textContent = fileName;
        });

        // Form submission
        $(document).ready(function(){
            const studentId = getStudentIdFromUrl();
            
            if (studentId) {
                loadStudentData(studentId);
            } else {
                showMessage("No student ID provided.", 'danger');
            }

            $('#editForm').on('submit', function(e){
                e.preventDefault();
                
                let formData = new FormData(this);
                
                $.ajax({
                    url: '../process/editStudents.php',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response){
                        if (response.startsWith('success')) {
                            showMessage("Student updated successfully!", 'success');
                            setTimeout(function() {
                                window.location.href = "dashboard.php";
                            }, 1500);
                        } else {
                            showMessage(response, 'danger');
                        }
                    },
                    error: function(error){
                        console.log("Error:", error);
                        showMessage("Error updating student. Please try again.", 'danger');
                    }
                });
            });
        });
    </script>
</body>
</html>