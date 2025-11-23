<?php
require_once '../database/connection.php';
mysqli_select_db($conn, 'Student_Mgmt');

// Get form data
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';

// Validate required fields
if (empty($name) || empty($email)) {
    echo "Name and email are required fields.";
    exit();
}

// Handle file upload
$photo = '';
if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = '../uploads/';
    
    // Create uploads directory if it doesn't exist
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    $fileName = uniqid() . '_' . basename($_FILES['photo']['name']);
    $photoPath = $uploadDir . $fileName;
    
    // Move uploaded file
    if (move_uploaded_file($_FILES['photo']['tmp_name'], $photoPath)) {
        $photo = $fileName;
    }
}

// Insert into database
$sql = "INSERT INTO students (full_name, email, phone, photo) VALUES ('$name', '$email', '$phone', '$photo')";

if (mysqli_query($conn, $sql)) {
    echo "success";
} else {
    echo "Error: " . mysqli_error($conn);
}

mysqli_close($conn);
?>