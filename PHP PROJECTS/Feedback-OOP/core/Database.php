<?php

// file: core/Database.php
class Database
{
    private $host = "localhost";
    private $user = "root";
    private $pass = "csmpl@123";
    private $name = "feedback";
    private $conn;
    public function __construct()
    {
        $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->name);
        if ($this->conn->connect_error) {
            die("DB Connection failed: " . $this->conn->connect_error);
        }
    }
    public function getConnection()
    {
        return $this->conn;
    }
}

?>