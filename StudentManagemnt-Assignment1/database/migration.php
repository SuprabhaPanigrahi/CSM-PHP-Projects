<?php

include_once 'connection.php';

$sql = 'CREATE DATABASE IF NOT EXISTS Student_Mgmt';

if (mysqli_query($conn, $sql)) {
    echo "\n Database created successfully";
    mysqli_select_db($conn, 'Student_Mgmt');

    $usersTable = 'CREATE TABLE IF NOT EXISTS users(
    id int(6) unsigned auto_increment primary key,
    email varchar(120) unique not null,
    username varchar(50) unique not null,
    password_hash varchar(255) not null,
    created_at timestamp default current_timestamp,
    updated_at timestamp default current_timestamp on update current_timestamp,
    created_by int(11),
    updated_by int(11),
    deleted_by int(11),
    deleted_at timestamp null default null,
    is_deleted tinyint(1) default 0
    )';

    if (mysqli_query($conn, $usersTable)) {
        echo "\n Table users added successfully";
    } else {
        die("Error creating table: " . mysqli_error($conn));
    }
}

$studentTable = 'CREATE TABLE IF NOT EXISTS students (
id int(6) unsigned auto_increment primary key,
full_name varchar(150) not null,
email varchar(150) not null,
phone varchar(20),
photo varchar(255),
is_active tinyint(1) default 1,
created_at timestamp default current_timestamp,
updated_at timestamp default current_timestamp on update current_timestamp,
created_by int(11),
updated_by int(11),
deleted_at timestamp null default null,
deleted_by int(11),
is_deleted tinyint(1) default 0
)';

if (mysqli_query($conn, $studentTable)) {
    echo "\nTable students added successfully";
} else {
    die("Error creating table: " . mysqli_error($conn));
}

mysqli_close($conn);