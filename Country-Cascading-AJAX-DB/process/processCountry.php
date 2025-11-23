<?php
require_once '../database/connection.php';

// Set header first
header('Content-Type: application/json');

// Check connection
if (!$conn) {
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}

// Select database
if (!mysqli_select_db($conn, 'customer_db')) {
    echo json_encode(["error" => "Database selection failed: " . mysqli_error($conn)]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Fetch countries
    $sql = "SELECT id, name, code FROM countries WHERE is_deleted = 0";
    $result = mysqli_query($conn, $sql);

    $rows = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        echo json_encode($rows);
    } else {
        echo json_encode(["error" => "Query failed: " . mysqli_error($conn)]);
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Fetch states for a specific country
    if (isset($_POST['country_id']) && !empty($_POST['country_id'])) {
        $countryId = mysqli_real_escape_string($conn, $_POST['country_id']);
        
        $sql = "SELECT id, name FROM states WHERE country_id = $countryId AND is_deleted = 0";
        $result = mysqli_query($conn, $sql);

        $rows = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $rows[] = $row;
            }
            echo json_encode($rows);
        } else {
            echo json_encode(["error" => "Query failed: " . mysqli_error($conn)]);
        }
    } else {
        echo json_encode(["error" => "Country ID is required"]);
    }
} else {
    echo json_encode(["error" => "Invalid request method"]);
}
?>