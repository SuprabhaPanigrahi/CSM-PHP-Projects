<?php
include_once 'database/connection.php';
mysqli_select_db($conn, 'customer_db');

// Handle delete action
if (isset($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];
    $delete_sql = "DELETE FROM customers WHERE id = $delete_id";
    if (mysqli_query($conn, $delete_sql)) {
        $message = "Customer deleted successfully!";
        $message_type = "success";
    } else {
        $message = "Error deleting customer: " . mysqli_error($conn);
        $message_type = "danger";
    }
    // Redirect to avoid resubmission
    header("Location: view-all-customer.php?message=" . urlencode($message) . "&type=" . $message_type);
    exit();
}

// Fetch all customers with location names
$sql = "SELECT 
            c.id, 
            c.name, 
            c.email, 
            c.phone, 
            c.address,
            c.countryId,
            c.stateId,
            c.cityId,
            co.name as country_name,
            s.name as state_name,
            ci.name as city_name,
            c.created_at
        FROM customers c
        LEFT JOIN countries co ON c.countryId = co.id
        LEFT JOIN states s ON c.stateId = s.id
        LEFT JOIN cities ci ON c.cityId = ci.id
        ORDER BY c.created_at DESC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Customers</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>All Customers</h1>
        <a href="index.php" class="btn btn-primary">Add New Customer</a>
    </div>

    <?php if (isset($_GET['message'])): ?>
        <div class="alert alert-<?php echo $_GET['type'] ?? 'info'; ?> alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($_GET['message']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Country</th>
                        <th>State</th>
                        <th>City</th>
                        <th>Date Added</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($customer = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo $customer['id']; ?></td>
                            <td><?php echo htmlspecialchars($customer['name']); ?></td>
                            <td><?php echo htmlspecialchars($customer['email']); ?></td>
                            <td><?php echo htmlspecialchars($customer['phone']); ?></td>
                            <td><?php echo htmlspecialchars($customer['address']); ?></td>
                            <td><?php echo htmlspecialchars($customer['country_name']); ?></td>
                            <td><?php echo htmlspecialchars($customer['state_name']); ?></td>
                            <td><?php echo htmlspecialchars($customer['city_name']); ?></td>
                            <td><?php echo date('M j, Y', strtotime($customer['created_at'])); ?></td>
                            <td>
                                <a href="edit-customer.php?id=<?php echo $customer['id']; ?>" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <button onclick="confirmDelete(<?php echo $customer['id']; ?>)" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-info text-center">
            <h4>No Customers Found</h4>
            <p>There are no customers in the database yet.</p>
            <a href="index.php" class="btn btn-primary">Add First Customer</a>
        </div>
    <?php endif; ?>
</div>

<script>
function confirmDelete(customerId) {
    if (confirm('Are you sure you want to delete this customer? This action cannot be undone.')) {
        window.location.href = 'view-all-customer.php?delete_id=' + customerId;
    }
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
mysqli_close($conn);
?>