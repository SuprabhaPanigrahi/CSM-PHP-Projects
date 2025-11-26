<?php
// src/models/LoginUser.php
class LoginUser extends Model {
    public function findByUsername($username) {
        $stmt = $this->db->prepare("SELECT * FROM loginuser WHERE username=? LIMIT 1");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function verify($username, $password) {
        $row = $this->findByUsername($username);
        if (!$row) return false;
        return password_verify($password, $row['password']);
    }
}
