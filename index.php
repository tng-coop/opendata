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
    // In the calling code, separate data handling from presentation
    try {
        $data = fetchOpDataCount();
        echo json_encode($data);
    } catch (Exception $e) {
        // Handle or log the error appropriately here
        echo json_encode(['error' => 'An error occurred.']);
    }
    include 'form.php';
}
