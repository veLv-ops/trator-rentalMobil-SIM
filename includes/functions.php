<?php
// Determine correct path for database config
if (file_exists('../config/database.php')) {
    require_once '../config/database.php';
} elseif (file_exists('../../config/database.php')) {
    require_once '../../config/database.php';
} else {
    require_once dirname(__DIR__) . '/config/database.php';
}

// Initialize database connection
$database = new Database();
$db = $database->getConnection();

// Function to sanitize input
function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Function to redirect
function redirect($url) {
    header("Location: $url");
    exit();
}

// Function to check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Function to start session if not started
function startSession() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
}
?>