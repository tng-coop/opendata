<?php

require 'DatabaseConnector.php'; // Adjust the path as needed to locate the DatabaseConnector class

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
