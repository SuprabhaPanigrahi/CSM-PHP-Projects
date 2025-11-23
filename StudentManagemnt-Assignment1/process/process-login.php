<?php
require_once '../database/connection.php';
mysqli_select_db($conn, 'Student_Mgmt');

// Get POST data
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

// Validate credentials
if($username == 'situ' && $password == 'situ123'){
    echo "login successful";
}
else if($username == 'isha' && $password == 'isha123'){
    echo "login successful";
}
else if($username == 'ram' && $password == 'ram123'){
    echo "login successful";
}
else {
    echo "invalid credentials";
}

mysqli_close($conn);
?>