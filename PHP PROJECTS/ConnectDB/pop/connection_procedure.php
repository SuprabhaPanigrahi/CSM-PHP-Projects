<?php
/* Attempt MySQL server connection. Assuming you are running MySQL
server with default setting */
// $conn = mysqli_connect("localhost", "root", "csmpl@123");
 

include '../includes/constants.php';

$conn = mysqli_connect(HOSTNAME,USERNAME,PASSWORD,DATABASE_NAME);
// Check connection
if($conn === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
// Print host information
echo "Connect Successfully. Host info: " . mysqli_get_host_info($conn);
?>