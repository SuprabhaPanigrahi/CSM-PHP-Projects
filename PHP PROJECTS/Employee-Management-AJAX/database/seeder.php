<?php

include 'connection.php';
mysqli_select_db($conn, 'employee_db');

$deptTable = "CREATE TABLE IF NOT EXISTS departments (
    dept_id int(6) unsigned primary key,
    name varchar(50) not null,
    created_at timestamp default current_timestamp,
    updated_at timestamp default current_timestamp on update current_timestamp
)";

if (!mysqli_query($conn, $deptTable)) {
    die("Error creating departments table: " . mysqli_error($conn));
}


$departments = [
    ['dept_id' => 1, 'name' => 'IT'],
    ['dept_id' => 2, 'name' => 'HR'],
    ['dept_id' => 3, 'name' => 'Testing'],
];

foreach ($departments as $department) {
    $dept_id = mysqli_real_escape_string($conn, $department['dept_id']);
    $name = mysqli_real_escape_string($conn, $department['name']);

    $insert_sql = "INSERT INTO departments (dept_id, name) VALUES ($dept_id, '$name')";

    if (mysqli_query($conn, $insert_sql)) {
        if (mysqli_affected_rows($conn) > 0) {
            echo "Inserted department: " . $name . "\n";
        } else {
            echo "Department already exists: " . $name . "\n";
        }
    } else {
        echo "Error inserting department " . $name . ": " . mysqli_error($conn) . "\n";
    }
}


$employeesTable = "CREATE TABLE IF NOT EXISTS employees (
    emp_id int(6) unsigned primary key,
    name varchar(50) not null,
    hire_date date,
    salary float,
    employment_type varchar(50) check (employment_type in ('contractual', 'permanent')),
    dept_id int(6) unsigned,
    created_at timestamp default current_timestamp,
    updated_at timestamp default current_timestamp on update current_timestamp,
    created_by int(11),
    updated_by int(11),
    deleted_at timestamp null default null,
    deleted_by int(11),
    is_deleted tinyint(1) default 0,
    foreign key (dept_id) references departments(dept_id)
)";

if (!mysqli_query($conn, $employeesTable)) {
    die("Error creating employees table: " . mysqli_error($conn));
}

// Insert employees with complete data
$employees = [
    ['emp_id' => 1, 'name' => 'John Doe', 'hire_date' => '2023-01-15', 'salary' => 50000.00, 'employment_type' => 'permanent', 'dept_id' => 1, 'is_deleted' => 0],
    ['emp_id' => 2, 'name' => 'Jane Smith', 'hire_date' => '2023-03-20', 'salary' => 45000.00, 'employment_type' => 'contractual', 'dept_id' => 2,'is_deleted' => 0],
    ['emp_id' => 3, 'name' => 'Mike Johnson', 'hire_date' => '2023-06-10', 'salary' => 55000.00, 'employment_type' => 'permanent', 'dept_id' => 1, 'is_deleted' => 0],
    ['emp_id' => 4, 'name' => 'Sarah Wilson', 'hire_date' => '2023-02-28', 'salary' => 48000.00, 'employment_type' => 'permanent', 'dept_id' => 3, 'is_deleted' => 0],
    ['emp_id' => 5, 'name' => 'David Brown', 'hire_date' => '2023-04-15', 'salary' => 52000.00, 'employment_type' => 'contractual', 'dept_id' => 2, 'is_deleted' => 0],
];

foreach ($employees as $employee) {
    $emp_id = mysqli_real_escape_string($conn, $employee['emp_id']);
    $name = mysqli_real_escape_string($conn, $employee['name']);
    $hire_date = mysqli_real_escape_string($conn, $employee['hire_date']);
    $salary = mysqli_real_escape_string($conn, $employee['salary']);
    $employment_type = mysqli_real_escape_string($conn, $employee['employment_type']);
    $dept_id = mysqli_real_escape_string($conn, $employee['dept_id']);
    $is_deleted = mysqli_real_escape_string($conn, $employee['is_deleted']);

    $insert_sql = "INSERT INTO employees (emp_id, name, hire_date, salary, employment_type, dept_id, is_deleted) 
                   VALUES ($emp_id, '$name', '$hire_date', $salary, '$employment_type', $dept_id, $is_deleted)";

    if (mysqli_query($conn, $insert_sql)) {
        if (mysqli_affected_rows($conn) > 0) {
            echo "Inserted employee: " . $name . "\n";
        } else {
            echo "Employee already exists: " . $name . "\n";
        }
    } else {
        echo "Error inserting employee " . $name . ": " . mysqli_error($conn) . "\n";
    }
}

mysqli_close($conn);
?>