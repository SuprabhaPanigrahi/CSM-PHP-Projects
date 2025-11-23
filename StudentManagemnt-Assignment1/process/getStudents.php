<?php

require_once '../database/connection.php';
mysqli_select_db($conn, 'Student_Mgmt');

$sql = "SELECT id, full_name, email, created_at FROM students";
$result = mysqli_query($conn, $sql);

$students = [];
if(mysqli_num_rows($result) > 0){
    while($row = mysqli_fetch_assoc($result)){
        $students[] = $row;
    }
}

echo json_encode($students);
mysqli_close($conn);
?>