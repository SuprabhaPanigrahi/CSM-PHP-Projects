<?php
include_once 'database/connection.php';
mysqli_select_db($conn, 'customer_db');

$customer_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch customer data
$customer = null;
if ($customer_id > 0) {
    $sql = "SELECT * FROM customers WHERE id = $customer_id";
    $result = mysqli_query($conn, $sql);
    $customer = mysqli_fetch_assoc($result);
}

// Handle form submission for update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_customer'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $country = (int)$_POST['country'];
    $state = (int)$_POST['state'];
    $city = (int)$_POST['city'];

    $update_sql = "UPDATE customers SET 
                    name = '$name',
                    email = '$email', 
                    phone = '$phone', 
                    address = '$address',
                    countryID = $country,
                    stateId = $state,
                    cityId = $city,
                    updated_at = NOW()
                   WHERE id = $customer_id";

    if (mysqli_query($conn, $update_sql)) {
        $message = "Customer updated successfully!";
        $message_type = "success";
        // Refresh customer data
        $result = mysqli_query($conn, "SELECT * FROM customers WHERE id = $customer_id");
        $customer = mysqli_fetch_assoc($result);
    } else {
        $message = "Error updating customer: " . mysqli_error($conn);
        $message_type = "danger";
    }
}

// Get countries for dropdown
$countries = mysqli_query($conn, "SELECT * FROM countries WHERE is_deleted = 0");
$states = [];
$cities = [];

if ($customer) {
    $states = mysqli_query($conn, "SELECT * FROM states WHERE countryID = {$customer['countryID']} AND is_deleted = 0");
    $cities = mysqli_query($conn, "SELECT * FROM cities WHERE stateId = {$customer['stateID']} AND is_deleted = 0");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Customer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Edit Customer</h1>
        <a href="view-all-customer.php" class="btn btn-secondary">Back to List</a>
    </div>

    <?php if (!$customer): ?>
        <div class="alert alert-danger">
            Customer not found!
        </div>
    <?php else: ?>
        <?php if (isset($message)): ?>
            <div class="alert alert-<?php echo $message_type; ?> alert-dismissible fade show" role="alert">
                <?php echo $message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <input type="hidden" name="update_customer" value="1">
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Name</label>
                    <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($customer['name']); ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($customer['email']); ?>" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Phone</label>
                    <input type="tel" class="form-control" name="phone" value="<?php echo htmlspecialchars($customer['phone']); ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Country</label>
                    <select class="form-select" name="country" id="country" required>
                        <option value="">Select Country</option>
                        <?php while ($row = mysqli_fetch_assoc($countries)): ?>
                            <option value="<?php echo $row['id']; ?>" 
                                <?php echo ($customer['countryID'] == $row['id']) ? 'selected' : ''; ?>>
                                <?php echo $row['name']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">State</label>
                    <select class="form-select" name="state" id="state" required>
                        <option value="">Select State</option>
                        <?php if ($states && mysqli_num_rows($states) > 0): ?>
                            <?php while ($row = mysqli_fetch_assoc($states)): ?>
                                <option value="<?php echo $row['id']; ?>" 
                                    <?php echo ($customer['stateId'] == $row['id']) ? 'selected' : ''; ?>>
                                    <?php echo $row['name']; ?>
                                </option>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">City</label>
                    <select class="form-select" name="city" id="city" required>
                        <option value="">Select City</option>
                        <?php if ($cities && mysqli_num_rows($cities) > 0): ?>
                            <?php while ($row = mysqli_fetch_assoc($cities)): ?>
                                <option value="<?php echo $row['id']; ?>" 
                                    <?php echo ($customer['cityId'] == $row['id']) ? 'selected' : ''; ?>>
                                    <?php echo $row['name']; ?>
                                </option>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Address</label>
                <textarea class="form-control" name="address" rows="3" required><?php echo htmlspecialchars($customer['address']); ?></textarea>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-success">Update Customer</button>
                <a href="view-all-customer.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    <?php endif; ?>
</div>

<script>
// JavaScript for cascading dropdowns (similar to your add form)
document.getElementById('country').addEventListener('change', function() {
    var countryID = this.value;
    var stateSelect = document.getElementById('state');
    var citySelect = document.getElementById('city');
    
    // Reset state and city
    stateSelect.innerHTML = '<option value="">Select State</option>';
    citySelect.innerHTML = '<option value="">Select City</option>';
    
    if (countryID) {
        // Fetch states for selected country
        fetch('get-states.php?country_id=' + countryID)
            .then(response => response.json())
            .then(data => {
                data.forEach(state => {
                    stateSelect.innerHTML += `<option value="${state.id}">${state.name}</option>`;
                });
            });
    }
});

document.getElementById('state').addEventListener('change', function() {
    var stateId = this.value;
    var citySelect = document.getElementById('city');
    
    // Reset city
    citySelect.innerHTML = '<option value="">Select City</option>';
    
    if (stateId) {
        // Fetch cities for selected state
        fetch('get-cities.php?state_id=' + stateId)
            .then(response => response.json())
            .then(data => {
                data.forEach(city => {
                    citySelect.innerHTML += `<option value="${city.id}">${city.name}</option>`;
                });
            });
    }
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
mysqli_close($conn);
?>