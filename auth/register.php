<?php
/**
 * Register page router
 * This file provides a direct access point for /auth/register
 * ensuring the route works even without mod_rewrite
 */

// Include the front controller with the auth/register route
$_GET['url'] = 'auth/register';
require_once dirname(__DIR__) . '/index.php';
