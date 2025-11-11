<?php
session_start();
require_once dirname(__DIR__, 3) . '/includes/admin_auth.php';

$adminAuth = new AdminAuth();
$adminAuth->logout();

header('Location: /trator/home');
exit();
?>