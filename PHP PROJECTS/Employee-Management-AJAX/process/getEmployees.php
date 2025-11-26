<?php
require_once '../database/connection.php';

// Select database
mysqli_select_db($conn, 'employee_db');

// Enable error reporting for debugging (remove in production)
error_reporting(0); // Temporarily disable to avoid HTML output

$sql = "SELECT e.emp_id, e.name, e.hire_date, e.salary, e.employment_type, d.dept_id 
        FROM employees e 
        LEFT JOIN departments d ON e.dept_id = d.dept_id";

$result = mysqli_query($conn, $sql);

$employees = [];

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $employees[] = $row;
    }
}

echo json_encode($employees);

mysqli_close($conn);
?>