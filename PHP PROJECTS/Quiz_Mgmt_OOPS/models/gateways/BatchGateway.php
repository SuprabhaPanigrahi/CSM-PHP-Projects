<?php
class BatchGateway {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getAll() {
        $batches = [];
        
        // Simple direct query - no try-catch, no prepared statements
        $sql = "SELECT id, name FROM batch";
        $result = $this->conn->query($sql);
        
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $batches[] = $row;
            }
        }
        
        return $batches;
    }

    public function getById($id) {
        $id = (int)$id;
        $sql = "SELECT * FROM batch WHERE id = $id";
        $result = $this->conn->query($sql);
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        
        return null;
    }
}
?>