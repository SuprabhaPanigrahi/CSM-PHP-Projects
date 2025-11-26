<?php
require_once "../gateways/ResultGateway.php";

class Result {
    private $gateway;

    public function __construct() {
        $this->gateway = new ResultGateway();
    }

    public function addResult($student_id, $question_id, $selected_option) {
        return $this->gateway->insert($student_id, $question_id, $selected_option);
    }

    public function getStudentResults($student_id) {
        return $this->gateway->getByStudent($student_id);
    }

    public function getScore($student_id) {
        return $this->gateway->calculateScore($student_id);
    }
}
?>
