<?php
require_once "../handler/check_session.php";
checkAdminSession();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="#">Admin Panel</a>
        <div class="navbar-nav ms-auto">
            <span class="navbar-text me-3">
                Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>
            </span>
            <a class="nav-link" href="../handler/logout.php">Logout</a>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <h1>Admin Dashboard</h1>
    <p>Welcome to the admin dashboard!</p>
    
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Manage Students</h5>
                    <p class="card-text">View and manage student accounts</p>
                    <a href="manage_students.php" class="btn btn-primary">Go</a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Manage Questions</h5>
                    <p class="card-text">Add, edit, or delete quiz questions</p>
                    <a href="manage_questions.php" class="btn btn-primary">Go</a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">View Results</h5>
                    <p class="card-text">View student quiz results</p>
                    <a href="view_results.php" class="btn btn-primary">Go</a>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>