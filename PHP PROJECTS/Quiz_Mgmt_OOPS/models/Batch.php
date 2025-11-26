<?php
require_once "../gateways/BatchGateway.php";

class Batch {
    private $gateway;

    public function __construct() {
        $this->gateway = new BatchGateway();
    }

    public function getAllBatches() {
        return $this->gateway->getAll();
    }

    public function addBatch($name) {
        return $this->gateway->insert($name);
    }
}
?>
