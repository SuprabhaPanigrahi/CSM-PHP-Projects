<?php

include_once 'connection.php';

$sql = 'CREATE DATABASE IF NOT EXISTS customer_db';

if (mysqli_query($conn, $sql)) {
    echo "\n Database created successfully";
    mysqli_select_db($conn, 'customer_db');

    $tableSql = 'CREATE TABLE IF NOT EXISTS customers(
    id int(6) unsigned auto_increment primary key,
    name varchar(30) not null,
    email varchar(50),
    phone varchar(15),
    address varchar(100),
    countryID int(11),
    stateID int(11),
    cityID int(11),
    image varchar(100),
    created_at timestamp default current_timestamp,
    updated_at timestamp default current_timestamp on update current_timestamp,
    created_by int(11),
    updated_by int(11),
    deleted_by int(11),
    deleted_at timestamp null default null,
    is_deleted tinyint(1) default 0
    )';

    if (mysqli_query($conn, $tableSql)) {
        echo "\n Table customers added successfully";
    } else {
        die("Error creating table: " . mysqli_error($conn));
    }
}

$countriesTableSql = 'CREATE TABLE IF NOT EXISTS countries (
id int(6) unsigned auto_increment primary key,
name varchar(50) not null,
code varchar(10) not null,
created_at timestamp default current_timestamp,
updated_at timestamp default current_timestamp on update current_timestamp,
created_by int(11),
updated_by int(11),
deleted_at timestamp null default null,
deleted_by int(11),
is_deleted tinyint(1) default 0
)';

if (mysqli_query($conn, $countriesTableSql)) {
    echo "\nTable contries added successfully";
} else {
    die("Error creating table: " . mysqli_error($conn));
}

$statesTableSql = 'CREATE TABLE IF NOT EXISTS states (
id int(6) unsigned auto_increment primary key,
countryID int(11) not null,
name varchar(50) not null,
created_at timestamp default current_timestamp,
updated_at timestamp default current_timestamp on update current_timestamp,
created_by int(11),
updated_by int(11),
deleted_at timestamp null default null,
deleted_by int(11),
is_deleted tinyint(1) default 0
)';

if (mysqli_query($conn, $statesTableSql)) {
    echo "\nTable states added successfully";
} else {
    die("Error creating table: " . mysqli_error($conn));
}

$citiesTableSql = 'CREATE TABLE IF NOT EXISTS cities (
id int(6) unsigned auto_increment primary key,
stateID int(11) not null,
name varchar(50) not null,
created_at timestamp default current_timestamp,
updated_at timestamp default current_timestamp on update current_timestamp,
created_by int(11),
updated_by int(11),
deleted_at timestamp null default null,
deleted_by int(11),
is_deleted tinyint(1) default 0
)';

if (mysqli_query($conn, $citiesTableSql)) {
    echo "\nTable cities added successfully";
} else {
    die("Error creating table: " . mysqli_error($conn));
}

mysqli_close($conn);