<?php
class ResultGateway {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function addResult($student_id, $tech_id, $total_questions, $correct_answers, $score_percentage) {
        $sql = "INSERT INTO results (student_id, tech_id, total_questions, correct_answers, score_percentage, created_at) 
                VALUES ($student_id, $tech_id, $total_questions, $correct_answers, $score_percentage, NOW())";
        
        if ($this->conn->query($sql)) {
            return $this->conn->insert_id;
        }
        return false;
    }

    public function getResultsByStudent($student_id) {
        $results = [];
        $sql = "SELECT r.*, t.name as tech_name 
                FROM results r 
                LEFT JOIN technology t ON r.tech_id = t.id 
                WHERE r.student_id = $student_id 
                ORDER BY r.created_at DESC";
        
        $result = $this->conn->query($sql);
        
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $results[] = $row;
            }
        }
        
        return $results;
    }
}
?>