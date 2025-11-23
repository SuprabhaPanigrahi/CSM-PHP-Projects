<?php
require_once '../config/dbconfig.php';

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_DATABASE);

if($conn == false){

    $conn = false;
}
?>