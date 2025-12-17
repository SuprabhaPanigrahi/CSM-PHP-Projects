<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LMS Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .tabs {
            margin-bottom: 20px;
        }

        .tab {
            padding: 10px 20px;
            cursor: pointer;
            background: #f0f0f0;
            border: 1px solid #ccc;
        }

        .tab.active {
            background: #007bff;
            color: white;
        }

        .form-group {
            margin-bottom: 10px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 5px;
        }

        button {
            padding: 10px 20px;
            background: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Leave Management System</h1>

        <div class="tabs">
            <button class="tab active" onclick="showTab('employees')">Employees</button>
            <button class="tab" onclick="showTab('leaves')">Leaves</button>
            <button class="tab" onclick="showTab('login')">Login</button>
        </div>

        <!-- Login Form -->
        <div id="login-tab" style="display: none;">
            <h2>Login</h2>
            <div class="form-group">
                <label>Username:</label>
                <input type="text" id="username">
            </div>
            <div class="form-group">
                <label>Password:</label>
                <input type="password" id="password">
            </div>
            <button onclick="login()">Login</button>
            <div id="login-message"></div>
        </div>

        <!-- Employees Tab -->
        <div id="employees-tab">
            <h2>Employees</h2>
            <form id="employee-form">
                <input type="hidden" id="employee-id">
                <div class="form-group">
                    <label>First Name:</label>
                    <input type="text" id="firstname" required>
                </div>
                <div class="form-group">
                    <label>Last Name:</label>
                    <input type="text" id="lastname" required>
                </div>
                <div class="form-group">
                    <label>Department:</label>
                    <input type="text" id="department" required>
                </div>
                <div class="form-group">
                    <label>Date of Birth:</label>
                    <input type="date" id="date_of_birth" required>
                </div>
                <button type="button" onclick="saveEmployee()" id="employee-btn">Add Employee</button>
                <button type="button" onclick="clearEmployeeForm()">Clear</button>
            </form>

            <h3>Employee List</h3>
            <input type="text" id="department-filter" placeholder="Filter by department">
            <button onclick="filterEmployees()">Filter</button>
            <table id="employees-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Department</th>
                        <th>Date of Birth</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>

        <!-- Leaves Tab -->
        <div id="leaves-tab" style="display: none;">
            <h2>Leaves</h2>
            <form id="leave-form">
                <input type="hidden" id="leave-id">
                <div class="form-group">
                    <label>Employee ID:</label>
                    <input type="number" id="employee_id" required>
                </div>
                <div class="form-group">
                    <label>Description:</label>
                    <textarea id="description" required></textarea>
                </div>
                <div class="form-group">
                    <label>Start Date:</label>
                    <input type="date" id="start_date" required>
                </div>
                <div class="form-group">
                    <label>End Date:</label>
                    <input type="date" id="end_date" required>
                </div>
                <div class="form-group">
                    <label>Approved:</label>
                    <select id="approved">
                        <option value="false">No</option>
                        <option value="true">Yes</option>
                    </select>
                </div>
                <button type="button" onclick="saveLeave()" id="leave-btn">Add Leave</button>
                <button type="button" onclick="clearLeaveForm()">Clear</button>
            </form>

            <h3>Leave List</h3>
            <div class="form-group">
                <label>Start Date:</label>
                <input type="date" id="start-date-filter">
                <label>End Date:</label>
                <input type="date" id="end-date-filter">
                <label>Approved:</label>
                <select id="approved-filter">
                    <option value="">All</option>
                    <option value="true">Approved</option>
                    <option value="false">Not Approved</option>
                </select>
                <button onclick="filterLeaves()">Filter</button>
            </div>
            <table id="leaves-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Employee ID</th>
                        <th>Description</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Approved</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    <script>
        let currentUser = null;

        function showTab(tabName, event = null) { // Add event parameter with default null
            // Hide all tabs
            document.querySelectorAll('[id$="-tab"]').forEach(tab => {
                tab.style.display = 'none';
            });

            // Remove active class from all tabs
            document.querySelectorAll('.tab').forEach(tab => {
                tab.classList.remove('active');
            });

            // Show selected tab
            document.getElementById(`${tabName}-tab`).style.display = 'block';

            // Add active class to clicked tab (if event exists)
            if (event) {
                event.target.classList.add('active');
            } else {
                // Find and activate the tab button
                document.querySelectorAll('.tab').forEach(tab => {
                    if (tab.textContent.toLowerCase().includes(tabName.toLowerCase())) {
                        tab.classList.add('active');
                    }
                });
            }

            // Load data if needed
            if (tabName === 'employees') {
                loadEmployees();
            } else if (tabName === 'leaves') {
                loadLeaves();
            }
        }

        function login() {
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;

            fetch('/api/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        username,
                        password
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (response.ok) {
                        currentUser = data.user;
                        document.getElementById('login-message').innerHTML =
                            `<p style="color: green;">Login successful! Role: ${data.user.role}</p>`;
                        showTab('employees');
                    } else {
                        document.getElementById('login-message').innerHTML =
                            `<p style="color: red;">${data.message}</p>`;
                    }
                });
        }

        function loadEmployees() {
            const filter = document.getElementById('department-filter').value;
            let url = '/api/employees';
            if (filter) {
                url += `?department=${encodeURIComponent(filter)}`;
            }

            fetch(url, {
                    headers: currentUser ? getAuthHeaders() : {}
                })
                .then(response => {
                    if (response.status === 204) {
                        document.querySelector('#employees-table tbody').innerHTML =
                            '<tr><td colspan="6">No employees found</td></tr>';
                        return;
                    }
                    return response.json();
                })
                .then(data => {
                    if (data) {
                        const tbody = document.querySelector('#employees-table tbody');
                        tbody.innerHTML = '';
                        data.forEach(emp => {
                            const row = tbody.insertRow();
                            row.innerHTML = `
                            <td>${emp.id}</td>
                            <td>${emp.firstname}</td>
                            <td>${emp.lastname}</td>
                            <td>${emp.department}</td>
                            <td>${emp.date_of_birth}</td>
                            <td>
                                <button onclick="editEmployee(${emp.id})">Edit</button>
                                <button onclick="deleteEmployee(${emp.id})">Delete</button>
                            </td>
                        `;
                        });
                    }
                });
        }

        function saveEmployee() {
            const id = document.getElementById('employee-id').value;
            const method = id ? 'PUT' : 'POST';
            const url = id ? `/api/employees/${id}` : '/api/employees';

            const employee = {
                firstname: document.getElementById('firstname').value,
                lastname: document.getElementById('lastname').value,
                department: document.getElementById('department').value,
                date_of_birth: document.getElementById('date_of_birth').value
            };

            fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        ...getAuthHeaders()
                    },
                    body: JSON.stringify(employee)
                })
                .then(response => {
                    if (response.ok) {
                        clearEmployeeForm();
                        loadEmployees();
                    }
                });
        }

        function editEmployee(id) {
            fetch(`/api/employees/${id}`, {
                    headers: getAuthHeaders()
                })
                .then(response => response.json())
                .then(employee => {
                    document.getElementById('employee-id').value = employee.id;
                    document.getElementById('firstname').value = employee.firstname;
                    document.getElementById('lastname').value = employee.lastname;
                    document.getElementById('department').value = employee.department;
                    document.getElementById('date_of_birth').value = employee.date_of_birth;
                    document.getElementById('employee-btn').innerText = 'Update Employee';
                });
        }

        function deleteEmployee(id) {
            if (!confirm('Are you sure you want to delete this employee?')) return;

            fetch(`/api/employees/${id}`, {
                    method: 'DELETE',
                    headers: getAuthHeaders()
                })
                .then(() => {
                    loadEmployees();
                });
        }

        function clearEmployeeForm() {
            document.getElementById('employee-form').reset();
            document.getElementById('employee-id').value = '';
            document.getElementById('employee-btn').innerText = 'Add Employee';
        }

        function filterEmployees() {
            loadEmployees();
        }

        function loadLeaves() {
            const startDate = document.getElementById('start-date-filter').value;
            const endDate = document.getElementById('end-date-filter').value;
            const approved = document.getElementById('approved-filter').value;

            let url = '/api/leaves?';
            const params = [];
            if (startDate) params.push(`start_date=${startDate}`);
            if (endDate) params.push(`end_date=${endDate}`);
            if (approved) params.push(`approved=${approved}`);

            url += params.join('&');

            fetch(url, {
                    headers: getAuthHeaders()
                })
                .then(response => {
                    if (response.status === 204) {
                        document.querySelector('#leaves-table tbody').innerHTML =
                            '<tr><td colspan="7">No leaves found</td></tr>';
                        return;
                    }
                    return response.json();
                })
                .then(data => {
                    if (data) {
                        const tbody = document.querySelector('#leaves-table tbody');
                        tbody.innerHTML = '';
                        data.forEach(leave => {
                            const row = tbody.insertRow();
                            row.innerHTML = `
                            <td>${leave.id}</td>
                            <td>${leave.employee_id}</td>
                            <td>${leave.description}</td>
                            <td>${leave.start_date}</td>
                            <td>${leave.end_date}</td>
                            <td>${leave.approved ? 'Yes' : 'No'}</td>
                            <td>
                                <button onclick="editLeave(${leave.id})">Edit</button>
                                <button onclick="deleteLeave(${leave.id})">Delete</button>
                            </td>
                        `;
                        });
                    }
                });
        }

        function saveLeave() {
            const id = document.getElementById('leave-id').value;
            const employeeId = document.getElementById('employee_id').value;
            const method = id ? 'PUT' : 'POST';
            const url = id ? `/api/leaves/${id}` : `/api/leaves/employees/${employeeId}`;

            const leave = {
                employee_id: employeeId,
                description: document.getElementById('description').value,
                start_date: document.getElementById('start_date').value,
                end_date: document.getElementById('end_date').value,
                approved: document.getElementById('approved').value === 'true'
            };

            fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        ...getAuthHeaders()
                    },
                    body: JSON.stringify(leave)
                })
                .then(response => {
                    if (response.ok) {
                        clearLeaveForm();
                        loadLeaves();
                    }
                });
        }

        function editLeave(id) {
            fetch(`/api/leaves/${id}`, {
                    headers: getAuthHeaders()
                })
                .then(response => response.json())
                .then(leave => {
                    document.getElementById('leave-id').value = leave.id;
                    document.getElementById('employee_id').value = leave.employee_id;
                    document.getElementById('description').value = leave.description;
                    document.getElementById('start_date').value = leave.start_date;
                    document.getElementById('end_date').value = leave.end_date;
                    document.getElementById('approved').value = leave.approved ? 'true' : 'false';
                    document.getElementById('leave-btn').innerText = 'Update Leave';
                });
        }

        function deleteLeave(id) {
            if (!confirm('Are you sure you want to delete this leave?')) return;

            fetch(`/api/leaves/${id}`, {
                    method: 'DELETE',
                    headers: getAuthHeaders()
                })
                .then(() => {
                    loadLeaves();
                });
        }

        function clearLeaveForm() {
            document.getElementById('leave-form').reset();
            document.getElementById('leave-id').value = '';
            document.getElementById('leave-btn').innerText = 'Add Leave';
        }

        function filterLeaves() {
            loadLeaves();
        }

        function getAuthHeaders() {
            if (!currentUser) return {};
            return {
                'Authorization': 'Basic ' + btoa(currentUser.username + ':' + 'password')
            };
        }

        // Initialize
        showTab('login');
    </script>
</body>

</html>