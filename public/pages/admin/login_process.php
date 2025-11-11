<?php
session_start();
require_once dirname(__DIR__, 3) . '/includes/admin_auth.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = sanitize($_POST['username']);
    $password = $_POST['password'];
    
    $adminAuth = new AdminAuth();
    
    if ($adminAuth->login($username, $password)) {
        redirect('dashboard.php');
    } else {
        redirect('login.php?error=invalid');
    }
} else {
    redirect('login.php');
}
?>