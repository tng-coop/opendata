<?php
require 'DatabaseConnector.php';

function fetchOpDataCount() {
    try {
        $databaseConnector = new DatabaseConnector();
        $pdo = $databaseConnector->getConnection();
        $sql = 'SELECT count(*) FROM opendata;';
        $stmt = $pdo->query($sql); // For a simple, parameter-less query, query() is sufficient
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (\PDOException $e) {
        // Consider logging the error here instead of directly outputting it
        throw new Exception("Database error: " . $e->getMessage());
    }
}

