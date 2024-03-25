<?php
session_start();
require 'vendor/autoload.php';
require_once 'ExceptionHandler.php';
set_exception_handler('myExceptionHandler');
set_error_handler('handleError');
