<?php
require_once "../gateways/StudentGateway.php";

class Student {
    private $gateway;

    public function __construct() {
        $this->gateway = new StudentGateway();
    }

    public function addStudent($name, $email, $phone, $gender, $batch_id, $tech_id) {
        return $this->gateway->insert($name, $email, $phone, $gender, $batch_id, $tech_id);
    }

    public function getStudentById($id) {
        $result = $this->gateway->getById($id);
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return false;
    }
}
?>
