<?php

require 'config.php';
require 'UUIDGenerator.php';
require 'databaseOperations.php';

$scriptPathDir = dirname($_SERVER['PHP_SELF']);
$uri = $_SERVER['REQUEST_URI'];

if (preg_match('/\/uuid\/([a-f0-9\-]+)$/', $uri, $matches)) {
    displayUUIDFromUrl($matches[1]);
} else {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['generate_uuid'])) {
        generateAndRedirect($scriptPathDir);
    }
    $data = fetchOpDataCount(); // Let global exception handler manage any errors.
    echo json_encode($data);
    include 'form.php';
}
