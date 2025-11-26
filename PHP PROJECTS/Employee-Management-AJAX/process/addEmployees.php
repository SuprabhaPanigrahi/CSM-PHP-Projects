<?php
include '../database/connection.php';

if ($_POST) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $hire_date = mysqli_real_escape_string($conn, $_POST['hire_date']);
    $salary = mysqli_real_escape_string($conn, $_POST['salary']);
    $dept_id = mysqli_real_escape_string($conn, $_POST['dept_id']);
    $employment_type = mysqli_real_escape_string($conn, $_POST['employment_type']);

    // Get next emp_id
    $max_id_query = "SELECT MAX(emp_id) as max_id FROM employees";
    $max_result = mysqli_query($conn, $max_id_query);
    $max_row = mysqli_fetch_assoc($max_result);
    $next_emp_id = $max_row['max_id'] + 1;

    $sql = "INSERT INTO employees (emp_id, name, hire_date, salary, dept_id, employment_type) 
            VALUES ($next_emp_id, '$name', '$hire_date', $salary, $dept_id, '$employment_type')";

    if (mysqli_query($conn, $sql)) {
        echo "success";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>