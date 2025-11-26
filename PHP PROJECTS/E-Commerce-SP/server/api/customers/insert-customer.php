<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

include_once __DIR__ . '/../../inc/bootstrap.php';

// Get form data
$name = $_POST['productName'] ?? '';
$price = $_POST['price'] ?? 0;
$categoryId = $_POST['category'] ?? 0;
$productCode = $_POST['productCode'] ?? 0;
$purchasePrice = $_POST['purchasePrice'] ?? 0;
$discount = $_POST['discount'] ?? 0;
$sellingPrice = $_POST['sellingPrice'] ?? 0;
$description = $_POST['description'] ?? '';

// Handle file upload with shorter filename
$image = 'default.jpg';
if(isset($_FILES['productImage']) && $_FILES['productImage']['error'] === 0) {
    $uploadDir = __DIR__ . '/../../storage/products/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    
    // Generate shorter filename (max 15 chars + extension)
    $fileExtension = pathinfo($_FILES['productImage']['name'], PATHINFO_EXTENSION);
    $timestamp = time();
    $shortName = substr($_FILES['productImage']['name'], 0, 8); // Take first 8 chars of original name
    $image = 'p' . $timestamp . '_' . $shortName . '.' . $fileExtension;
    
    // Ensure filename is not too long (max 30 chars)
    if(strlen($image) > 30) {
        $image = 'p' . $timestamp . '.' . $fileExtension;
    }
    
    move_uploaded_file($_FILES['productImage']['tmp_name'], $uploadDir . $image);
}

// Escape strings
$name = mysqli_real_escape_string($conn, $name);
$description = mysqli_real_escape_string($conn, $description);

$sql = "CALL USP_PRODUCTS(NULL, '$name', $price, $categoryId, $productCode, $purchasePrice, $discount, $sellingPrice, '$description', '$image', 'INSERT')";
$result = mysqli_query($conn, $sql);

if($result) {
    echo json_encode(['success' => true, 'message' => 'Product added successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error: ' . mysqli_error($conn)]);
}

mysqli_close($conn);
?>