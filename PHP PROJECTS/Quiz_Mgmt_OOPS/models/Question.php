<?php
require_once "../gateways/QuestionGateway.php";

class Question {
    private $gateway;

    public function __construct() {
        $this->gateway = new QuestionGateway();
    }

    public function getAllQuestions() {
        return $this->gateway->getAll();
    }

    public function addQuestion($tech_id, $question_text, $opt1, $opt2, $opt3, $opt4, $answer) {
        return $this->gateway->insert($tech_id, $question_text, $opt1, $opt2, $opt3, $opt4, $answer);
    }

    public function getRandomQuestions($tech_id, $limit=10) {
        return $this->gateway->getRandomByTech($tech_id, $limit);
    }
}
?>
