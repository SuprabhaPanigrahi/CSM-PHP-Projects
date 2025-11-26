<?php
require_once '../core/database.php';

if(isset($_POST['id'])) {
    $id = $_POST['id'];
    $stmt = $conn->prepare("DELETE FROM members WHERE id = ?");
    $stmt->bind_param("i", $id);
    if($stmt->execute()){
        echo "Member deleted successfully!";
    } else {
        echo "Failed to delete member.";
    }
}
?>
