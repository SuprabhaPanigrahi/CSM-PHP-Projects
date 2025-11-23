<?php
header('Content-Type: application/json');

include_once __DIR__ . '/../../inc/bootstrap.php';

// Get form data
$name = $_POST['name'] ?? '';
$description = $_POST['description'] ?? '';
$status = $_POST['status'] ?? 1;

// Handle file upload
$image = 'default.jpg';
if(isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
    $uploadDir = __DIR__ . '/../../storage/categories/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    
    $image = 'cat_' . time() . '_' . $_FILES['image']['name'];
    move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $image);
}

// Escape strings
$name = mysqli_real_escape_string($conn, $name);
$description = mysqli_real_escape_string($conn, $description);

$sql = "CALL USP_CATEGORY(NULL, '$name', 'INSERT')";
$result = mysqli_query($conn, $sql);

if($result) {
    echo json_encode(['success' => true, 'message' => 'Category added successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error: ' . mysqli_error($conn)]);
}

mysqli_close($conn);
?>