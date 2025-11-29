<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Citizen Registration System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .welcome-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            overflow: hidden;
        }
        .welcome-header {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
            padding: 40px 20px;
            text-align: center;
        }
        .feature-card {
            border: none;
            border-radius: 10px;
            transition: transform 0.3s ease;
            margin-bottom: 20px;
        }
        .feature-card:hover {
            transform: translateY(-5px);
        }
        .feature-icon {
            font-size: 2.5rem;
            margin-bottom: 15px;
        }
        .btn-primary {
            background: linear-gradient(135deg, #3498db, #2980b9);
            border: none;
            padding: 12px 30px;
            font-weight: 600;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #2980b9, #3498db);
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="welcome-container">
                    <!-- Header Section -->
                    <div class="welcome-header">
                        <h1 class="display-4 fw-bold mb-3">
                            <i class="fas fa-users me-3"></i>Citizen Registration System
                        </h1>
                        <p class="lead mb-4">Efficiently manage citizen data with our comprehensive registration platform</p>
                        <div class="row justify-content-center">
                            <div class="col-md-8">
                                <div class="alert alert-light" role="alert">
                                    <strong>Welcome!</strong> Get started by registering new citizens or managing existing records.
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main Content -->
                    <div class="p-5">
                        <!-- Quick Stats -->
                        <div class="row mb-5">
                            <div class="col-md-3 col-6 text-center">
                                <div class="feature-card p-3 bg-light">
                                    <div class="feature-icon text-primary">
                                        <i class="fas fa-user-plus"></i>
                                    </div>
                                    <h5>Register</h5>
                                    <p class="text-muted">Add new citizens</p>
                                </div>
                            </div>
                            <div class="col-md-3 col-6 text-center">
                                <div class="feature-card p-3 bg-light">
                                    <div class="feature-icon text-success">
                                        <i class="fas fa-list"></i>
                                    </div>
                                    <h5>View</h5>
                                    <p class="text-muted">Browse all records</p>
                                </div>
                            </div>
                            <div class="col-md-3 col-6 text-center">
                                <div class="feature-card p-3 bg-light">
                                    <div class="feature-icon text-warning">
                                        <i class="fas fa-edit"></i>
                                    </div>
                                    <h5>Update</h5>
                                    <p class="text-muted">Edit citizen data</p>
                                </div>
                            </div>
                            <div class="col-md-3 col-6 text-center">
                                <div class="feature-card p-3 bg-light">
                                    <div class="feature-icon text-danger">
                                        <i class="fas fa-chart-bar"></i>
                                    </div>
                                    <h5>Reports</h5>
                                    <p class="text-muted">Generate insights</p>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <div class="card feature-card">
                                    <div class="card-body text-center">
                                        <h4 class="card-title text-primary">
                                            <i class="fas fa-user-plus me-2"></i>New Registration
                                        </h4>
                                        <p class="card-text">Register a new citizen with complete demographic and location details</p>
                                        <a href="{{ route('citizens.create') }}" class="btn btn-primary btn-lg">
                                            Start Registration
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="card feature-card">
                                    <div class="card-body text-center">
                                        <h4 class="card-title text-success">
                                            <i class="fas fa-list me-2"></i>View Citizens
                                        </h4>
                                        <p class="card-text">Browse, search, and manage existing citizen records</p>
                                        <a href="{{ route('citizens.index') }}" class="btn btn-success btn-lg">
                                            View All Citizens
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Features List -->
                        <div class="row">
                            <div class="col-12">
                                <h3 class="text-center mb-4">System Features</h3>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <div class="d-flex align-items-start">
                                            <i class="fas fa-map-marker-alt text-primary me-3 mt-1"></i>
                                            <div>
                                                <h6>Location Management</h6>
                                                <small class="text-muted">State → Block → Panchayat → Village hierarchy</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="d-flex align-items-start">
                                            <i class="fas fa-search text-success me-3 mt-1"></i>
                                            <div>
                                                <h6>Advanced Search</h6>
                                                <small class="text-muted">Find citizens by name, location, or phone</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="d-flex align-items-start">
                                            <i class="fas fa-shield-alt text-warning me-3 mt-1"></i>
                                            <div>
                                                <h6>Data Security</h6>
                                                <small class="text-muted">Secure storage and privacy protection</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Links -->
                        <div class="text-center mt-5">
                            <div class="btn-group" role="group">
                                <a href="{{ route('citizens.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>Add Citizen
                                </a>
                                <a href="{{ route('citizens.index') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-list me-2"></i>View All
                                </a>
                                <a href="#" class="btn btn-outline-secondary">
                                    <i class="fas fa-question-circle me-2"></i>Help
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="bg-light p-4 text-center">
                        <p class="mb-0 text-muted">
                            &copy; 2024 Citizen Registration System. 
                            <span class="text-primary">Efficient • Reliable • Secure</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
</body>
</html>