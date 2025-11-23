<?php
include_once "database.php";
mysqli_select_db($conn, "subscription_db");

$name = $_POST['subscription_name'];
$email = $_POST['subscriber_email'];
$phone = $_POST['subscriber_phone'];

$sql = "INSERT INTO subscribers (name,email, phone)
        VALUES ('$name', '$email', '$phone')";

if (mysqli_query($conn, $sql)) {
    echo mysqli_insert_id($conn); // return last subscriber_id
} else {
    echo "ERROR";
}
?>
