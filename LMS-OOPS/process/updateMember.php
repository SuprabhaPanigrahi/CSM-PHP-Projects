<?php
require_once '../core/database.php';

if(isset($_POST['id'], $_POST['name'], $_POST['member_id'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $member_id = $_POST['member_id'];

    $stmt = $conn->prepare("UPDATE members SET name = ?, member_id = ? WHERE id = ?");
    $stmt->bind_param("ssi", $name, $member_id, $id);
    if($stmt->execute()){
        echo "Member updated successfully!";
    } else {
        echo "Failed to update member.";
    }
}
?>
