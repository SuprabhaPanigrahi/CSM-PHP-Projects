<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Multi-Table Employee Data | Laravel</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: #333;
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 1400px;
            margin: 0 auto;
        }
        
        .header {
            text-align: center;
            color: white;
            margin-bottom: 30px;
            padding: 20px;
        }
        
        .header h1 {
            font-size: 2.8rem;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        
        .header p {
            font-size: 1.2rem;
            opacity: 0.9;
        }
        
        .card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.15);
        }
        
        .card-title {
            color: #2a5298;
            border-bottom: 3px solid #2a5298;
            padding-bottom: 15px;
            margin-bottom: 20px;
            font-size: 1.8rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .card-title i {
            font-size: 1.5rem;
        }
        
        .form-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 25px;
        }
        
        .form-section {
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
        }
        
        .form-section h3 {
            color: #1e3c72;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #1e3c72;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: #555;
            font-size: 0.9rem;
        }
        
        input, select {
            width: 100%;
            padding: 10px 12px;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            font-size: 15px;
        }
        
        input:focus, select:focus {
            outline: none;
            border-color: #2a5298;
        }
        
        .btn-submit {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            border: none;
            padding: 15px 40px;
            border-radius: 8px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 20px;
            width: 100%;
        }
        
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(30, 60, 114, 0.3);
        }
        
        /* Tables Styling */
        .tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        
        .tab-btn {
            padding: 12px 25px;
            background: #f1f3f5;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            color: #555;
            transition: all 0.3s;
        }
        
        .tab-btn.active {
            background: #2a5298;
            color: white;
        }
        
        .tab-content {
            display: none;
        }
        
        .tab-content.active {
            display: block;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        
        th {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            padding: 15px;
            text-align: left;
            font-weight: 600;
            position: sticky;
            top: 0;
        }
        
        td {
            padding: 12px 15px;
            border-bottom: 1px solid #e0e0e0;
        }
        
        tr:hover {
            background-color: #f8f9fa;
        }
        
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .highlight {
            background-color: #e3f2fd !important;
        }
        
        .salary-cell {
            font-weight: 600;
            color: #2e7d32;
        }
        
        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: 500;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .stats-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }
        
        .stat-value {
            font-size: 2.5rem;
            font-weight: 700;
            color: #1e3c72;
            margin: 10px 0;
        }
        
        .stat-label {
            color: #666;
            font-size: 0.9rem;
        }
        
        .badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .badge-it {
            background: #e3f2fd;
            color: #1565c0;
        }
        
        .badge-hr {
            background: #f3e5f5;
            color: #7b1fa2;
        }
        
        .badge-finance {
            background: #e8f5e9;
            color: #2e7d32;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📊 Multi-Table Employee Management System</h1>
            <p>Fetching & Displaying Data from 3 Tables: Employees, Departments, Salaries</p>
        </div>
        
        @if(session('success'))
            <div class="alert alert-success">
                ✅ {{ session('success') }}
            </div>
        @endif
        
        <!-- Statistics Cards -->
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-label">Total Employees</div>
                <div class="stat-value">{{ $allEmployees->count() }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Total Departments</div>
                <div class="stat-value">{{ $allDepartments->count() }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Salary Records</div>
                <div class="stat-value">{{ $allSalaries->count() }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Total Net Salary</div>
                <div class="stat-value">${{ number_format($allSalaries->sum('net_salary'), 2) }}</div>
            </div>
        </div>
        
        <!-- Add Employee Form -->
        <div class="card">
            <div class="card-title">
                <i>➕</i> Add New Employee (All 3 Tables)
            </div>
            
            <form method="POST" action="{{ route('employee.add') }}">
                @csrf
                
                <div class="form-container">
                    <!-- Employee Information -->
                    <div class="form-section">
                        <h3>👤 Employee Details</h3>
                        <div class="form-group">
                            <label>Employee Name *</label>
                            <input type="text" name="emp_name" required>
                        </div>
                        <div class="form-group">
                            <label>Employee Code *</label>
                            <input type="text" name="emp_code" required>
                        </div>
                        <div class="form-group">
                            <label>Email *</label>
                            <input type="email" name="emp_email" required>
                        </div>
                        <div class="form-group">
                            <label>Phone *</label>
                            <input type="text" name="emp_phone" required>
                        </div>
                        <div class="form-group">
                            <label>Joining Date *</label>
                            <input type="date" name="joining_date" required>
                        </div>
                    </div>
                    
                    <!-- Department Information -->
                    <div class="form-section">
                        <h3>🏢 Department Details</h3>
                        <div class="form-group">
                            <label>Department Name *</label>
                            <input type="text" name="dept_name" required>
                        </div>
                        <div class="form-group">
                            <label>Department Head *</label>
                            <input type="text" name="dept_head" required>
                        </div>
                        <div class="form-group">
                            <label>Location *</label>
                            <select name="location" required>
                                <option value="">Select Location</option>
                                <option value="New York">New York</option>
                                <option value="London">London</option>
                                <option value="Tokyo">Tokyo</option>
                                <option value="Dubai">Dubai</option>
                                <option value="Singapore">Singapore</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Salary Information -->
                    <div class="form-section">
                        <h3>💰 Salary Details</h3>
                        <div class="form-group">
                            <label>Basic Salary ($) *</label>
                            <input type="number" name="basic_salary" step="0.01" required>
                        </div>
                        <div class="form-group">
                            <label>Allowances ($) *</label>
                            <input type="number" name="allowances" step="0.01" required>
                        </div>
                        <div class="form-group">
                            <label>Deductions ($) *</label>
                            <input type="number" name="deductions" step="0.01" required>
                        </div>
                        <div class="form-group">
                            <label>Payment Date *</label>
                            <input type="date" name="payment_date" required>
                        </div>
                    </div>
                </div>
                
                <button type="submit" class="btn-submit">
                    💾 Save Employee to All 3 Tables
                </button>
            </form>
        </div>
        
        <!-- Tabs for Different Views -->
        <div class="card">
            <div class="tabs">
                <button class="tab-btn active" onclick="showTab('combined')">🔗 Combined View (All 3 Tables)</button>
                <button class="tab-btn" onclick="showTab('employees')">👥 Employees Table</button>
                <button class="tab-btn" onclick="showTab('departments')">🏢 Departments Table</button>
                <button class="tab-btn" onclick="showTab('salaries')">💰 Salaries Table</button>
            </div>
            
            <!-- Tab 1: Combined Data -->
            <div id="combined" class="tab-content active">
                <h3>🔗 Combined Data from Employees, Departments & Salaries Tables</h3>
                
                @if($combinedData->count() > 0)
                <div style="overflow-x: auto;">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Code</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Join Date</th>
                                <th>Department</th>
                                <th>Dept Head</th>
                                <th>Location</th>
                                <th>Basic Salary</th>
                                <th>Allowances</th>
                                <th>Deductions</th>
                                <th>Net Salary</th>
                                <th>Payment Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($combinedData as $data)
                            <tr>
                                <td>{{ $data->id }}</td>
                                <td><strong>{{ $data->emp_name }}</strong></td>
                                <td>{{ $data->emp_code }}</td>
                                <td>{{ $data->emp_email }}</td>
                                <td>{{ $data->emp_phone }}</td>
                                <td>{{ \Carbon\Carbon::parse($data->joining_date)->format('d M Y') }}</td>
                                <td>
                                    <span class="badge 
                                        {{ str_contains($data->dept_name, 'IT') ? 'badge-it' : '' }}
                                        {{ str_contains($data->dept_name, 'HR') ? 'badge-hr' : '' }}
                                        {{ str_contains($data->dept_name, 'Finance') ? 'badge-finance' : '' }}">
                                        {{ $data->dept_name }}
                                    </span>
                                </td>
                                <td>{{ $data->dept_head }}</td>
                                <td>{{ $data->location }}</td>
                                <td class="salary-cell">${{ number_format($data->basic_salary, 2) }}</td>
                                <td class="salary-cell">${{ number_format($data->allowances, 2) }}</td>
                                <td class="salary-cell">${{ number_format($data->deductions, 2) }}</td>
                                <td class="salary-cell" style="color: #2e7d32; font-weight: bold;">
                                    ${{ number_format($data->net_salary, 2) }}
                                </td>
                                <td>{{ \Carbon\Carbon::parse($data->payment_date)->format('d M Y') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                    <p style="text-align: center; padding: 40px; color: #666;">
                        No combined data found. Add employees to see data from all tables.
                    </p>
                @endif
            </div>
            
            <!-- Tab 2: Employees Table -->
            <div id="employees" class="tab-content">
                <h3>👥 Employees Table Data</h3>
                
                @if($allEmployees->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Code</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Join Date</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($allEmployees as $employee)
                        <tr>
                            <td>{{ $employee->id }}</td>
                            <td><strong>{{ $employee->emp_name }}</strong></td>
                            <td>{{ $employee->emp_code }}</td>
                            <td>{{ $employee->emp_email }}</td>
                            <td>{{ $employee->emp_phone }}</td>
                            <td>{{ \Carbon\Carbon::parse($employee->joining_date)->format('d M Y') }}</td>
                            <td>{{ $employee->created_at->format('d M Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                    <p>No employees found.</p>
                @endif
            </div>
            
            <!-- Tab 3: Departments Table -->
            <div id="departments" class="tab-content">
                <h3>🏢 Departments Table Data</h3>
                
                @if($allDepartments->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Employee ID</th>
                            <th>Department</th>
                            <th>Department Head</th>
                            <th>Location</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($allDepartments as $dept)
                        <tr>
                            <td>{{ $dept->id }}</td>
                            <td>{{ $dept->emp_id }}</td>
                            <td>{{ $dept->dept_name }}</td>
                            <td>{{ $dept->dept_head }}</td>
                            <td>{{ $dept->location }}</td>
                            <td>{{ $dept->created_at->format('d M Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                    <p>No departments found.</p>
                @endif
            </div>
            
            <!-- Tab 4: Salaries Table -->
            <div id="salaries" class="tab-content">
                <h3>💰 Salaries Table Data</h3>
                
                @if($allSalaries->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Employee ID</th>
                            <th>Basic Salary</th>
                            <th>Allowances</th>
                            <th>Deductions</th>
                            <th>Net Salary</th>
                            <th>Payment Date</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($allSalaries as $salary)
                        <tr>
                            <td>{{ $salary->id }}</td>
                            <td>{{ $salary->emp_id }}</td>
                            <td>${{ number_format($salary->basic_salary, 2) }}</td>
                            <td>${{ number_format($salary->allowances, 2) }}</td>
                            <td>${{ number_format($salary->deductions, 2) }}</td>
                            <td style="font-weight: bold; color: #2e7d32;">
                                ${{ number_format($salary->net_salary, 2) }}
                            </td>
                            <td>{{ \Carbon\Carbon::parse($salary->payment_date)->format('d M Y') }}</td>
                            <td>{{ $salary->created_at->format('d M Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                    <p>No salary records found.</p>
                @endif
            </div>
        </div>
    </div>
    
    <script>
        function showTab(tabName) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });
            
            // Remove active class from all buttons
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            
            // Show selected tab
            document.getElementById(tabName).classList.add('active');
            
            // Add active class to clicked button
            event.target.classList.add('active');
        }
        
        // Auto-calculate net salary
        document.addEventListener('DOMContentLoaded', function() {
            const basicInput = document.querySelector('input[name="basic_salary"]');
            const allowanceInput = document.querySelector('input[name="allowances"]');
            const deductionInput = document.querySelector('input[name="deductions"]');
            
            function calculateNet() {
                const basic = parseFloat(basicInput.value) || 0;
                const allowance = parseFloat(allowanceInput.value) || 0;
                const deduction = parseFloat(deductionInput.value) || 0;
                
                // You can show the net salary in an alert or console
                const net = basic + allowance - deduction;
                console.log('Net Salary would be: $' + net.toFixed(2));
            }
            
            if(basicInput && allowanceInput && deductionInput) {
                basicInput.addEventListener('input', calculateNet);
                allowanceInput.addEventListener('input', calculateNet);
                deductionInput.addEventListener('input', calculateNet);
            }
        });
    </script>
</body>
</html>