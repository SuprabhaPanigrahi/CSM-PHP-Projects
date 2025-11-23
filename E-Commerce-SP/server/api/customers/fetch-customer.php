<?php
include_once __DIR__ . '/../../inc/bootstrap.php';

// mysqli_select_db($conn, 'e_commerce');

$sql = "CALL USP_CUSTOMERS(0,NULL,NULL, NULL, NULL, NULL, NULL, NULL, 'SELECT')";
$result = mysqli_query($conn, $sql);

$customers = [];

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $customers[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($customers);

mysqli_close($conn);
