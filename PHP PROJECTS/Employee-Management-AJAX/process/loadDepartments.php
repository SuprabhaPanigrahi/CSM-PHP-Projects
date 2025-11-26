<?php 

require_once '../database/connection.php';
mysqli_select_db($conn, 'employee_db');

$sql = "SELECT * FROM departments";
$result = mysqli_query($conn,$sql);

$departments=[];

if(mysqli_num_rows($result) > 0){
    while($row = mysqli_fetch_assoc($result)){
        $departments[] = $row;
    }
}

echo json_encode($departments);
mysqli_close($conn);
?>