<?php
include_once __DIR__ . '/../database/connection.php';

$name = $_POST['name'] ?? '';
$hire_date = $_POST['hire_date'] ?? '';
$salary = $_POST['salary'] ?? '';
$etype = $_POST['etype'] ?? '';
$department = $_POST['department'] ?? '';

$sql = "INSERT INTO employees (name, hire_date, salary, employment_type, dept_id) 
VALUES('$name', '$hire_date', $salary, '$etype', $department)";

mysqli_query($conn, $sql);
echo "Data inserted Successfully";

mysqli_close($conn);
?>