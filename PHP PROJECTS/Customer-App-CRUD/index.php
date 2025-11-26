<?php

include_once 'database/connection.php';
mysqli_select_db($conn, 'customer_db');

$selected_country_id = 0;
$selected_state_id = 0;
$selected_city_id = 0;

// Keep form data between submissions
$name = '';
$email = '';
$phone = '';
$address = '';

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Capture entered values so they persist after reload
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $address = $_POST['address'] ?? '';

    // Determine selected dropdowns
    $selected_country_id = $_POST['country'] ?? 0;
    $selected_state_id   = $_POST['state'] ?? 0;
    $selected_city_id    = $_POST['city'] ?? 0;

    // Check if it's final form submission
    $is_final_submit = isset($_POST['final_submit']) && $_POST['final_submit'] == '1';

    if ($is_final_submit) {
        if (!empty($name) && !empty($email) && !empty($phone) && !empty($address) && $selected_country_id && $selected_state_id && $selected_city_id) {
            
            $name = mysqli_real_escape_string($conn, $name);
            $email = mysqli_real_escape_string($conn, $email);
            $phone = mysqli_real_escape_string($conn, $phone);
            $address = mysqli_real_escape_string($conn, $address);

            $insertSql = "INSERT INTO customers (name, email, phone, address, countryId, stateId, cityId)
                          VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $insertSql);
            mysqli_stmt_bind_param($stmt, 'ssssiii', $name, $email, $phone, $address, $selected_country_id, $selected_state_id, $selected_city_id);

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);
                header("Location: view-all-customer.php"); // redirect after successful insert
                exit();
            } else {
                echo "<div class='alert alert-danger text-center'>Error inserting data: " . mysqli_error($conn) . "</div>";
            }

            mysqli_stmt_close($stmt);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
        }
        .card {
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            border: none;
        }
        h2 {
            font-weight: 700;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <div class="card p-4">
        <h2 class="text-center text-primary mb-4">Customer Registration Form</h2>

        <form method="POST" id="customerForm">
            <input type="hidden" name="final_submit" id="final_submit" value="0">

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="name" name="name" required
                        value="<?php echo htmlspecialchars($name); ?>">
                </div>
                <div class="col-md-6">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email" required
                        value="<?php echo htmlspecialchars($email); ?>">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="phone" class="form-label">Phone Number</label>
                    <input type="tel" class="form-control" id="phone" name="phone" required
                        value="<?php echo htmlspecialchars($phone); ?>">
                </div>
                <div class="col-md-6">
                    <label for="country" class="form-label">Country</label>
                    <select class="form-select" id="country" name="country" onchange="autoSubmit()">
                        <option value="">Select Country</option>
                        <?php
                        $result = mysqli_query($conn, "SELECT * FROM countries WHERE is_deleted = 0");
                        while ($row = mysqli_fetch_assoc($result)) {
                            $selected = ($selected_country_id == $row['id']) ? 'selected' : '';
                            echo "<option value='{$row['id']}' $selected>{$row['name']}</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="state" class="form-label">State</label>
                    <select class="form-select" id="state" name="state" onchange="autoSubmit()">
                        <option value="">Select State</option>
                        <?php
                        if ($selected_country_id > 0) {
                            $sql = "SELECT * FROM states WHERE countryId = $selected_country_id AND is_deleted = 0";
                            $result = mysqli_query($conn, $sql);
                            while ($row = mysqli_fetch_assoc($result)) {
                                $selected = ($selected_state_id == $row['id']) ? 'selected' : '';
                                echo "<option value='{$row['id']}' $selected>{$row['name']}</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="city" class="form-label">City</label>
                    <select class="form-select" id="city" name="city" required>
                        <option value="">Select City</option>
                        <?php
                        if ($selected_state_id > 0) {
                            $sql = "SELECT * FROM cities WHERE stateId = $selected_state_id AND is_deleted = 0";
                            $result = mysqli_query($conn, $sql);
                            while ($row = mysqli_fetch_assoc($result)) {
                                $selected = ($selected_city_id == $row['id']) ? 'selected' : '';
                                echo "<option value='{$row['id']}' $selected>{$row['name']}</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label for="address" class="form-label">Full Address</label>
                <textarea class="form-control" id="address" name="address" rows="3" required><?php echo htmlspecialchars($address); ?></textarea>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-success px-5" onclick="finalSubmit()">Save Customer</button>
            </div>
        </form>
    </div>
</div>

<script>
    function autoSubmit() {
        document.getElementById('final_submit').value = 0;
        document.getElementById('customerForm').submit();
    }

    function finalSubmit() {
        document.getElementById('final_submit').value = 1;
    }
</script>
</body>
</html>
