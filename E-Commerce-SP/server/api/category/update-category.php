<?php
header('Content-Type: application/json');

include_once __DIR__ . '/../../inc/bootstrap.php';

// Get form data
$id = $_POST['id'] ?? 0;
$name = $_POST['name'] ?? '';
$description = $_POST['description'] ?? '';
$status = $_POST['status'] ?? 1;
$current_image = $_POST['current_image'] ?? 'default.jpg';

// Handle file upload
$image = $current_image;
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

$sql = "CALL USP_CATEGORY($id, '$name', 'UPDATE')";
$result = mysqli_query($conn, $sql);

if($result) {
    echo json_encode(['success' => true, 'message' => 'Category updated successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error: ' . mysqli_error($conn)]);
}

mysqli_close($conn);
?>