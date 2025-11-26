<?php
#step1: collect all submitted data.
//print_r($_POST);
//exit();
session_start();
require_once '../models/user.php';

$fullName= $_POST['fullname'];
$email= $_POST['email'];
$password= $_POST['password'];
$qualification= $_POST['qualification'];
$phone= $_POST['phone'];
$dob= $_POST['dob'];
$gender= $_POST['gender'];      
$address= $_POST['address'];
$country= $_POST['country'];
$zip= $_POST['zip'];

#step2: create object of user class 
// Handle qualification array - convert to string if it's an array
if(is_array($qualification)) {
    $qualification = implode(', ', $qualification);
}

$userOne = new User($fullName, $email, $password, $phone, $dob, $gender, $qualification, $address, $country, $zip);

if(!isset($_SESSION['users'])){
     $_SESSION['users']=[];
}  

array_push($_SESSION['users'], serialize($userOne)); 


header('Location: ../pages/view-users.php');







