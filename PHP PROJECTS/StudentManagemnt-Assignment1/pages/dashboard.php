<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Students - EduTrack</title>
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
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        h1 {
            color: #2c3e50;
            font-weight: 600;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 1px solid #dee2e6;
        }

        .alert {
            padding: 12px 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            display: none;
        }

        .alert-success {
            background-color: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
        }

        .alert-danger {
            background-color: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
        }

        .search-box {
            margin-bottom: 25px;
        }

        .search-box input {
            border-radius: 6px;
            border: 1px solid #ced4da;
            padding: 10px 15px;
            width: 300px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 25px;
        }

        th {
            background-color: #f8f9fa;
            padding: 15px;
            text-align: left;
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
            color: #495057;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid #e9ecef;
        }

        tr:last-child td {
            border-bottom: none;
        }

        .add-student-btn {
            background-color: #4361ee;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: 500;
            transition: background-color 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .add-student-btn:hover {
            background-color: #3a0ca3;
            color: white;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .status-active {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .status-inactive {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .actions {
            display: flex;
            gap: 10px;
        }

        .btn-edit {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-edit:hover {
            background-color: #218838;
            transform: translateY(-1px);
        }

        .btn-delete {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-delete:hover {
            background-color: #c82333;
            transform: translateY(-1px);
        }

        /* Loading Spinner */
        .spinner-border {
            width: 1rem;
            height: 1rem;
            margin-right: 8px;
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
            
            .actions {
                flex-direction: column;
                gap: 5px;
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
                    <a href="dashboard.php">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="active">
                    <a href="#">
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
                    <h1>Students</h1>

                    <!-- Success and Error Messages -->
                    <div id="successMessage" class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i>
                        <span id="successText"></span>
                    </div>

                    <div id="errorMessage" class="alert alert-danger">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <span id="errorText"></span>
                    </div>

                    <div class="search-box">
                        <input type="text" class="form-control" placeholder="Search...">
                    </div>

                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="studentsTableBody">
                            <!-- Students will be populated here -->
                            <tr>
                                <td colspan="6" class="text-center">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    Loading students...
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <a href="addingStudents.php" class="add-student-btn">
                        <i class="fas fa-plus me-2"></i>Add Student
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete student <strong id="studentName"></strong>? This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">
                        <i class="fas fa-trash me-1"></i>Delete Student
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        let studentToDelete = null;
        let studentNameToDelete = '';

        $(document).ready(function() {
            getStudents();
        });

        function getStudents() {
            $.ajax({
                url: '../process/getStudents.php',
                method: 'GET',
                success: function(response) {
                    try {
                        let students = JSON.parse(response);
                        populateStudentsTable(students);
                    } catch (e) {
                        console.log("Error parsing JSON:", e);
                        showError("Error loading students data.");
                    }
                },
                error: function(error) {
                    console.log("Error getting students:", error);
                    showError("Error loading students. Please try again.");
                }
            });
        }

        function populateStudentsTable(students) {
            const tbody = $('#studentsTableBody');
            tbody.empty();

            if (students.length === 0) {
                tbody.append('<tr><td colspan="6" class="text-center">No students found</td></tr>');
                return;
            }

            students.forEach(function(student) {
                const status = student.status === 'active' || Math.random() > 0.3 ? 'Active' : 'Inactive';
                const statusClass = status === 'Active' ? 'status-active' : 'status-inactive';
                
                const row = `
                    <tr id="student-${student.id}">
                        <td>${student.id}</td>
                        <td>${student.full_name}</td>
                        <td>${student.email}</td>
                        <td>${student.phone || 'N/A'}</td>
                        <td><span class="status-badge ${statusClass}">${status}</span></td>
                        <td>
                            <div class="actions">
                                <button class="btn-edit" onclick="editStudent(${student.id})">
                                    <i class="fas fa-edit me-1"></i>Edit
                                </button>
                                <button class="btn-delete" onclick="confirmDelete(${student.id}, '${student.full_name.replace(/'/g, "\\'")}')">
                                    <i class="fas fa-trash me-1"></i>Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
                tbody.append(row);
            });
        }

        function editStudent(studentId) {
            window.location.href = `edittingStudents.php?id=${studentId}`;
        }

        function confirmDelete(studentId, studentName) {
            studentToDelete = studentId;
            studentNameToDelete = studentName;
            
            $('#studentName').text(studentName);
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            deleteModal.show();
        }

        function deleteStudent() {
            if (!studentToDelete) return;

            // Show loading state
            $('#confirmDelete').prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status"></span> Deleting...');

            $.ajax({
                url: `../process/deleteStudents.php?id=${studentToDelete}`,
                method: 'GET',
                success: function(response) {
                    $('#confirmDelete').prop('disabled', false).html('<i class="fas fa-trash me-1"></i>Delete Student');
                    
                    if (response.startsWith('success')) {
                        // Remove the student row from table
                        $(`#student-${studentToDelete}`).remove();
                        
                        // Show success message
                        showSuccess(`Student "${studentNameToDelete}" deleted successfully!`);
                        
                        // Close modal
                        const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteModal'));
                        deleteModal.hide();
                        
                        // Check if table is empty
                        if ($('#studentsTableBody tr').length === 0) {
                            $('#studentsTableBody').html('<tr><td colspan="6" class="text-center">No students found</td></tr>');
                        }
                    } else {
                        showError(response.replace('error:', ''));
                    }
                },
                error: function(error) {
                    $('#confirmDelete').prop('disabled', false).html('<i class="fas fa-trash me-1"></i>Delete Student');
                    console.log("Error deleting student:", error);
                    showError("Error deleting student. Please try again.");
                }
            });
        }

        function showSuccess(message) {
            $('#successText').text(message);
            $('#successMessage').fadeIn();
            
            // Auto hide after 5 seconds
            setTimeout(() => {
                $('#successMessage').fadeOut();
            }, 5000);
        }

        function showError(message) {
            $('#errorText').text(message);
            $('#errorMessage').fadeIn();
            
            // Auto hide after 5 seconds
            setTimeout(() => {
                $('#errorMessage').fadeOut();
            }, 5000);
        }

        // Set up event listener for confirm delete button
        document.getElementById('confirmDelete').addEventListener('click', deleteStudent);
    </script>
</body>
</html>