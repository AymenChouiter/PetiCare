<?php

$request = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

$path = parse_url($request, PHP_URL_PATH);

if ($method === 'GET' && ($path === '/' || $path === '/index.php')) {
    include __DIR__ . '/public/index.php'; 
} else {
    http_response_code(404);
    echo "404 - Page Not Found";
}