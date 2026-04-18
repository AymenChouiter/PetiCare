<?php

// Extracting request properties
$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($uri, PHP_URL_PATH);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (php_sapi_name() === 'cli-server') {
    $possibleFilePath = __DIR__ . '/public' . $path;

    if (is_file($possibleFilePath))
        return false;
}

require_once __DIR__ . '/server/controller/AnimalController.php';
require_once __DIR__ . '/server/controller/AdminController.php';

$animalController = new AnimalController();
$adminController = new AdminController();

if ($method === 'GET' && $path === '/') {
    $adminController->redirectTo('/dashboard');
} else if ($method === 'GET' && $path === '/admin') {
    $adminController->showLoginPage();
} else if ($method === 'POST' && $path === '/login') {
    $adminController->login();
} else if ($method === 'GET' && $path === '/logout') {
    $adminController->logout();
} else if ($method === 'GET' && $path === '/dashboard') {
    $adminController->requireAuth();
    $animalController->index();
} else if ($method === 'POST' && $path === '/add') {
    $adminController->requireAuth();
    $animalController->createAnimal();
} else if ($method === 'POST' && $path === '/update') {
    $adminController->requireAuth();
    $animalController->updateAnimal();
} else if ($method === 'DELETE' && $path === '/delete') {
    $adminController->requireAuth();
    $animalController->deleteAnimal();
} else {
    http_response_code(404);
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'error',
        'message' => '404 - Page Not Found',
    ]);
}