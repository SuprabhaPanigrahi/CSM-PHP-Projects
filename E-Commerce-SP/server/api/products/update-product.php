<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

include_once __DIR__ . '/../../inc/bootstrap.php';

// Get form data
$id = $_POST['id'] ?? 0;
$name = $_POST['productName'] ?? '';
$price = $_POST['price'] ?? 0;
$categoryId = $_POST['category'] ?? 0;
$productCode = $_POST['productCode'] ?? 0;
$purchasePrice = $_POST['purchasePrice'] ?? 0;
$discount = $_POST['discount'] ?? 0;
$sellingPrice = $_POST['sellingPrice'] ?? 0;
$description = $_POST['description'] ?? '';
$current_image = $_POST['current_image'] ?? 'default.jpg';

// Handle file upload with SHORT filename
$image = $current_image; // Keep current image by default
if(isset($_FILES['productImage']) && $_FILES['productImage']['error'] === 0) {
    $uploadDir = __DIR__ . '/../../storage/products/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    
    // Generate SHORT filename - just timestamp + extension
    $fileExtension = pathinfo($_FILES['productImage']['name'], PATHINFO_EXTENSION);
    $image = time() . '.' . $fileExtension;
    
    move_uploaded_file($_FILES['productImage']['tmp_name'], $uploadDir . $image);
}

// Escape strings
$name = mysqli_real_escape_string($conn, $name);
$description = mysqli_real_escape_string($conn, $description);

$sql = "CALL USP_PRODUCTS($id, '$name', $price, $categoryId, $productCode, $purchasePrice, $discount, $sellingPrice, '$description', '$image', 'UPDATE')";

// Debug the SQL
error_log("Executing UPDATE SQL: " . $sql);

$result = mysqli_query($conn, $sql);

if($result) {
    echo json_encode(['success' => true, 'message' => 'Product updated successfully']);
} else {
    $error = mysqli_error($conn);
    error_log("MySQL Error: " . $error);
    echo json_encode(['success' => false, 'message' => 'Error: ' . $error]);
}

mysqli_close($conn);
?>