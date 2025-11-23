<?php
include_once "database.php";
mysqli_select_db($conn, "subscription_db");
 


$subscriber_id   = $_POST['subscriber_id'] ?? 0;
$subscription_id = $_POST['subscription_id'] ?? 0;
$plan_id         = $_POST['plan_id'] ?? 0;
$start_date      = $_POST['start_date'] ?? '';
$end_date        = $_POST['end_date'] ?? '';

if (empty($subscriber_id) || empty($subscription_id) || empty($plan_id) || empty($start_date) || empty($end_date)) {
    echo "ERROR: Missing transaction data";
    exit;
}



// fetch plan price
$res = mysqli_query($conn, "SELECT price FROM plans WHERE plan_id='$plan_id'");
if (!$res || mysqli_num_rows($res) == 0) {
    echo "ERROR: Invalid plan ID";
    exit;
}
$row = mysqli_fetch_assoc($res);
$price = $row['price'];

// calculate total days
$days = (strtotime($end_date) - strtotime($start_date)) / 86400;
if ($days <= 0) $days = 1;

// final amount
$amount = $price * $days;
$payment_date = date("Y-m-d");

// insert transaction
 
$insert = "INSERT INTO transactions (subscriber_id, subscription_id, amount, payment_date)
           VALUES ('$subscriber_id', '$subscription_id', '$amount', '$payment_date')";
 


if (mysqli_query($conn, $insert)) {
    echo "SUCCESS";
} else {
    echo "ERROR: " . mysqli_error($conn);
}
?>
