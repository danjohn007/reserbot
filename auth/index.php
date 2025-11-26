<?php
/**
 * Auth directory index
 * Redirects to login page
 */

// Define the route before including the front controller
// This is a hardcoded, safe value - not user input
define('RESERBOT_ROUTE', 'auth/login');
$_GET['url'] = RESERBOT_ROUTE;
require_once dirname(__DIR__) . '/index.php';
