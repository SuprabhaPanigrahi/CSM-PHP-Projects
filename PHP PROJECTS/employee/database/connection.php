<?php
include_once  __DIR__ .'/../config/dbconfig.php';
 
// establishing connection

$conn = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
if(!$conn){
    die("Not connected".mysqli_connect_error());
}
