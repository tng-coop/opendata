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

// Handle UUID generation and redirect if the request method is POST and generate_uuid is set
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['goto_uuid'])) {
    $uuid = $_SESSION['generated_uuid'] ;
    if (!Uuid::isValid($uuid)) {
        throw new Exception("Invalid UUID format.");
    }
    $redirectPath = rtrim($scriptPathDir, '/') . '/uuid/' . $uuid;
    header('Location: ' . $redirectPath);
    exit;
}

/// if session uuid exists
if (isset($_SESSION['currentDataUuid']) && !empty($_SESSION['currentDataUuid'])) {
    $uuidFromSession = htmlspecialchars($_SESSION['currentDataUuid']);
?>
    <form method="post">
        <input type="submit" name="goto_uuid" value="Generate UUID22">
    </form>
<?php
} else {
    $data = fetchOpDataCount(); // Let global exception handler manage any errors.
    echo json_encode($data);
?>
    <form method="post">
        <input type="submit" name="generate_uuid" value="Generate UUID">
    </form>
<?php
}
