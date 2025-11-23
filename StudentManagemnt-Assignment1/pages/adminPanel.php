<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduTrack - Admin Panel</title>
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
        
        .section-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 20px;
            color: var(--secondary);
            padding-bottom: 10px;
            border-bottom: 2px solid var(--accent);
            display: inline-block;
        }
        
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            text-align: center;
            transition: all 0.3s ease;
            border-top: 4px solid var(--primary);
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 5px;
        }
        
        .stat-label {
            color: var(--gray);
            font-size: 1rem;
            font-weight: 500;
        }
        
        .table-container {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            margin-top: 20px;
        }
        
        .table-title {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 20px;
            color: var(--secondary);
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th {
            background-color: #f8f9fa;
            padding: 15px;
            text-align: left;
            font-weight: 600;
            color: var(--dark);
            border-bottom: 2px solid #dee2e6;
        }
        
        td {
            padding: 15px;
            border-bottom: 1px solid #e9ecef;
        }
        
        tr:hover {
            background-color: #f8f9fa;
        }
        
        .divider {
            height: 1px;
            background: linear-gradient(to right, transparent, var(--accent), transparent);
            margin: 30px 0;
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
                <li class="active">
                    <a href="dashboard.php">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="fas fa-users"></i>
                        <span>Students</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="fas fa-chart-bar"></i>
                        <span>Reports</span>
                    </a>
                </li>
            </ul>
        </div>
        
        <!-- Main Content -->
        <div class="main-content">
            <!-- Top Header -->
            <div class="top-header">
                <h1>Admin Panel</h1>
                <button class="logout-btn">
                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                </button>
            </div>
            
            <!-- Content Area -->
            <div class="content-area">
                <h2 class="section-title">Overview</h2>
                
                <div class="stats-container">
                    <div class="stat-card">
                        <div class="stat-number">120</div>
                        <div class="stat-label">Total Students</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">5</div>
                        <div class="stat-label">Active Sessions</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">15</div>
                        <div class="stat-label">Files Uploaded</div>
                    </div>
                </div>
                
                <div class="divider"></div>
                
                <div class="table-container">
                    <h3 class="table-title">Recent Students</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Enrolled</th>
                            </tr>
                        </thead>
                        <tbody id="studentsTableBody">
                            <!-- Students will be populated here by JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <script>
        $(document).ready(function(){
            getStudents();
        });

        function getStudents() {
            $.ajax({
                url: '../process/getStudents.php',
                method: 'GET',
                success: function(response){
                    console.log("Success:", response);
                    try {
                        let students = JSON.parse(response);
                        console.log("Parsed students:", students);
                        populateStudentsTable(students);
                    } catch (e) {
                        console.log("Error parsing JSON:", e);
                        console.log("Raw response:", response);
                    }
                },
                error: function(error){
                    console.log("Error getting students:", error);
                }
            });
        }

        function populateStudentsTable(students) {
            const tbody = $('#studentsTableBody');
            tbody.empty();
            
            if (students.length === 0) {
                tbody.append('<tr><td colspan="4" class="text-center">No students found</td></tr>');
                return;
            }
            
            students.forEach(function(student) {
                const row = `
                    <tr>
                        <td>${student.id}</td>
                        <td>${student.full_name}</td>
                        <td>${student.email}</td>
                        <td>${student.created_at}</td>
                    </tr>
                `;
                tbody.append(row);
            });
        }
    </script>
</body>
</html>