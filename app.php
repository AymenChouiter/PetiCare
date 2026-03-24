<?php

$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

$requestPath = parse_url($requestUri, PHP_URL_PATH);

require_once __DIR__ . '/server/controller/ItemController.php';

if ($requestMethod === 'POST' && isset($_POST['_method'])) {
    $overriddenHttpMethod = strtoupper($_POST['_method']);
    if (in_array($overriddenHttpMethod, ['PUT', 'DELETE'], true)) {
        $requestMethod = $overriddenHttpMethod;
    }
}

if ($requestMethod === 'POST' && $requestPath === '/add') {
    $itemController = new ItemController();
    $itemController->createAnimalRecord();
    exit;
}

if (($requestMethod === 'POST' && $requestPath === '/update') || ($requestMethod === 'PUT' && $requestPath === '/edit')) {
    $itemController = new ItemController();
    $itemController->updateAnimalRecord();
    exit;
}

if (($requestMethod === 'POST' && $requestPath === '/delete') || ($requestMethod === 'DELETE' && $requestPath === '/remove')) {
    $itemController = new ItemController();
    $itemController->deleteAnimalRecord();
    exit;
}

$normalizedRequestPath = rtrim($requestPath, '/') ?: '/';
$resolvedStaticAssetPath = __DIR__ . '/public' . $normalizedRequestPath;

if (file_exists($resolvedStaticAssetPath) && is_file($resolvedStaticAssetPath)) {
    $mimeTypeByFileExtension = [
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

    $requestedFileExtension = strtolower(pathinfo($resolvedStaticAssetPath, PATHINFO_EXTENSION));
    header('Content-Type: ' . ($mimeTypeByFileExtension[$requestedFileExtension] ?? 'application/octet-stream'));
    readfile($resolvedStaticAssetPath);
    exit;
}

if ($requestMethod === 'GET' && ($normalizedRequestPath === '/' || $normalizedRequestPath === '/index.php')) {
    $itemController = new ItemController();
    $itemController->renderAnimalManagementPage();
} else {
    http_response_code(404);
    header('Content-Type: application/json');
    echo json_encode([
        'status'  => 'error',
        'message' => '404 - Page Not Found',
    ]);
}