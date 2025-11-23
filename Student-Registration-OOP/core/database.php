<?php

class Database
{
    private $host = "localhost";
    private $user = "root";
    private $pass = "csmpl@123";
    private $name = "oop_cases";

    /** @var mysqli */
    private $conn;

    public function __construct()
    {
        // Create connection
        $this->conn = new mysqli(
            $this->host,
            $this->user,
            $this->pass,
            $this->name
        );

        // Check connection
        if ($this->conn->connect_error) {
            die("Database connection failed: " . $this->conn->connect_error);
        }

        // Optional: set charset
        $this->conn->set_charset("utf8mb4");
    }

    public function getConnection(): mysqli
    {
        return $this->conn;
    }
}
