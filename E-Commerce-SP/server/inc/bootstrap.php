<?php
require_once __DIR__ . "/../../config/env.php";
require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../../config/paths.php";

if (session_status() === PHP_SESSION_NONE) {
    session_name(SESSION_NAME);
    session_start();
}
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_DATABASE);
if (!$conn) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed"]);
    exit();
}
mysqli_set_charset($conn, "utf8mb4");

header('Content-Type:application/json; charset=utf-8');
