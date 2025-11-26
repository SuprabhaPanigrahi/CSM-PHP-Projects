<?php
require_once '../core/database.php';

$members = $conn->query("SELECT * FROM members ORDER BY id DESC");

if(isset($_GET['for_dropdown'])){
    foreach($members as $m){
        echo "<option value='{$m['id']}'>{$m['name']} ({$m['member_id']})</option>";
    }
    exit;
}

foreach($members as $m){
    echo "<tr>
            <td>{$m['id']}</td>
            <td>{$m['name']}</td>
            <td>{$m['member_id']}</td>
            <td>
                <button class='btn btn-sm btn-warning edit-member' 
                        data-id='{$m['id']}' 
                        data-name='{$m['name']}' 
                        data-member_id='{$m['member_id']}'>Edit</button>
                <button class='btn btn-sm btn-danger delete-member' data-id='{$m['id']}'>Delete</button>
            </td>
          </tr>";
}
?>
