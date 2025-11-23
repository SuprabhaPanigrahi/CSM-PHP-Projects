<?php
require_once '../core/database.php';
require_once '../models/Member.php';
require_once '../core/validator.php';

$action = $_POST['action'] ?? '';

switch($action){

    // ADD MEMBER
    case "add":
        $name = Validator::clean($_POST['name']);
        $memberId = Validator::clean($_POST['member_id']);

        $stmt = $conn->prepare("INSERT INTO members (name, member_id) VALUES (?, ?)");
        $stmt->bind_param("ss", $name, $memberId);

        echo $stmt->execute() ? "Member added" : "Error adding member";
        break;


    // GET MEMBERS
    case "get":
        $res = $conn->query("SELECT * FROM members ORDER BY id DESC");

        while($m = $res->fetch_assoc()){
            echo "<tr>
                    <td>{$m['id']}</td>
                    <td>{$m['name']}</td>
                    <td>{$m['member_id']}</td>

                    <td>
                        <button class='btn btn-warning btn-sm edit-member'
                            data-id='{$m['id']}'
                            data-name='{$m['name']}'
                            data-member_id='{$m['member_id']}'>
                            Edit
                        </button>

                        <button class='btn btn-danger btn-sm delete-member'
                            data-id='{$m['id']}'>
                            Delete
                        </button>
                    </td>
                  </tr>";
        }
        break;


    // UPDATE MEMBER
    case "update":
        $id = $_POST['id'];
        $name = Validator::clean($_POST['name']);
        $memberId = Validator::clean($_POST['member_id']);

        $stmt = $conn->prepare("UPDATE members SET name=?, member_id=? WHERE id=?");
        $stmt->bind_param("ssi", $name, $memberId, $id);

        echo $stmt->execute() ? "Member updated" : "Error updating member";
        break;


    // DELETE MEMBER
    case "delete":
        $id = $_POST['id'];

        $stmt = $conn->prepare("DELETE FROM members WHERE id=?");
        $stmt->bind_param("i", $id);

        echo $stmt->execute() ? "Member removed" : "Error deleting member";
        break;


    default:
        echo "Invalid action";
}
