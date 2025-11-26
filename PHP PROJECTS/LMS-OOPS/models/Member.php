<?php
class Member {
    public static function addMember($conn, $name, $memberId){
        $stmt = $conn->prepare("INSERT INTO members(name, member_id) VALUES (?, ?)");
        $stmt->bind_param("ss", $name, $memberId);
        return $stmt->execute();
    }

    public static function getMembers($conn){
        $result = $conn->query("SELECT * FROM members");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public static function removeMember($conn, $id){
        $stmt = $conn->prepare("DELETE FROM members WHERE id=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>
