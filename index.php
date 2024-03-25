<?php
require 'config.php';
require 'UUIDGenerator.php';
require 'databaseOperations.php';

use Ramsey\Uuid\Uuid;

session_start(); // Ensure session is started at the beginning for session management

$scriptPathDir = dirname($_SERVER['PHP_SELF']);
$uri = $_SERVER['REQUEST_URI'];

// Check if the URI contains a UUID
if (preg_match('/\/uuid\/([a-f0-9\-]+)$/', $uri, $matches)) {
    $uuidString = $matches[1]; // Extract the UUID string from the matches
    if (!Uuid::isValid($uuidString)) {
        throw new Exception("Invalid UUID format.");
    }
    $sanitizedUuid = htmlspecialchars($uuidString); // Sanitize the UUID
    $_SESSION['currentDataUuid'] = $sanitizedUuid;
    require 'editor.php'; // Include the editor script and then exit the script
    exit;
}

// Handle UUID generation and redirect if the request method is POST and generate_uuid is set
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['generate_uuid'])) {
    $uuid4 = Uuid::uuid4();
    $_SESSION['generated_uuid'] = $uuid4->toString();
    $redirectPath = rtrim($scriptPathDir, '/') . '/uuid/' . $uuid4->toString();
    header('Location: ' . $redirectPath);
    exit;
}

// Default case: fetch operation data count, encode to JSON, and include the form
$data = fetchOpDataCount(); // Let global exception handler manage any errors.
echo json_encode($data);
include 'form.php';
