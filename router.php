<?php

// Extracting request properties
$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($uri, PHP_URL_PATH);

if (php_sapi_name() === 'cli-server') {
    $possibleFilePath = __DIR__ . '/public' . $path;

    if (is_file($possibleFilePath))
        return false;
}

require_once __DIR__ . '/server/controller/AnimalController.php';
$controller = new AnimalController();

if ($method === 'POST' && $path === '/add') {
    $controller->createAnimal();
} else if ($method === 'POST' && $path === '/update') {
    $controller->updateAnimal();
} else if ($method === 'DELETE' && $path === '/delete') {
    $controller->deleteAnimal();
} else if ($method === 'GET' && $path === '/') {
    $controller->index();
} else {
    http_response_code(404);
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'error',
        'message' => '404 - Page Not Found',
    ]);
}