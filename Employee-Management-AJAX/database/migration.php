<?php

include_once 'connection.php';

$sql = 'CREATE DATABASE IF NOT EXISTS employee_db';

if (mysqli_query($conn, $sql)) {
    echo "\n Database created successfully";
    mysqli_select_db($conn, 'employee_db');

    $departmentsTable = 'CREATE TABLE IF NOT EXISTS departments (
    dept_id int(6) unsigned primary key,
    name varchar(30) not null,
    created_at timestamp default current_timestamp,
    updated_at timestamp default current_timestamp on update current_timestamp,
    created_by int(11),
    updated_by int(11),
    deleted_by int(11),
    deleted_at timestamp null default null,
    is_deleted tinyint(1) default 0
    )';

    if (mysqli_query($conn, $departmentsTable)) {
        echo "\n Table departments added successfully";
    } else {
        die("Error creating table: " . mysqli_error($conn));
    }
}

$employeesTable = 'CREATE TABLE IF NOT EXISTS employees (
    emp_id int(6) unsigned primary key,
    name varchar(50) not null,
    hire_date date,
    salary float,
    employment_type varchar(50),
    dept_id int(6) unsigned,
    created_at timestamp default current_timestamp,
    updated_at timestamp default current_timestamp on update current_timestamp,
    created_by int(11),
    updated_by int(11),
    deleted_at timestamp null default null,
    deleted_by int(11),
    is_deleted tinyint(1) default 0,
    foreign key (dept_id) references departments(dept_id)
)';

if (mysqli_query($conn, $employeesTable)) {
    echo "\nTable employees added successfully";
} else {
    die("Error creating table: " . mysqli_error($conn));
}

mysqli_close($conn);