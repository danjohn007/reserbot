<?php
/**
 * Logout page router
 * This file provides a direct access point for /auth/logout
 * ensuring the route works even without mod_rewrite
 */

// Include the front controller with the auth/logout route
$_GET['url'] = 'auth/logout';
require_once dirname(__DIR__) . '/index.php';
