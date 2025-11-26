<?php
class QuestionGateway {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getQuestionsByTechnology($tech_id) {
        $questions = [];
        
        error_log("Getting questions for technology ID: $tech_id");
        
        // First, count how many questions we have for this technology
        $count_sql = "SELECT COUNT(*) as total FROM question WHERE technology_id = $tech_id";
        $count_result = $this->conn->query($count_sql);
        $total_questions = $count_result ? $count_result->fetch_assoc()['total'] : 0;
        
        error_log("Total questions available for tech $tech_id: $total_questions");
        
        // If we have questions, get up to 10 random ones
        if ($total_questions > 0) {
            $limit = min($total_questions, 10); // Get up to 10 questions
            
            $sql = "SELECT id, technology_id, question_text, option1, option2, option3, option4, answer 
                    FROM question 
                    WHERE technology_id = $tech_id 
                    ORDER BY RAND() LIMIT $limit";
            
            error_log("Executing SQL: $sql");
            
            $result = $this->conn->query($sql);
            
            if ($result) {
                error_log("Query successful, found: " . $result->num_rows . " questions");
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $questions[] = $row;
                    }
                }
            } else {
                error_log("Query failed: " . $this->conn->error);
            }
        } else {
            error_log("No questions found for technology ID: $tech_id");
        }
        
        error_log("Returning " . count($questions) . " questions for tech $tech_id");
        return $questions;
    }

    public function getById($id) {
        $id = (int)$id;
        $sql = "SELECT * FROM question WHERE id = $id";
        $result = $this->conn->query($sql);
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        
        return null;
    }
}
?>