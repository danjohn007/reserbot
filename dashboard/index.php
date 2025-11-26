<?php
/**
 * Dashboard directory index
 * Forwards requests to the main front controller
 */

// Define the route before including the front controller
// This is a hardcoded, safe value - not user input
define('RESERBOT_ROUTE', 'dashboard');
$_GET['url'] = RESERBOT_ROUTE;
require_once dirname(__DIR__) . '/index.php';
