<?php

require 'vendor/autoload.php';
require 'DatabaseConnector.php';
use Ramsey\Uuid\Uuid;

session_start();

// Get the current script's directory relative to the document root
$scriptPathDir = dirname($_SERVER['PHP_SELF']);

$uri = $_SERVER['REQUEST_URI'];
// Adjust the regular expression for matching the UUID, making it relative to the script's directory
if (preg_match('/\/uuid\/([a-f0-9\-]+)$/', $uri, $matches)) {
    // Display the UUID from the URL
    $uuidFromUrl = $matches[1];
    echo "UUID from URL: " . htmlspecialchars($uuidFromUrl) . "<br>";
} else {
    // Normal page operation for generating and displaying a UUID
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['generate_uuid'])) {
        try {
            $uuid4 = Uuid::uuid4();
            $_SESSION['generated_uuid'] = $uuid4->toString();
            // Redirect to a URL relative to the script's directory
            header('Location: ' . $scriptPathDir . 'uuid/' . $uuid4->toString());
            exit;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            exit;
        }
    }

    if (isset($_SESSION['generated_uuid'])) {
        echo "Generated UUID: " . $_SESSION['generated_uuid'] . "<br>";
        unset($_SESSION['generated_uuid']);
    }

    // Database operation example remains unchanged
    try {
        $databaseConnector = new DatabaseConnector();
        $pdo = $databaseConnector->getConnection();
        $sql = 'SELECT count(*) FROM opendata;';
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        while ($row = $stmt->fetch()) {
            echo json_encode($row) . PHP_EOL;
        }
    } catch (\PDOException $e) {
        echo "Error: " . $e->getMessage();
        exit;
    }

    echo '<form method="post">
            <input type="submit" name="generate_uuid" value="Generate UUID">
          </form>';
}
?>
