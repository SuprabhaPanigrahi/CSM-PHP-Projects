<?php
// src/Database.php
class Database {
    private static $instance = null;
    /** @var mysqli */
    private $conn;

    private function __construct() {
        $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($this->conn->connect_error) {
            die("DB connect error: " . $this->conn->connect_error);
        }
        $this->conn->set_charset('utf8mb4');
    }

    public static function getInstance(): Database {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    /** @return mysqli */
    public function getConnection() {
        return $this->conn;
    }
}
