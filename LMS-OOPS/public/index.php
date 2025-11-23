<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>LMS - Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <style>
        body {
            background: #f8f9fa;
        }
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.15);
            transition: 0.3s;
        }
        .card-icon {
            font-size: 2.5rem;
            color: #fff;
            background-color: #0d6efd;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            margin-right: 15px;
        }
        .card-body {
            display: flex;
            align-items: center;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"/>
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-5 text-center">📚 Library Management System</h1>
    <div class="row g-4">
        <div class="col-md-6 col-lg-3">
            <a href="manage_books.php" class="text-decoration-none">
                <div class="card card-hover shadow-sm">
                    <div class="card-body">
                        <div class="card-icon bg-primary"><i class="fas fa-book"></i></div>
                        <div>
                            <h5 class="card-title">Manage Books</h5>
                            <p class="card-text text-muted">Add, update or delete books.</p>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-6 col-lg-3">
            <a href="manage_members.php" class="text-decoration-none">
                <div class="card card-hover shadow-sm">
                    <div class="card-body">
                        <div class="card-icon bg-success"><i class="fas fa-users"></i></div>
                        <div>
                            <h5 class="card-title">Manage Members</h5>
                            <p class="card-text text-muted">Add, update or delete members.</p>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-6 col-lg-3">
            <a href="issue_return.php" class="text-decoration-none">
                <div class="card card-hover shadow-sm">
                    <div class="card-body">
                        <div class="card-icon bg-warning"><i class="fas fa-exchange-alt"></i></div>
                        <div>
                            <h5 class="card-title">Issue / Return Books</h5>
                            <p class="card-text text-muted">Manage book lending and returns.</p>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-6 col-lg-3">
            <a href="view_issued_books.php" class="text-decoration-none">
                <div class="card card-hover shadow-sm">
                    <div class="card-body">
                        <div class="card-icon bg-danger"><i class="fas fa-book-reader"></i></div>
                        <div>
                            <h5 class="card-title">View Issued Books</h5>
                            <p class="card-text text-muted">Check all issued books and their members.</p>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
