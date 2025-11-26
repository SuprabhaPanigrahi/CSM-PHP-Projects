<?php
include '../database/connection.php';

$sql = "SELECT e.emp_id, e.name, e.hire_date, e.salary, e.employment_type, 
               d.name as dept_name 
        FROM employees e 
        LEFT JOIN departments d ON e.dept_id = d.dept_id 
        WHERE e.is_deleted = 0 
        ORDER BY e.emp_id";

$result = mysqli_query($conn, $sql);
                                                            
$employees = [];
while ($row = mysqli_fetch_assoc($result)) {
    $employees[] = $row;
}

echo json_encode($employees);
mysqli_close($conn);
?>