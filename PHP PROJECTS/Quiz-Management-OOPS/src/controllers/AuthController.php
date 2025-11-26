<?php
// src/controllers/AuthController.php
class AuthController extends Controller {
    private $userModel;
    public function __construct() {
        $this->userModel = new LoginUser();
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $u = $_POST['username'] ?? '';
            $p = $_POST['password'] ?? '';
            if ($this->userModel->verify($u, $p)) {
                $_SESSION['admin'] = $u;
                $this->redirect('?r=admin/dashboard');
            } else {
                $this->view('auth/login', ['error' => 'Invalid credentials']);
            }
        } else {
            $this->view('auth/login', []);
        }
    }

    public function logout() {
        session_unset();
        session_destroy();
        $this->redirect('?r=auth/login');
    }
}
