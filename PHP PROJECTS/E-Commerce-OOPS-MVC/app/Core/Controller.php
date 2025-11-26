<?php
namespace App\Core;

class Controller {
    protected function view($viewName, $data = []) {
        extract($data);
        require_once APP_PATH . "/Views/$viewName.php";
    }
    
    protected function model($modelName) {
        $className = "App\\Models\\$modelName";
        return new $className();
    }
}