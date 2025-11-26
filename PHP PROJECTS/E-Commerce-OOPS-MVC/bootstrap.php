<?php
// Define base paths
define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

// Auto-load classes with namespaces
spl_autoload_register(function($className) {
    $file = APP_PATH . '/' . str_replace('\\', '/', $className) . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

// Load config
$config = require_once BASE_PATH . '/config/environment.php';

// Start session
session_start();