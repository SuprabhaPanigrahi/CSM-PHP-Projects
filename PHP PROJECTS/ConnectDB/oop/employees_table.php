<?php
// Include your constants (contains DB credentials)
require_once '../includes/constants.php';
require_once 'connection_oop.php';

/**
 * Database connection class
 */
class Database {
    private $host;
    private $user;
    private $pass;
    private $dbname;
    private $conn;

    public function __construct($host, $user, $pass, $dbname) {
        $this->host = $host;
        $this->user = $user;
        $this->pass = $pass;
        $this->dbname = $dbname;
        $this->connect();
    }

    private function connect() {
        $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->dbname);

        if ($this->conn->connect_error) {
            die("Database Connection failed: " . $this->conn->connect_error);
        }
    }

    public function getConnection() {
        return $this->conn;
    }

    public function __destruct() {
        if ($this->conn) {
            $this->conn->close();
        }
    }
}

/**
 * Employee class handles employee-related queries
 */
class Employee {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllEmployees() {
        $sql = "SELECT * FROM employees";
        $result = $this->conn->query($sql);

        if (!$result) {
            die("Query failed: " . $this->conn->error);
        }

        return $result;
    }

    public function getEmployeesWithManagersAndDepartments() {
        $sql = "
            SELECT 
                E.EMPLOYEE_ID,
                E.FIRST_NAME AS EmployeeFirstName,
                E.LAST_NAME AS EmployeeLastName,
                M.FIRST_NAME AS ManagerFirstName,
                M.LAST_NAME AS ManagerLastName,
                D.DEPARTMENT_NAME
            FROM EMPLOYEES AS E
            LEFT JOIN EMPLOYEES AS M ON E.MANAGER_ID = M.EMPLOYEE_ID
            LEFT JOIN DEPARTMENTS AS D ON D.DEPARTMENT_ID = E.DEPARTMENT_ID
            ORDER BY E.EMPLOYEE_ID
        ";

        $result = $this->conn->query($sql);

        if (!$result) {
            die("Join query failed: " . $this->conn->error);
        }

        return $result;
    }
}

// Instantiate classes
$database = new Database(HOSTNAME, USERNAME, PASSWORD, DATABASE_NAME);
$db = $database->getConnection();
$employeeObj = new Employee($db);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employees</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="text-center mb-4">Employees List</h2>

    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Hire Date</th>
                    <th>Job ID</th>
                    <th>Salary</th>
                    <th>Commission PCT</th>
                    <th>Manager ID</th>
                    <th>Department ID</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $employees = $employeeObj->getAllEmployees();

                if ($employees->num_rows > 0) {
                    while ($row = $employees->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['EMPLOYEE_ID']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['FIRST_NAME']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['LAST_NAME']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['EMAIL']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['PHONE_NUMBER']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['HIRE_DATE']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['JOB_ID']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['SALARY']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['COMMISSION_PCT']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['MANAGER_ID']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['DEPARTMENT_ID']) . "</td>";
                        echo "<td>
                                <div class='btn-group' role='group'>
                                    <button class='btn btn-sm btn-primary'>Edit</button>
                                    <button class='btn btn-sm btn-danger'>Delete</button>
                                    <a href='modal.php' target='_blank' class='btn btn-sm btn-info'>View</a>
                                </div>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='12' class='text-center text-muted'>No results found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <?php
    $joinResults = $employeeObj->getEmployeesWithManagersAndDepartments();

    if ($joinResults->num_rows > 0) {
        echo "<h3 class='mt-5 mb-3 text-center'>Employees with Managers and Departments</h3>";
        echo "<div class='table-responsive'>
                <table class='table table-bordered table-striped'>
                <thead class='table-dark'>
                    <tr>
                        <th>Employee</th>
                        <th>Manager</th>
                        <th>Department</th>
                    </tr>
                </thead>
                <tbody>";

        while ($row = $joinResults->fetch_assoc()) {
            echo "<tr>
                    <td>" . htmlspecialchars($row['EmployeeFirstName'] . ' ' . $row['EmployeeLastName']) . "</td>
                    <td>" . htmlspecialchars(($row['ManagerFirstName'] ?? '') . ' ' . ($row['ManagerLastName'] ?? '')) . "</td>
                    <td>" . htmlspecialchars($row['DEPARTMENT_NAME']) . "</td>
                  </tr>";
        }

        echo "</tbody></table></div>";
    } else {
        echo "<p class='text-muted text-center mt-4'>No join results found.</p>";
    }
    ?>
</div>
</body>
</html>
