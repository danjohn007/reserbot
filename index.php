<?php
/**
 * ReserBot - Front Controller
 * Main entry point for all requests
 */

// Start session
session_start();

// Load configuration
require_once __DIR__ . '/config/config.php';

// Autoloader for classes
spl_autoload_register(function ($class) {
    // Try controllers
    $controller_file = CONTROLLERS_PATH . $class . '.php';
    if (file_exists($controller_file)) {
        require_once $controller_file;
        return;
    }
    
    // Try models
    $model_file = MODELS_PATH . $class . '.php';
    if (file_exists($model_file)) {
        require_once $model_file;
        return;
    }
});

// Helper function to get URL parameter
function getUrl() {
    if (isset($_GET['url'])) {
        return rtrim($_GET['url'], '/');
    }
    // Fallback: parse from REQUEST_URI (for PHP built-in server or servers without .htaccess)
    $uri = $_SERVER['REQUEST_URI'] ?? '';
    if (strpos($uri, '?') !== false) {
        $uri = strstr($uri, '?', true);
    }
    return trim($uri, '/');
}

// Parse URL
$url = getUrl();
$url = filter_var($url, FILTER_SANITIZE_URL);
$url = explode('/', $url);

// Determine controller and method
$controllerName = !empty($url[0]) ? ucfirst($url[0]) . 'Controller' : 'HomeController';
$method = isset($url[1]) && !empty($url[1]) ? $url[1] : 'index';
$params = array_slice($url, 2);

// Check if controller exists
$controllerFile = CONTROLLERS_PATH . $controllerName . '.php';
if (!file_exists($controllerFile)) {
    $controllerName = 'HomeController';
    $method = 'notFound';
    $params = [];
}

// Instantiate controller
require_once $controllerFile;
$controller = new $controllerName();

// Check if method exists
if (!method_exists($controller, $method)) {
    $method = 'index';
}

// Call method with parameters
call_user_func_array([$controller, $method], $params);
