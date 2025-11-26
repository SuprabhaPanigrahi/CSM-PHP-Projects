<?php
 

include_once __DIR__ . "/database.php";

mysqli_select_db($conn, "subscription_db");

$sql = "SELECT * FROM plans";
$plan = [];

$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $plan[] = $row;
    }
}

echo json_encode($plan);
exit;
?>
