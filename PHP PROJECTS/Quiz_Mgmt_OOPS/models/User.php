<?php
require_once "../gateways/UserGateway.php";

class User {
    private $gateway;

    public function __construct() {
        $this->gateway = new UserGateway();
    }

    // Admin login
    public function login($email, $password) {
        $result = $this->gateway->getAdminByEmailPassword($email, $password);
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return false;
    }

    // Add new user
    public function addUser($name, $email, $password, $role) {
        return $this->gateway->insertUser($name, $email, $password, $role);
    }
}
?>
