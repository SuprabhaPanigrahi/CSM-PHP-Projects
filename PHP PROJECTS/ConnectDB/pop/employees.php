<?php

include 'connection_procedure.php';

$sql = "SELECT * FROM employees";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) { 
        echo "ID: " . $row['employee_id'] . " - Name: " . $row["first_name"] .
             " - Position: " . $row["position"] . "<br>";
    }
} else {
    echo "0 results"; 
}

mysqli_close($conn);

?>
