<?php
include "../core/database.php";
include "../core/models/Member.php";

$id = $_POST['member_id'];

$result = Member::searchMember($conn, $id);
?>
