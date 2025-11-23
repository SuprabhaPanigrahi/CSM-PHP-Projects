<?php

define('DB_HOST', "localhost");
define('DB_USER', "root");
define('DB_PASS', 'csmpl@123');
define('DB_DATABASE', 'e_commerce');


$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_DATABASE);

if($conn == false){
    $conn = false;
}

?>
