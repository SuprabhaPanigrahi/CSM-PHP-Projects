<?php
// src/core/Controller.php
class Controller {
    protected function view($path, $data = []) {
        extract($data);
        require __DIR__ . '/../views/layout/header.php';
        require __DIR__ . '/../views/' . $path . '.php';
        require __DIR__ . '/../views/layout/footer.php';
        exit;
    }

    protected function redirect($url) {
        header("Location: $url");
        exit;
    }
}
