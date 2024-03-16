<?php

require 'vendor/autoload.php'; // Use Composer's autoloader to load dependencies
require 'DatabaseConnector.php'; // Adjust the path as needed to locate the DatabaseConnector class
use Ramsey\Uuid\Uuid; // Import the Ramsey\Uuid\Uuid class

session_start(); // Start a session to store temporary data

// Check if the button was pressed
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['generate_uuid'])) {
    try {
        // Generate a UUID version 4 (random)
        $uuid4 = Uuid::uuid4();
        
        // Store the UUID in the session to display after redirect
        $_SESSION['generated_uuid'] = $uuid4->toString();

        // Redirect to the same page with a GET request
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    } catch (Exception $e) {
        // Handle error
        echo "Error: " . $e->getMessage();
        exit;
    }
}

// Display the UUID if it was just generated
if (isset($_SESSION['generated_uuid'])) {
    echo "Generated UUID: " . $_SESSION['generated_uuid'] . "<br>";
    // Clear the UUID from the session to avoid displaying it on refresh
    unset($_SESSION['generated_uuid']);
}

try {
    // Instantiate DatabaseConnector and get a PDO connection
    $databaseConnector = new DatabaseConnector();
    $pdo = $databaseConnector->getConnection();

    // SQL query
    $sql = 'SELECT count(*) FROM opendata;';

    // Prepare the SQL statement
    $stmt = $pdo->prepare($sql);

    // Execute the query
    $stmt->execute();

    // Fetch the results
    while ($row = $stmt->fetch()) {
        // Output the results, for example, as JSON
        echo json_encode($row) . PHP_EOL;
    }
} catch (\PDOException $e) {
    // Handle error
    echo "Error: " . $e->getMessage();
    exit;
}

// Adding a button to generate a new UUID without using client-side JavaScript
echo '<form method="post">
        <input type="submit" name="generate_uuid" value="Generate UUID">
      </form>';
?>
