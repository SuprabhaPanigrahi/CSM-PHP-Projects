<?php
include_once '../database/connection.php';
mysqli_select_db($conn, 'product_inventory');

$selected_category_id = $_POST['category'] ?? 0;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['final_submit'])) {
        // Redirect to process page with all data
        header("Location: ../process/process-product.php?" . http_build_query($_POST));
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Inventory Page</title>
    <link rel="stylesheet" href="../bootstrap.min.css">
</head>

<body class="bg-light">
    <div class="container mt-5 p-4 bg-white shadow-sm rounded" style="max-width: 400px;">
        <h2 class="text-center mb-4">Product Inventory</h2>

        <form method="post">
            <div class="mb-3">
                <label for="category" class="form-label">Category Name:</label>
                <select name="category" id="category" class="form-control" required onchange="this.form.submit()">
                    <option value="">~~Select~~</option>
                    <?php
                    $sql = "SELECT categoryID, categoryName FROM categories WHERE is_deleted = 0";
                    $result = mysqli_query($conn, $sql);
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $selected = ($selected_category_id == $row['categoryID']) ? 'selected' : '';
                            echo '<option value="' . $row['categoryID'] . '" ' . $selected . '>' . $row['categoryName'] . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="product_name" class="form-label">Product Name:</label>
                <select name="product_name" id="product_name" class="form-control" required>
                    <option value="">~~Select~~</option>
                    <?php
                    if ($selected_category_id > 0) {
                        $sql = "SELECT productID, productName FROM products WHERE categoryID = $selected_category_id AND is_deleted = 0";
                        $result = mysqli_query($conn, $sql);
                        if ($result && mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo '<option value="' . $row['productID'] . '">' . $row['productName'] . '</option>';
                            }
                        } else {
                            echo '<option value="">No products found</option>';
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="product_price" class="form-label">Product Price:</label>
                <input type="text" id="product_price" name="product_price" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="product_qty" class="form-label">Product Quantity:</label>
                <input type="number" id="product_qty" name="product_qty" class="form-control" required>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-info" name="final_submit">Save</button>
            </div>
        </form>
    </div>
</body>
</html>