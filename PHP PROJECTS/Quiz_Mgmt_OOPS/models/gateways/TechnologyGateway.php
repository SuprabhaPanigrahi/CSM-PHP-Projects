<?php
class TechnologyGateway {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getAll() {
        $techs = [];
        
        // Simple direct query - no try-catch, no prepared statements
        $sql = "SELECT id, name FROM technology";
        $result = $this->conn->query($sql);
        
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $techs[] = $row;
            }
        }
        
        return $techs;
    }

    public function getById($id) {
        $id = (int)$id;
        $sql = "SELECT * FROM technology WHERE id = $id";
        $result = $this->conn->query($sql);
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        
        return null;
    }
}
?>