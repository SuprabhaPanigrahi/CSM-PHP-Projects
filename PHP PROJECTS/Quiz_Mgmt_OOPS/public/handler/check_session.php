<?php
session_start();

function checkAdminSession() {
    if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
        header('Location: ../admin/login.php');
        exit;
    }
    
    if ($_SESSION['user_role'] !== 'admin') {
        header('Location: ../admin/login.php');
        exit;
    }
}

function checkStudentSession() {
    if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
        header('Location: ../student/login.php');
        exit;
    }
    
    if ($_SESSION['user_role'] !== 'student') {
        header('Location: ../student/login.php');
        exit;
    }
}
?>