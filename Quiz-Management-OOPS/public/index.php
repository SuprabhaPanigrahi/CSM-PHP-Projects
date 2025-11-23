<?php
// public/index.php
session_start();
require_once __DIR__ . '/../src/config.php';
require_once __DIR__ . '/../src/Database.php';
require_once __DIR__ . '/../src/core/Controller.php';
require_once __DIR__ . '/../src/core/Model.php';

// autoload controllers and models
spl_autoload_register(function($class) {
    $paths = [__DIR__ . '/../src/controllers/', __DIR__ . '/../src/models/'];
    foreach ($paths as $p) {
        $f = $p . $class . '.php';
        if (file_exists($f)) { require_once $f; return; }
    }
});

// simple router ?r=controller/action
$route = $_GET['r'] ?? 'auth/login';
list($controller, $action) = explode('/', $route . '/');
$controllerClass = ucfirst($controller) . 'Controller';
if (!class_exists($controllerClass)) {
    header("HTTP/1.0 404 Not Found");
    echo "Controller not found";
    exit;
}
$ctrl = new $controllerClass();
if (!method_exists($ctrl, $action)) {
    header("HTTP/1.0 404 Not Found");
    echo "Action not found";
    exit;
}
$ctrl->{$action}();
