<?php
// src/models/Technology.php
class Technology extends Model {
    public function all() {
        $res = $this->db->query("SELECT * FROM technology ORDER BY TechName");
        return $res->fetch_all(MYSQLI_ASSOC);
    }
    public function create($name) {
        $stmt = $this->db->prepare("INSERT INTO technology (TechName) VALUES (?)");
        $stmt->bind_param('s', $name);
        return $stmt->execute();
    }
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM technology WHERE TechID=?");
        $stmt->bind_param('i',$id);
        return $stmt->execute();
    }
}
