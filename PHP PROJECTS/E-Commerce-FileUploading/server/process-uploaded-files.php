<?php
require_once '../database/connection.php';

// Check if all required fields are set
if (!isset($_POST['name']) || empty($_POST['name'])) {
    echo "Name is required";
    exit();
}

if (!isset($_POST['price']) || empty($_POST['price'])) {
    echo "Price is required";
    exit();
}

if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
    echo "Image upload failed or no image selected";
    exit();
}

$name = trim($_POST['name']);
$price = trim($_POST['price']);
$image = $_FILES['image'];

$target_path = 'uploads';

// Create uploads directory if it doesn't exist
if (!is_dir($target_path)) {
    mkdir($target_path, 0777, true);
}

$extension = pathinfo($image['name'], PATHINFO_EXTENSION);
$filename = 'product_' . time() . '.' . $extension;
$final_path = $target_path . '/' . $filename;

$allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
if (!in_array(strtolower($extension), $allowed_extensions)) {
    echo "Invalid file type. Only JPG, JPEG, PNG, GIF, and WEBP are allowed.";
    exit();
}

if ($image['size'] > 5 * 1024 * 1024) {
    echo "File size too large. Maximum size is 5MB.";
    exit();
}

// Move uploaded file
if (move_uploaded_file($image['tmp_name'], $final_path)) {
    // Escape values to prevent SQL injection
    // $name = mysqli_real_escape_string($conn, $name);
    // $price = mysqli_real_escape_string($conn, $price);
    // $filename = mysqli_real_escape_string($conn, $filename);

    // Insert into database
    $sql = "INSERT INTO Products_Table (name, price, image) VALUES ('$name', '$price', '$filename')";

    if (mysqli_query($conn, $sql)) {
        echo "Product uploaded successfully";
    } else {
        echo "Database error: " . mysqli_error($conn);
        // Delete the uploaded file if database insert fails
        if (file_exists($final_path)) {
            unlink($final_path);
        }
    }
} else {
    echo "Failed to move uploaded file";
}

mysqli_close($conn);
exit();
