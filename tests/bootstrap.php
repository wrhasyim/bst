<?php
// Mock PHP output buffering for views
if (!defined('BASE_URL')) {
    define('BASE_URL', 'http://localhost');
}

// Ensure session is started for testing
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
