<?php
// src/models/Question.php
class Question extends Model {
    public function all() {
        $res = $this->db->query("SELECT q.*, b.BatchName, t.TechName FROM question q
            LEFT JOIN batch b ON q.batchID=b.BatchID
            LEFT JOIN technology t ON q.techID=t.TechID
            ORDER BY q.QnID DESC");
        return $res->fetch_all(MYSQLI_ASSOC);
    }
    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO question (Qn_desc, Opt_1, Opt_2, Opt_3, Opt_4, Answer, batchID, techID) VALUES (?,?,?,?,?,?,?,?)");
        $stmt->bind_param('sssssiii', $data['desc'], $data['opt1'], $data['opt2'], $data['opt3'], $data['opt4'], $data['answer'], $data['batchID'], $data['techID']);
        return $stmt->execute();
    }
    public function forBatchTech($batchID, $techID) {
        $stmt = $this->db->prepare("SELECT * FROM question WHERE batchID=? AND techID=? ORDER BY QnID");
        $stmt->bind_param('ii',$batchID,$techID);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
