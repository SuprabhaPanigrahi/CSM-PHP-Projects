<!DOCTYPE html>
<html>
<head>
    <title>Manage Students</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .action-btns .btn { margin-right: 5px; }
        .table th { background-color: #f8f9fa; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="dashboard.php">Admin Panel</a>
        <div class="navbar-nav ms-auto">
            <a class="nav-link" href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            <a class="nav-link" href="../handler/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-users"></i> Manage Students</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStudentModal">
            <i class="fas fa-plus"></i> Add New Student
        </button>
    </div>

    <!-- Search and Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <input type="text" id="searchInput" class="form-control" placeholder="Search students...">
                </div>
                <div class="col-md-3">
                    <select id="batchFilter" class="form-control">
                        <option value="">All Batches</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select id="techFilter" class="form-control">
                        <option value="">All Technologies</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Students Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Gender</th>
                            <th>Batch</th>
                            <th>Technology</th>
                            <th>Created Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="student_list">
                        <!-- Dynamic content will be loaded here -->
                    </tbody>
                </table>
            </div>
            <div id="loading" class="text-center">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
            <div id="noStudents" class="text-center" style="display: none;">
                <p class="text-muted">No students found.</p>
            </div>
        </div>
    </div>
</div>

<!-- Add Student Modal -->
<div class="modal fade" id="addStudentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Student</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addStudentForm">
                    <div class="mb-3">
                        <label class="form-label">Name *</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email *</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Gender</label>
                        <select name="gender" class="form-control">
                            <option value="">Select Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Batch *</label>
                        <select name="batch_id" class="form-control" required id="batchSelectModal">
                            <option value="">Select Batch</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Technology *</label>
                        <select name="tech_id" class="form-control" required id="techSelectModal">
                            <option value="">Select Technology</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="addStudent()">Add Student</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Student Modal -->
<div class="modal fade" id="editStudentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Student</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editStudentForm">
                    <input type="hidden" name="id" id="editStudentId">
                    <div class="mb-3">
                        <label class="form-label">Name *</label>
                        <input type="text" name="name" id="editStudentName" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email *</label>
                        <input type="email" name="email" id="editStudentEmail" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" id="editStudentPhone" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Gender</label>
                        <select name="gender" id="editStudentGender" class="form-control">
                            <option value="">Select Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Batch *</label>
                        <select name="batch_id" id="editStudentBatch" class="form-control" required>
                            <option value="">Select Batch</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Technology *</label>
                        <select name="tech_id" id="editStudentTech" class="form-control" required>
                            <option value="">Select Technology</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="updateStudent()">Update Student</button>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function() {
    loadStudents();
    loadFilters();
    loadModalOptions();
});

function loadStudents() {
    $('#loading').show();
    $('#student_list').html('');
    $('#noStudents').hide();

    $.ajax({
        url: '../handler/getStudents.php',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            $('#loading').hide();
            if (response.status === 'success' && response.students.length > 0) {
                displayStudents(response.students);
            } else {
                $('#noStudents').show();
            }
        },
        error: function() {
            $('#loading').hide();
            $('#noStudents').show();
        }
    });
}

function displayStudents(students) {
    let html = '';
    students.forEach(student => {
        html += `
            <tr>
                <td>${student.id}</td>
                <td>${student.name}</td>
                <td>${student.email}</td>
                <td>${student.phone || '-'}</td>
                <td>${student.gender ? student.gender.charAt(0).toUpperCase() + student.gender.slice(1) : '-'}</td>
                <td>${student.batch_name || '-'}</td>
                <td>${student.tech_name || '-'}</td>
                <td>${student.created_at ? new Date(student.created_at).toLocaleDateString() : '-'}</td>
                <td class="action-btns">
                    <button class="btn btn-sm btn-warning" onclick="editStudent(${student.id})">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-danger" onclick="deleteStudent(${student.id})">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
    });
    $('#student_list').html(html);
}

function loadFilters() {
    // Load batches for filter
    $.ajax({
        url: '../handler/getBatches.php',
        method: 'GET',
        success: function(response) {
            if (response.status === 'success') {
                response.batches.forEach(batch => {
                    $('#batchFilter').append(`<option value="${batch.id}">${batch.name}</option>`);
                });
            }
        }
    });

    // Load technologies for filter
    $.ajax({
        url: '../handler/getTechnologies.php',
        method: 'GET',
        success: function(response) {
            if (response.status === 'success') {
                response.technologies.forEach(tech => {
                    $('#techFilter').append(`<option value="${tech.id}">${tech.name}</option>`);
                });
            }
        }
    });
}

function loadModalOptions() {
    // Load batches for modal
    $.ajax({
        url: '../handler/getBatches.php',
        method: 'GET',
        success: function(response) {
            if (response.status === 'success') {
                $('#batchSelectModal').html('<option value="">Select Batch</option>');
                $('#editStudentBatch').html('<option value="">Select Batch</option>');
                response.batches.forEach(batch => {
                    $('#batchSelectModal').append(`<option value="${batch.id}">${batch.name}</option>`);
                    $('#editStudentBatch').append(`<option value="${batch.id}">${batch.name}</option>`);
                });
            }
        }
    });

    // Load technologies for modal
    $.ajax({
        url: '../handler/getTechnologies.php',
        method: 'GET',
        success: function(response) {
            if (response.status === 'success') {
                $('#techSelectModal').html('<option value="">Select Technology</option>');
                $('#editStudentTech').html('<option value="">Select Technology</option>');
                response.technologies.forEach(tech => {
                    $('#techSelectModal').append(`<option value="${tech.id}">${tech.name}</option>`);
                    $('#editStudentTech').append(`<option value="${tech.id}">${tech.name}</option>`);
                });
            }
        }
    });
}

function addStudent() {
    const formData = $('#addStudentForm').serialize();
    
    $.ajax({
        url: '../handler/addStudent.php',
        method: 'POST',
        data: formData,
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                $('#addStudentModal').modal('hide');
                $('#addStudentForm')[0].reset();
                loadStudents();
                alert('Student added successfully!');
            } else {
                alert('Error: ' + response.message);
            }
        },
        error: function() {
            alert('Error adding student. Please try again.');
        }
    });
}

function editStudent(studentId) {
    $.ajax({
        url: '../handler/getStudent.php',
        method: 'GET',
        data: { id: studentId },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                const student = response.student;
                $('#editStudentId').val(student.id);
                $('#editStudentName').val(student.name);
                $('#editStudentEmail').val(student.email);
                $('#editStudentPhone').val(student.phone || '');
                $('#editStudentGender').val(student.gender || '');
                $('#editStudentBatch').val(student.batch_id);
                $('#editStudentTech').val(student.tech_id);
                $('#editStudentModal').modal('show');
            } else {
                alert('Error loading student data');
            }
        }
    });
}

function updateStudent() {
    const formData = $('#editStudentForm').serialize();
    
    $.ajax({
        url: '../handler/updateStudent.php',
        method: 'POST',
        data: formData,
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                $('#editStudentModal').modal('hide');
                loadStudents();
                alert('Student updated successfully!');
            } else {
                alert('Error: ' + response.message);
            }
        },
        error: function() {
            alert('Error updating student. Please try again.');
        }
    });
}

function deleteStudent(studentId) {
    if (confirm('Are you sure you want to delete this student?')) {
        $.ajax({
            url: '../handler/deleteStudent.php',
            method: 'POST',
            data: { id: studentId },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    loadStudents();
                    alert('Student deleted successfully!');
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function() {
                alert('Error deleting student. Please try again.');
            }
        });
    }
}

// Search functionality
$('#searchInput').on('input', function() {
    // Implement search functionality here
});

// Filter functionality
$('#batchFilter, #techFilter').change(function() {
    // Implement filter functionality here
});
</script>

</body>
</html>