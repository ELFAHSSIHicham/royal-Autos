<?php
declare(strict_types=1);

session_start();

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../config/database.php';

$routes = require __DIR__ . '/../config/routes.php';

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';

// If project is in a subfolder, set it here (example: /royal-Autos/public)
$basePath = '';
if ($basePath !== '' && str_starts_with($path, $basePath)) {
    $path = substr($path, strlen($basePath));
}
if ($path === '') {
    $path = '/';
}

if (!isset($routes[$path])) {
    http_response_code(404);
    echo "404 - Route not found";
    exit;
}

[$class, $method] = $routes[$path];

if (!class_exists($class)) {
    http_response_code(500);
    echo "Class not found: " . htmlspecialchars($class);
    exit;
}

$controller = new $class();

if (!method_exists($controller, $method)) {
    http_response_code(500);
    echo "Method not found: " . htmlspecialchars($method);
    exit;
}

$controller->$method();


