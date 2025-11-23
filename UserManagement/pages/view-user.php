<?php
session_start();
require_once '../models/user.php';

// Check if users session exists and is not empty
if(isset($_SESSION['users']) && !empty($_SESSION['users'])) {
    $users = $_SESSION['users'];
    //print_r($users);
    foreach($users as $user){
        echo unserialize($user)->__toString() . "<hr>";
    }
} else {
    echo "<p>No users registered yet.</p>";
}
?>
<a href="registration.php">Register More</a>