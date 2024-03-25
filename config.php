<?php
ini_set('session.use_cookies', '1');
ini_set('session.cookie_lifetime', '86400');
session_start();
require 'vendor/autoload.php';
require_once 'ExceptionHandler.php';
set_exception_handler('myExceptionHandler');
set_error_handler('handleError');
