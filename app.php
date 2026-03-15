<?php

$request = $_SERVER['REQUEST_URI'];
$method  = $_SERVER['REQUEST_METHOD'];

$path = parse_url($request, PHP_URL_PATH);

require_once __DIR__ . '/server/controller/ItemController.php';

// Normalize method override if needed (e.g., using _method in POST)
if ($method === 'POST' && isset($_POST['_method'])) {
    $override = strtoupper($_POST['_method']);
    if (in_array($override, ['PUT', 'DELETE'], true)) {
        $method = $override;
    }
}

// API routes
if ($method === 'POST' && $path === '/add') {
    $controller = new ItemController();
    $controller->store();
    exit;
}

if (($method === 'POST' && $path === '/update') || ($method === 'PUT' && $path === '/edit')) {
    $controller = new ItemController();
    $controller->update();
    exit;
}

if (($method === 'POST' && $path === '/delete') || ($method === 'DELETE' && $path === '/remove')) {
    $controller = new ItemController();
    $controller->destroy();
    exit;
}

// Existing home route - show animals and admin panel
$path = rtrim($path, '/') ?: '/';
$staticFile = __DIR__ . '/public' . $path;

if (file_exists($staticFile) && is_file($staticFile)) {
    $mime = [
        'css'  => 'text/css',
        'js'   => 'application/javascript',
        'png'  => 'image/png',
        'jpg'  => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'ico'  => 'image/x-icon',
        'svg'  => 'image/svg+xml',
        'woff' => 'font/woff',
        'woff2'=> 'font/woff2',
    ];

    $ext = strtolower(pathinfo($staticFile, PATHINFO_EXTENSION));
    header('Content-Type: ' . ($mime[$ext] ?? 'application/octet-stream'));
    readfile($staticFile);
    exit;
}

if ($method === 'GET' && ($path === '/' || $path === '/index.php')) {
    $controller = new ItemController();
    $controller->index();
} else {
    http_response_code(404);
    header('Content-Type: application/json');
    echo json_encode([
        'status'  => 'error',
        'message' => '404 - Page Not Found',
    ]);
}