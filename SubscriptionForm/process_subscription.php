<?php
include_once "database.php";
mysqli_select_db($conn, "subscription_db");

$subscriber_id = $_POST['subscriber_id'] ?? 0;
$plan_id       = $_POST['plan_id'] ?? 0;
$start_date    = $_POST['start_date'] ?? '';
$end_date      = $_POST['end_date'] ?? '';
$status        = $_POST['status'] ?? '';

if (!$subscriber_id || !$plan_id || !$start_date || !$end_date || !$status) {
    echo "ERROR: Missing subscription data";
    exit;
}

// Make sure subscriber exists
$res = mysqli_query($conn, "SELECT * FROM subscribers WHERE subscriber_id='$subscriber_id'");
if (mysqli_num_rows($res) == 0) {
    echo "ERROR: Invalid subscriber ID";
    exit;
}

// Insert subscription
$sql = "INSERT INTO subscriptions (subscriber_id, plan_id, start_date, end_date, status)
        VALUES ('$subscriber_id', '$plan_id', '$start_date', '$end_date', '$status')";

if (mysqli_query($conn, $sql)) {
    $subscription_id = mysqli_insert_id($conn);
    echo $subscription_id > 0 ? $subscription_id : "ERROR: Insert failed";
} else {
    echo "ERROR: " . mysqli_error($conn); // <- this is crucial
}

?>
