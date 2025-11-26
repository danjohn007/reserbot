<?php
/**
 * Auth directory index
 * Redirects to login page
 */

// Include the front controller with the auth/login route
$_GET['url'] = 'auth/login';
require_once dirname(__DIR__) . '/index.php';
