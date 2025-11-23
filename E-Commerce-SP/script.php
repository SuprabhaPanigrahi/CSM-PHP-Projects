<?php 

$conn = mysqli_connect("localhost","root","csmpl@123","hr");
if(!$conn){
    die("connection failed :" . mysqli_connect_error());
}
$message = '';
$sql = "CALL USP_COUNTEMP(@message)";
if(mysqli_query($conn, $sql)){
    $select = "select @message as message";
    $result = mysqli_query($conn, $select);
    $row = mysqli_fetch_assoc($result);
    $message = $row['message'];
    echo "Total employees: " . $message;
}
else{
    echo  "Error: " . mysqli_error($conn);
}

?>