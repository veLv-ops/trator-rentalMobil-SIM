<?php
session_start();
require_once '../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';
    $auth = new Auth();

    if ($action == 'login') {
        $username = sanitize($_POST['username']);
        $password = $_POST['password'];

        if ($auth->login($username, $password)) {
            redirect('/home');
        } else {
            redirect('/auth?error=invalid_credentials');
        }
    } elseif ($action == 'register') {
        $username = sanitize($_POST['username']);
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        if ($password !== $confirm_password) {
            redirect('/auth?error=password_mismatch');
        } elseif ($auth->register($username, $password)) {
            // Auto login after register
            $auth->login($username, $password);
            redirect('/home');
        } else {
            redirect('/auth?error=registration_failed');
        }
    }
} else {
    redirect('/auth');
}
