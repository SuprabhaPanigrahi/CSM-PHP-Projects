<?php
include_once __DIR__ . '/../database/connection.php';
mysqli_select_db($conn, 'orderdb');

$sql = 'SELECT * FROM departments';
$result = mysqli_query($conn, $sql);

$departments = [];

if(mysqli_num_rows($result) > 0){
    while($row = mysqli_fetch_assoc($result)){
        array_push($departments, $row);
        // $departments[] = $row;
    }
}

echo json_encode($departments);
mysqli_close($conn);
?>