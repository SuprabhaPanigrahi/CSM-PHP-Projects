 <?php

include_once __DIR__ . '/../database/connection.php';

$id = $_GET['id'];
$sql = "SELECT * FROM employees WHERE emp_id = $id";

$result = mysqli_query($conn, $sql);

echo json_encode(mysqli_fetch_assoc($result));

mysqli_close($conn);