<?php

include_once __DIR__ . '/../database/connection.php';

$sql = "SELECT e.emp_id, e.name, e.hire_date, e.salary, e.employment_type, d.name AS department_name FROM employees e LEFT JOIN departments d ON e.dept_id = d.dept_id"; 

$result = mysqli_query($conn, $sql);
$employees = [];

if(mysqli_num_rows($result) > 0){
    while($row = mysqli_fetch_assoc($result)){
        array_push($employees, $row);
        // $departments[] = $row;
    }
}

echo json_encode($employees);
mysqli_close($conn);