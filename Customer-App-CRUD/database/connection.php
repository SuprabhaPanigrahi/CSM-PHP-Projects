<?php

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'csmpl@123');
define('DB_PORT', 3306);
// require_once '../config/db_config.php';


$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS);
if ($conn === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
// Print host information
echo "Connect Successfully. Host info: " . mysqli_get_host_info($conn);
