<?php
require 'DatabaseConnector.php';

function performDatabaseOperation() {
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
}
