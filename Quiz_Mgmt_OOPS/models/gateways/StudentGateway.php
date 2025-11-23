<?php
class StudentGateway {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function addStudent($name, $email, $phone = '', $gender = '', $batch_id, $tech_id) {
        // Handle gender - if empty, set to NULL
        if (empty($gender)) {
            $gender = 'NULL';
        } else {
            $gender = "'" . $this->conn->real_escape_string($gender) . "'";
        }
        
        // Handle phone - if empty, set to NULL
        if (empty($phone)) {
            $phone = 'NULL';
        } else {
            $phone = "'" . $this->conn->real_escape_string($phone) . "'";
        }
        
        $name = $this->conn->real_escape_string($name);
        $email = $this->conn->real_escape_string($email);
        $batch_id = (int)$batch_id;
        $tech_id = (int)$tech_id;

        // Build SQL with NULL for optional fields
        $sql = "INSERT INTO student (name, email, phone, gender, batch_id, tech_id) 
                VALUES ('$name', '$email', $phone, $gender, $batch_id, $tech_id)";
        
        if ($this->conn->query($sql)) {
            return $this->conn->insert_id;
        }
        
        return false;
    }

    public function getById($id) {
        $id = (int)$id;
        $sql = "SELECT s.*, b.name as batch_name, t.name as tech_name 
                FROM student s 
                LEFT JOIN batch b ON s.batch_id = b.id 
                LEFT JOIN technology t ON s.tech_id = t.id 
                WHERE s.id = $id";
        
        $result = $this->conn->query($sql);
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        
        return null;
    }

    public function getByEmail($email) {
        $email = $this->conn->real_escape_string($email);
        $sql = "SELECT * FROM student WHERE email = '$email'";
        $result = $this->conn->query($sql);
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        
        return null;
    }
}
?>