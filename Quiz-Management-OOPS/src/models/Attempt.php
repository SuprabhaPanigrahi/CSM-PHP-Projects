<?php
// src/models/Attempt.php
class Attempt extends Model {
    public function create($guestID, $score, $total) {
        $stmt = $this->db->prepare("INSERT INTO attempt (guestID, score, total, created_at) VALUES (?,?,?,NOW())");
        $stmt->bind_param('iii', $guestID, $score, $total);
        $stmt->execute();
        return $this->db->insert_id;
    }
    public function all() {
        $res = $this->db->query("SELECT a.*, g.name, g.email FROM attempt a LEFT JOIN guest_user g ON a.guestID=g.guestID ORDER BY a.created_at DESC");
        return $res->fetch_all(MYSQLI_ASSOC);
    }
}
