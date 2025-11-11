<?php
require_once 'functions.php';

class AdminAuth {
    private $db;
    
    public function __construct() {
        global $db;
        $this->db = $db;
    }
    
    public function login($username, $password) {
        $stmt = $this->db->prepare("SELECT id, username, password, role FROM users WHERE username = ? AND role = 'admin'");
        $stmt->execute([$username]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($admin && password_verify($password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            $_SESSION['user_role'] = 'admin';
            return true;
        }
        return false;
    }
    
    public function isLoggedIn() {
        return isset($_SESSION['admin_id']) && isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
    }
    
    public function logout() {
        // Clear all session data to ensure clean logout
        session_unset();
        session_destroy();
        return true;
    }
}
?>