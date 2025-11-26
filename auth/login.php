<?php
/**
 * Login page router
 * This file provides a direct access point for /auth/login
 * ensuring the route works even without mod_rewrite
 */

// Include the front controller with the auth/login route
$_GET['url'] = 'auth/login';
require_once dirname(__DIR__) . '/index.php';
