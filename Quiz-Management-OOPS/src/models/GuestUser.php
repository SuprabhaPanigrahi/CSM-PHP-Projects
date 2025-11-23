<?php
// src/models/GuestUser.php
class GuestUser extends Model {
    public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT g.*, b.BatchName, t.TechName FROM guest_user g
            LEFT JOIN batch b ON g.batchID=b.BatchID
            LEFT JOIN technology t ON g.techID=t.TechID
            WHERE email=? LIMIT 1");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO guest_user (name,email,phone,gender,batchID,techID,created_at)
            VALUES (?, ?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param('ssssis', $data['name'], $data['email'], $data['phone'], $data['gender'], $data['batchID'], $data['techID']);
        if ($stmt->execute()) return $this->db->insert_id;
        return false;
    }
}
