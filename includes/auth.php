<?php
require_once 'functions.php';

class Auth {
    private $db;
    
    public function __construct() {
        global $db;
        $this->db = $db;
    }
    
    public function login($username, $password) {
        $stmt = $this->db->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Debug: uncomment to see what's retrieved
        // error_log("Login debug: " . print_r($user, true));
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_role'] = $user['role'];
            
            // Debug: uncomment to see what's stored in session
            // error_log("Session after login: user_role = " . $_SESSION['user_role']);
            
            return true;
        }
        return false;
    }
    
    public function register($username, $password) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        try {
            $stmt = $this->db->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'user')");
            return $stmt->execute([$username, $hashed_password]);
        } catch(PDOException $e) {
            return false;
        }
    }
    
    public function logout() {
        session_destroy();
        return true;
    }
}
?>