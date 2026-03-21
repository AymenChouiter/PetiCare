<?php

if (php_sapi_name() === 'cli-server') {
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $file = __DIR__ . '/public' . $uri;
    if (is_file($file)) {
        return false;
    }
}

$request = $_SERVER['REQUEST_URI'];
$method  = $_SERVER['REQUEST_METHOD'];

$path = parse_url($request, PHP_URL_PATH);

require_once __DIR__ . '/server/controller/ItemController.php';
$controller = new ItemController(); 

// Normalize method override if needed (e.g., using _method in POST)
if ($method === 'POST' && isset($_POST['_method'])) {
    $override = strtoupper($_POST['_method']);
    if (in_array($override, ['PUT', 'DELETE'], true)) {
        $method = $override;
    }
}

// API routes
if ($method === 'POST' && $path === '/add') {
    $controller->store();
    exit;
}

if (($method === 'POST' && $path === '/update') || ($method === 'PUT' && $path === '/edit')) {
    $controller->update();
    exit;
}

if (($method === 'POST' && $path === '/delete') || ($method === 'DELETE' && $path === '/remove')) {
    $controller->destroy();
    exit;
}

// Existing home route - show animals and admin panel
if ($method === 'GET' && ($path === '/' || $path === '/index.php')) {
    $controller->index();
} else {
    http_response_code(404);
    header('Content-Type: application/json');
    echo json_encode([
        'status'  => 'error',
        'message' => '404 - Page Not Found',
    ]);
}