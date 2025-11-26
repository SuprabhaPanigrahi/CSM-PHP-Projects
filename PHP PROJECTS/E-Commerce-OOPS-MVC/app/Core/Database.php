<?php
namespace App\Core;

class Database {
    private $connection;
    private static $instance = null;
    
    private function __construct() {
        $config = require_once BASE_PATH . '/config/environment.php';
        $dbConfig = $config['database'];
        
        $this->connection = new \mysqli(
            $dbConfig['host'],
            $dbConfig['user'],
            $dbConfig['password'],
            $dbConfig['database']
        );
        
        if ($this->connection->connect_error) {
            die("Database connection failed: " . $this->connection->connect_error);
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->connection;
    }
}