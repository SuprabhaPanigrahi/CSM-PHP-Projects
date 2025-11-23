<?php
session_start();
header('Content-Type: application/json');

require_once "../../core/database.php";

if (!$conn) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed']);
    exit;
}

// Check if it's a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    exit;
}

// Get and validate input
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$password = isset($_POST['password']) ? trim($_POST['password']) : '';
$role = isset($_POST['role']) ? trim($_POST['role']) : '';

if (empty($email) || empty($password)) {
    echo json_encode(['status' => 'error', 'message' => 'Email and password are required']);
    exit;
}

try {
    // Prepare SQL statement to prevent SQL injection
    $sql = "SELECT id, name, email, password, role FROM user WHERE email = ? AND role = ?";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }
    
    $stmt->bind_param("ss", $email, $role);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        // Verify password (plain text comparison since passwords are stored in plain text)
        if ($password === $user['password']) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['logged_in'] = true;
            
            echo json_encode([
                'status' => 'success', 
                'message' => 'Login successful!',
                'redirect' => '../admin/dashboard.php'
            ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid password']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'User not found or invalid role']);
    }
    
    $stmt->close();
    
} catch (Exception $e) {
    error_log("Login error: " . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'Login failed. Please try again.']);
}

$conn->close();
?>