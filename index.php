<?php
error_log(session_status() );
require 'config.php';
require 'UUIDGenerator.php';
require 'databaseOperations.php';
use Ramsey\Uuid\Uuid;

$scriptPathDir = dirname($_SERVER['PHP_SELF']);
$uri = $_SERVER['REQUEST_URI'];

if (preg_match('/\/uuid\/([a-f0-9\-]+)$/', $uri, $matches)) {
    $uuidString = $matches[1]; // Define $uuidString based on the regex match
    if (Uuid::isValid($uuidString)) {
        $sanitizedUuid = htmlspecialchars($uuidString); // Sanitize after validating
        $_SESSION['currentDataUuid'] = $sanitizedUuid;
        require 'editor.php'; // Include the editor script
    } else {
        throw new Exception("Invalid UUID format.");
    }
} else {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['generate_uuid'])) {
        generateAndRedirect($scriptPathDir);
    }
    $data = fetchOpDataCount(); // Let global exception handler manage any errors.
    echo json_encode($data);
    include 'form.php';
}
