<?php
require_once '../core/database.php';

if(isset($_POST['name'], $_POST['member_id'])) {
    $name = $_POST['name'];
    $member_id = $_POST['member_id'];

    $stmt = $conn->prepare("INSERT INTO members (name, member_id) VALUES (?, ?)");
    $stmt->bind_param("ss", $name, $member_id);
    if($stmt->execute()){
        echo "Member added successfully!";
    } else {
        echo "Failed to add member.";
    }
}
?>
