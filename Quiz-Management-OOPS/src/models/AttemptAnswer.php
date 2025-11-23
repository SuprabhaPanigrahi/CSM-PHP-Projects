<?php
// src/models/AttemptAnswer.php
class AttemptAnswer extends Model {
    public function save($attemptID, $questionID, $selected) {
        $stmt = $this->db->prepare("INSERT INTO attempt_answer (attemptID, questionID, selected) VALUES (?,?,?)");
        $stmt->bind_param('iii', $attemptID, $questionID, $selected);
        return $stmt->execute();
    }
}
