<?php

require_once '../config/db-config.php';

$conn = mysqli_connect(HOSTNAME, USER, PASSWORD, DBNAME);

if ($conn === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
// Print host information
// echo "Connect Successfully. Host info: " . mysqli_get_host_info($conn);