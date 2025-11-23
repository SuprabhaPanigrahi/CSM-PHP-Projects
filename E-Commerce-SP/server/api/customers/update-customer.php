<?php
header('Content-Type: application/json');

include_once __DIR__ . '/../../inc/bootstrap.php';

// Get form data
$id = $_POST['id'] ?? 0;
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';
$dob = $_POST['dob'] ?? '';
$address = $_POST['address'] ?? '';
$current_image = $_POST['current_image'] ?? 'default.jpg';

// Handle file upload
$image = $current_image;
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = __DIR__ . '/../../storage/uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    
    $image = 'cust_' . time() . '_' . $_FILES['image']['name'];
    move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $image);
}

// Escape strings for security
$name = mysqli_real_escape_string($conn, $name);
$email = mysqli_real_escape_string($conn, $email);
$phone = mysqli_real_escape_string($conn, $phone);
$address = mysqli_real_escape_string($conn, $address);

// ✅ CORRECT PARAMETER ORDER according to your stored procedure:
// p_id, p_name, p_email, p_dob, p_phone, p_address, p_image, p_isactive, p_action
$sql = "CALL USP_CUSTOMERS($id, '$name', '$email', '$dob', '$phone', '$address', '$image', 1, 'UPDATE')";

$result = mysqli_query($conn, $sql);

if ($result) {
    echo json_encode(['success' => true, 'message' => 'Customer updated successfully']);
} else {
    $error = mysqli_error($conn);
    echo json_encode(['success' => false, 'message' => 'Error: ' . $error]);
}

mysqli_close($conn);
?>