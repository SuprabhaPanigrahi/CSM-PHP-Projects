<?php

require_once 'connection.php';
mysqli_select_db($conn, 'product_inventory');

$categoryTable = 'CREATE TABLE IF NOT EXISTS categories (
    categoryID int(6) unsigned auto_increment primary key,
    categoryName varchar(30) not null,
    created_at timestamp default current_timestamp,
    updated_at timestamp default current_timestamp on update current_timestamp,
    created_by int(11),
    updated_by int(11),
    deleted_by int(11),
    deleted_at timestamp null default null,
    is_deleted tinyint(1) default 0
    )';

if (mysqli_query($conn, $categoryTable)) {
    echo "\n Table category added successfully";
} else {
    die("Error creating table: " . mysqli_error($conn));
}

$productTable = "CREATE TABLE IF NOT EXISTS products (
    productID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    categoryID int(6) unsigned,
    productName VARCHAR(50) NOT NULL,
    price FLOAT NOT NULL,
    quantity INT NOT NULL,
    isActive BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT(11),
    updated_by INT(11),
    deleted_at TIMESTAMP NULL DEFAULT NULL,
    deleted_by INT(11),
    is_deleted TINYINT(1) DEFAULT 0,
    FOREIGN KEY (categoryID) REFERENCES categories(categoryID)
)";

if (mysqli_query($conn, $productTable)) {
    echo "\nTable products added successfully";
} else {
    die("Error creating table: " . mysqli_error($conn));
}

$productInventoryTable = "CREATE TABLE IF NOT EXISTS product_inventory_table (
    slno INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    productName VARCHAR(50) NOT NULL,
    productCategory VARCHAR(30) NOT NULL,
    price FLOAT NOT NULL,
    quantity INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT(11),
    updated_by INT(11),
    deleted_at TIMESTAMP NULL DEFAULT NULL,
    deleted_by INT(11),
    is_deleted TINYINT(1) DEFAULT 0
)";


if (mysqli_query($conn, $productInventoryTable)) {
    echo "\nTable product_inventory added successfully";
} else {
    die("Error creating table: " . mysqli_error($conn));
}

mysqli_close($conn);
