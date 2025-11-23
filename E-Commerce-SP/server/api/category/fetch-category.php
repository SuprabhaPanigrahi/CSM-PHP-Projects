<?php
header('Content-Type: application/json');

include_once __DIR__ . '/../../inc/bootstrap.php';

$sql = "CALL USP_CATEGORY(NULL, NULL, 'SELECT');";
$result = mysqli_query($conn, $sql);

if(!$result) {
    echo json_encode(['success' => false, 'message' => 'Error fetching categories']);
}
else{
    $categories = [];
    while($row = mysqli_fetch_assoc($result)) {
        $categories[] = $row;
    }
    echo json_encode($categories);
}

mysqli_close($conn);
?>