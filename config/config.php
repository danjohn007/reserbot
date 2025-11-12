<?php
/**
 * ReserBot - Configuration File
 * Main configuration settings
 */

// Error Reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Timezone
date_default_timezone_set('America/Mexico_City');

// Auto-detect base URL
function detectBaseUrl() {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $host = $_SERVER['HTTP_HOST'];
    $script = $_SERVER['SCRIPT_NAME'];
    $path = str_replace('/index.php', '', $script);
    return $protocol . $host . $path;
}

// Base URL Configuration
define('BASE_URL', detectBaseUrl());
define('BASE_PATH', dirname(dirname(__FILE__)));

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'i45com_reserbot');
define('DB_USER', 'i45com_reserbot');
define('DB_PASS', 'Danjohn007!');
define('DB_CHARSET', 'utf8mb4');

// Application Settings
define('APP_NAME', 'ReserBot');
define('APP_VERSION', '1.0.0');
define('APP_ENV', 'development'); // development, production

// Security Settings
define('SESSION_LIFETIME', 7200); // 2 hours in seconds
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOGIN_TIMEOUT', 900); // 15 minutes
define('CSRF_TOKEN_NAME', '_csrf_token');

// Paths
define('CONTROLLERS_PATH', BASE_PATH . '/app/controllers/');
define('MODELS_PATH', BASE_PATH . '/app/models/');
define('VIEWS_PATH', BASE_PATH . '/app/views/');
define('LOGS_PATH', BASE_PATH . '/logs/');

// File Upload Settings
define('UPLOAD_MAX_SIZE', 10 * 1024 * 1024); // 10MB
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/gif']);

// Roles
define('ROLE_SUPERADMIN', 1);
define('ROLE_ADMIN', 2);
define('ROLE_ESPECIALISTA', 3);
define('ROLE_CLIENTE', 4);
define('ROLE_RECEPCIONISTA', 5);

// Status Constants
define('STATUS_PENDIENTE', 'pendiente');
define('STATUS_CONFIRMADA', 'confirmada');
define('STATUS_COMPLETADA', 'completada');
define('STATUS_CANCELADA', 'cancelada');

// Load database configuration
require_once __DIR__ . '/database.php';
