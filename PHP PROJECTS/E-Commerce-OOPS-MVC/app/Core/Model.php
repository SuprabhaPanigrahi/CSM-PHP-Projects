<?php
namespace App\Core;

class Model {
    protected $db;
    
    public function __construct() {
        $database = Database::getInstance();
        $this->db = $database->getConnection();
    }
}