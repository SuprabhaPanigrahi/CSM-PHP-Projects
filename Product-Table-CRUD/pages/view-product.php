<?php
include_once '../database/connection.php';
mysqli_select_db($conn, 'product_inventory');

// Handle messages
$message = '';
$message_type = '';

if (isset($_GET['message'])) {
    switch ($_GET['message']) {
        case 'success':
            $message = 'Product added to inventory successfully!';
            $message_type = 'success';
            break;
        case 'error':
            $message = 'Error: ' . ($_GET['error'] ?? 'Unknown error occurred');
            $message_type = 'danger';
            break;
        case 'invalid':
            $message = 'Please fill all fields with valid data!';
            $message_type = 'warning';
            break;
    }
}

// Fetch all inventory data
$sql = "SELECT 
            pi.inventoryID,
            pi.price,
            pi.quantity,
            pi.created_at,
            c.categoryName,
            p.productName
        FROM product_inventory pi
        LEFT JOIN categories c ON pi.categoryID = c.categoryID
        LEFT JOIN products p ON pi.productID = p.productID
        ORDER BY pi.created_at DESC";

$result = mysqli_query($conn, $sql);
$inventory_data = [];

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $inventory_data[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Product Inventory</title>
    <link rel="stylesheet" href="../bootstrap.min.css">
    <style>
        .container {
            max-width: 1200px;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Product Inventory</h1>
            <div>
                <a href="../dashboard.php" class="btn btn-primary">Add New Product</a>
                <a href="../index.php" class="btn btn-secondary">Go Home</a>
            </div>
        </div>

        <?php if ($message): ?>
            <div class="alert alert-<?php echo $message_type; ?> alert-dismissible fade show" role="alert">
                <?php echo $message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (count($inventory_data) > 0): ?>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Category</th>
                            <th>Product Name</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total Value</th>
                            <th>Date Added</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($inventory_data as $index => $item): ?>
                            <tr>
                                <td><?php echo $index + 1; ?></td>
                                <td><?php echo htmlspecialchars($item['categoryName']); ?></td>
                                <td><?php echo htmlspecialchars($item['productName']); ?></td>
                                <td>₹<?php echo number_format($item['price'], 2); ?></td>
                                <td><?php echo $item['quantity']; ?></td>
                                <td>₹<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                                <td><?php echo date('M j, Y g:i A', strtotime($item['created_at'])); ?></td>
                                <td>
                                    <button class="btn btn-warning btn-sm">Edit</button>
                                    <button class="btn btn-danger btn-sm" 
                                            onclick="deleteProduct(<?php echo $item['inventoryID']; ?>)">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-info text-center">
                <h4>No Products in Inventory</h4>
                <p>There are no products in the inventory yet.</p>
                <a href="../dashboard.php" class="btn btn-primary">Add First Product</a>
            </div>
        <?php endif; ?>
    </div>

    <script>
    function deleteProduct(inventoryId) {
        if (confirm('Are you sure you want to delete this product from inventory?')) {
            window.location.href = 'delete-product.php?id=' + inventoryId;
        }
    }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
mysqli_close($conn);
?>