<?php
include_once '../database/connection.php';
mysqli_select_db($conn, 'product_inventory');

$selected_category_id = isset($_POST['category']) ? intval($_POST['category']) : 0;
$selected_product_id = isset($_POST['product_name']) ? intval($_POST['product_name']) : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['final_submit'])) {
    $price = floatval($_POST['product_price']);
    $quantity = intval($_POST['product_qty']);

    // Validate all fields
    if ($selected_category_id > 0 && $selected_product_id > 0 && $price > 0 && $quantity > 0) {
        
        // Use correct column names (assuming your table has: productID, categoryID, price, quantity)
        $sql = "INSERT INTO product_inventory_table (categoryID, productID, price, quantity, created_at)
                VALUES ('$selected_category_id', '$selected_product_id', '$price', '$quantity', NOW())";

        if (mysqli_query($conn, $sql)) {
            // Redirect first, then exit
            header("Location: ../pages/view-product.php?message=success");
            exit();
        } else {
            header("Location: ../pages/view-product.php?message=error&error=" . urlencode(mysqli_error($conn)));
            exit();
        }
    } else {
        header("Location: ../pages/view-product.php?message=invalid");
        exit();
    }
} else {
    header("Location: ../pages/dashboard.php");
    exit();
}
?>