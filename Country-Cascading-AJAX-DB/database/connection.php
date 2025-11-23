<?php
require_once '../config/dbconfig.php';

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_DATABASE);

if($conn == false){
    // Don't echo - just let the connection be false
    // This will be handled by the calling files
    $conn = false;
}
// Remove ALL echo statements - they corrupt JSON output
?>