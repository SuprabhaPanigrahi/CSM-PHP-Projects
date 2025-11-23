<?php
// src/models/Batch.php
class Batch extends Model {
    public function all() {
        $res = $this->db->query("SELECT * FROM batch ORDER BY BatchName");
        return $res->fetch_all(MYSQLI_ASSOC);
    }
    public function create($name) {
        $stmt = $this->db->prepare("INSERT INTO batch (BatchName) VALUES (?)");
        $stmt->bind_param('s', $name);
        return $stmt->execute();
    }
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM batch WHERE BatchID=?");
        $stmt->bind_param('i',$id);
        return $stmt->execute();
    }
}
