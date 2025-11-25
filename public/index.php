<?php

// Get the request path and remove /trator prefix for XAMPP
$request = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$request = preg_replace('/^trator\/?/', '', $request);
if ($request === '') $request = 'home';

// Debug
// echo "Request: $request<br>";

// Handle admin routes
if (strpos($request, 'admin') === 0) {
    $parts = explode('/', $request);
    
    // Get admin page (default to login if not specified)
    if (count($parts) == 1) {
        // Just /admin -> redirect to login
        $adminPage = 'login';
    } else {
        // /admin/something -> use something
        $adminPage = $parts[1];
        
        // Remove .php extension if present
        if (substr($adminPage, -4) === '.php') {
            $adminPage = substr($adminPage, 0, -4);
        }
    }
    
    $adminFile = __DIR__ . '/pages/admin/' . $adminPage . '.php';
    
    if (file_exists($adminFile)) {
        require $adminFile;
        exit();
    } else {
        // Admin file not found, show 404
        http_response_code(404);
        echo "Admin page not found: $adminPage (looking for: $adminFile)";
        exit();
    }
}

// Handle regular pages
$file = __DIR__ . '/pages/' . $request . '.php';

if (file_exists($file)) {
    require $file;
} else {
    http_response_code(404);
    require __DIR__ . '/pages/404.php';
}
