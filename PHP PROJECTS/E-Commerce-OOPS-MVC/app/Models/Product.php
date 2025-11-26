<?php
namespace App\Models;

use App\Core\Model;
use App\Traits\LoggerTrait;

class Product extends Model {
    use LoggerTrait;
    
    public function getAll() {
        $this->log("Fetching all products");
        
        $result = $this->db->query("SELECT * FROM products");
        $products = [];
        
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
        
        return $products;
    }
    
    public function getById($id) {
        $this->log("Fetching product ID: $id");
        
        $stmt = $this->db->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
}