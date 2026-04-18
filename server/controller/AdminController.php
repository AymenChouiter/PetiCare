<?php

require_once __DIR__ . '/../../database/database.php';

class AdminController
{
    private mysqli $dbConnection;

    public function __construct()
    {
        global $conn;
        $this->dbConnection = $conn;
    }

    public function isLoggedIn(): bool
    {
        return !empty($_SESSION['admin']);
    }

    public function redirectTo(string $path): void
    {
        header("Location: {$path}");
        exit;
    }

    public function requireAuth(): void
    {
        if (!$this->isLoggedIn()) {
            $this->redirectTo('/admin');
        }
    }

    public function showLoginPage(): void
    {
        if ($this->isLoggedIn()) {
            $this->redirectTo('/dashboard');
        }

        $error = $_SESSION['login_error'] ?? null;
        unset($_SESSION['login_error']);

        require __DIR__ . '/../../public/admin.php';
    }

    public function login(): void
    {
        $identity = trim($_POST['identity'] ?? '');
        $password = (string) ($_POST['password'] ?? '');

        if ($identity === '' || $password === '') {
            $_SESSION['login_error'] = 'Username/email and password are required.';
            $this->redirectTo('/admin');
        }

        $sql = "SELECT id, username, email, password FROM admins WHERE username = ? OR email = ? LIMIT 1";
        $stmt = $this->dbConnection->prepare($sql);

        if (!$stmt) {
            $_SESSION['login_error'] = 'Login service is currently unavailable.';
            $this->redirectTo('/admin');
        }

        $stmt->bind_param('ss', $identity, $identity);
        $stmt->execute();
        $result = $stmt->get_result();
        $admin = $result ? $result->fetch_assoc() : null;
        $stmt->close();

        if (!$admin || !password_verify($password, $admin['password'])) {
            $_SESSION['login_error'] = 'Invalid credentials.';
            $this->redirectTo('/admin');
        }

        $_SESSION['admin'] = [
            'id' => (int) $admin['id'],
            'username' => $admin['username'],
            'email' => $admin['email'],
        ];

        $this->redirectTo('/dashboard');
    }

    public function logout(): void
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }
        session_destroy();
        $this->redirectTo('/admin');
    }
}

