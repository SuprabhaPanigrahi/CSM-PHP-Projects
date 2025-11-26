<?php
require_once "../core/database.php";

class UserGateway {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAdminByEmailPassword($email, $password) {
        $email = $this->db->escape($email);
        $password = $this->db->escape($password);

        $query = "SELECT * FROM user WHERE email='$email' AND password='$password' AND role='admin'";
        return $this->db->select($query);
    }

    public function insertUser($name, $email, $password, $role) {
        $name = $this->db->escape($name);
        $email = $this->db->escape($email);
        $password = $this->db->escape($password);
        $role = $this->db->escape($role);

        $query = "INSERT INTO user(name,email,password,role) VALUES('$name','$email','$password','$role')";
        return $this->db->execute($query);
    }
}
?>
