<?php
if (php_sapi_name() === 'cli-server') {
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $file = __DIR__ . '/public' . $uri;
    if (is_file($file)) {
        return false;
    }
}

$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

$requestPath = parse_url($requestUri, PHP_URL_PATH);

require_once __DIR__ . '/server/controller/ItemController.php';
$itemController = new ItemController();

if ($requestMethod === 'POST' && $requestPath === '/add') {
    $itemController->createAnimalRecord();
    exit;
}

if ($requestMethod === 'POST' && $requestPath === '/update') {
    $itemController->updateAnimalRecord();
    exit;
}

if ($requestMethod === 'POST' && $requestPath === '/delete') {
    $itemController->deleteAnimalRecord();
    exit;
}

$normalizedRequestPath = rtrim($requestPath, '/') ?: '/';

if ($requestMethod === 'GET' && $normalizedRequestPath === '/') {
    $itemController->renderAnimalManagementPage();
} else {
    http_response_code(404);
    header('Content-Type: application/json');
    echo json_encode([
        'status'  => 'error',
        'message' => '404 - Page Not Found',
    ]);
}