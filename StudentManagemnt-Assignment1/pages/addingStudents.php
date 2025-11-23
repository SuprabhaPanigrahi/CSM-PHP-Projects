<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student - EduTrack</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary: #4361ee;
            --secondary: #3a0ca3;
            --accent: #4cc9f0;
            --light: #f8f9fa;
            --dark: #212529;
            --gray: #6c757d;
            --sidebar-width: 250px;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fb;
            color: var(--dark);
            margin: 0;
            padding: 0;
        }
        
        .wrapper {
            display: flex;
            min-height: 100vh;
        }
        
        /* Sidebar Styles */
        .sidebar {
            width: var(--sidebar-width);
            background: linear-gradient(180deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            padding: 0;
            box-shadow: 3px 0 10px rgba(0, 0, 0, 0.1);
        }
        
        .sidebar-header {
            padding: 25px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .sidebar-header h3 {
            margin: 0;
            font-weight: 700;
            font-size: 1.5rem;
        }
        
        .sidebar-menu {
            list-style: none;
            padding: 20px 0;
            margin: 0;
        }
        
        .sidebar-menu li {
            padding: 12px 20px;
            border-left: 4px solid transparent;
            transition: all 0.3s ease;
        }
        
        .sidebar-menu li.active {
            background-color: rgba(255, 255, 255, 0.1);
            border-left: 4px solid var(--accent);
        }
        
        .sidebar-menu li:hover {
            background-color: rgba(255, 255, 255, 0.05);
        }
        
        .sidebar-menu a {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
        }
        
        .sidebar-menu i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        
        /* Main Content Styles */
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        
        /* Top Header Styles */
        .top-header {
            background-color: white;
            padding: 15px 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .top-header h1 {
            margin: 0;
            font-size: 1.8rem;
            font-weight: 600;
            color: var(--dark);
        }
        
        .logout-btn {
            background-color: var(--primary);
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .logout-btn:hover {
            background-color: var(--secondary);
            transform: translateY(-2px);
        }
        
        /* Content Area Styles */
        .content-area {
            padding: 30px;
            flex: 1;
        }
        
        .content-container {
            background-color: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            max-width: 600px;
            margin: 0 auto;
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
            border-color: var(--primary);
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
            background-color: var(--primary);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-save:hover {
            background-color: var(--secondary);
            transform: translateY(-2px);
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
            transform: translateY(-2px);
        }
        
        @media (max-width: 768px) {
            .wrapper {
                flex-direction: column;
            }
            
            .sidebar {
                width: 100%;
                height: auto;
            }
            
            .sidebar-menu {
                display: flex;
                overflow-x: auto;
            }
            
            .sidebar-menu li {
                flex: 1;
                min-width: 120px;
                text-align: center;
            }
            
            .sidebar-menu a {
                flex-direction: column;
            }
            
            .sidebar-menu i {
                margin-right: 0;
                margin-bottom: 5px;
            }
            
            .content-container {
                margin: 0;
            }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <h3><i class="fas fa-graduation-cap me-2"></i>EduTrack</h3>
            </div>
            <ul class="sidebar-menu">
                <li>
                    <a href="index.php">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="active">
                    <a href="../process/addStudents.php">
                        <i class="fas fa-users"></i>
                        <span>Students</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="fas fa-file-upload"></i>
                        <span>Uploads</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </a>
                </li>
            </ul>
        </div>
        
        <!-- Main Content -->
        <div class="main-content">
            <!-- Top Header -->
            <div class="top-header">
                <h1>Dashboard</h1>
                <button class="logout-btn">
                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                </button>
            </div>
            
            <!-- Content Area -->
            <div class="content-area">
                <div class="content-container">
                    <h1>Add Student</h1>
                    
                    <!-- FIXED FORM: Added name attributes and enctype -->
                    <form id="formID" method="post" action="../process/addStudents.php" enctype="multipart/form-data">
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
                        </div>
                        
                        <div class="button-group">
                            <button type="submit" class="btn-save" id="addbtn">
                                <i class="fas fa-save me-2"></i>Save
                            </button>
                            <a href="students.php" class="btn btn-secondary" style="padding: 12px 30px; text-decoration: none;">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        // File input functionality
        document.getElementById('photo').addEventListener('change', function(e) {
            const fileName = e.target.files[0] ? e.target.files[0].name : 'No file chosen';
            document.querySelector('.file-name').textContent = fileName;
        });

        $(document).ready(function(){
            $('#formID').on('submit', function(e){
                e.preventDefault();
                
                // Create FormData for file upload
                let formData = new FormData(this);
                
                $.ajax({
                    url: '../process/addStudents.php',
                    method: 'POST',
                    data: formData,
                    processData: false, // Important for file uploads
                    contentType: false, // Important for file uploads
                    success: function(response){
                        console.log("Success:", response);
                        alert("Student added successfully!");
                        window.location.href = "dashboard.php";
                    },
                    error: function(error){
                        console.log("Error:", error);
                        alert("Error adding student. Please try again.");
                    }
                });
            });
        });
    </script>
</body>
</html>