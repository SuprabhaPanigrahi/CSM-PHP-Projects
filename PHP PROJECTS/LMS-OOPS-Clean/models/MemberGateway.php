<?php
require_once '../core/Database.php';
require_once 'Member.php';

class MemberGateway {

    public static function getAll() {
        global $conn;
        $result = $conn->query("SELECT * FROM members ORDER BY id DESC");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public static function insert($name, $member_id) {
        global $conn;
        $stmt = $conn->prepare("INSERT INTO members (name, member_id) VALUES (?, ?)");
        $stmt->bind_param("ss", $name, $member_id);
        return $stmt->execute();
    }

    public static function delete($id) {
        global $conn;
        return $conn->query("DELETE FROM members WHERE id=$id");
    }

    public static function getForDropdown() {
        global $conn;
        $res = $conn->query("SELECT * FROM members ORDER BY name ASC");
        return $res->fetch_all(MYSQLI_ASSOC);
    }
}
