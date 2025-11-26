<?php
/**
 * Login page router
 * This file provides a direct access point for /auth/login
 * ensuring the route works even without mod_rewrite
 */

// Define the route before including the front controller
// This is a hardcoded, safe value - not user input
define('RESERBOT_ROUTE', 'auth/login');
$_GET['url'] = RESERBOT_ROUTE;
require_once dirname(__DIR__) . '/index.php';
