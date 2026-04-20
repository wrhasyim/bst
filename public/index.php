<?php
// public/index.php
session_start();
require_once __DIR__ . '/../config.php';

$url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : 'auth/login';
$url = filter_var($url, FILTER_SANITIZE_URL);
$urlParts = explode('/', $url);

$controllerName = ucfirst($urlParts[0]) . 'Controller';
$methodName = isset($urlParts[1]) ? $urlParts[1] : 'index';

$controllerFile = __DIR__ . '/../app/Controllers/' . $controllerName . '.php';

if (file_exists($controllerFile)) {
    require_once $controllerFile;
    $controller = new $controllerName();

    if (method_exists($controller, $methodName)) {
        unset($urlParts[0]);
        unset($urlParts[1]);
        $params = $urlParts ? array_values($urlParts) : [];
        call_user_func_array([$controller, $methodName], $params);
    } else {
        http_response_code(404);
        echo "404 - Method tidak ditemukan!";
    }
} else {
    http_response_code(404);
    echo "404 - Controller tidak ditemukan!";
}