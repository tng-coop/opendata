<?php
declare(strict_types=1); // Enforce strict type checking

error_reporting(E_ALL); // Report all PHP errors
ini_set('display_errors', '1'); // Display errors (use '0' for production environments)
ini_set('log_errors', '1'); // Log errors to the server's error log file

$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];
require_once 'loadAppConfig.php';
require_once 'databaseOperations.php';
ini_set('session.use_cookies', '1');
ini_set('session.cookie_lifetime', '86400');
session_start();
require_once 'vendor/autoload.php';
require_once 'ExceptionHandler.php';
set_exception_handler('myExceptionHandler');
set_error_handler('handleError');

