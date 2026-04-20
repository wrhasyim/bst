<?php
// app/Controllers/AuthController.php
require_once __DIR__ . '/../Models/User.php';

class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function login() {
        if (isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }
        require_once __DIR__ . '/../../views/auth/login.php';
    }

    public function authenticate() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';

            $user = $this->userModel->findByUsername($username);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['nama'] = $user['nama'];
                $_SESSION['role'] = $user['role'];
                header('Location: ' . BASE_URL . '/dashboard');
                exit;
            } else {
                $_SESSION['error'] = 'Username/Password salah atau akun tidak aktif!';
                header('Location: ' . BASE_URL . '/auth/login');
                exit;
            }
        }
    }

    public function logout() {
        session_unset();
        session_destroy();
        header('Location: ' . BASE_URL . '/auth/login');
        exit;
    }
}