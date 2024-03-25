<?php
require 'DatabaseConnector.php';

function fetchOpDataCount() {
    $databaseConnector = new DatabaseConnector();
    $pdo = $databaseConnector->getConnection();
    $sql = 'SELECT count(*) FROM opendata;';
    $stmt = $pdo->query($sql); // For a simple, parameter-less query, query() is sufficient
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
// Function to fetch JSON data for a given UUID
function fetchJsonForUuid($uuid) {
    $databaseConnector = new DatabaseConnector();
    $pdo = $databaseConnector->getConnection();
    
    // Prepare a SQL statement to prevent SQL injection
    $sql = 'SELECT json FROM opendata WHERE id = :uuid;';
    $stmt = $pdo->prepare($sql);
    
    // Bind the UUID parameter
    $stmt->bindParam(':uuid', $uuid, PDO::PARAM_STR);
    
    // Execute the query
    $stmt->execute();
    
    // Fetch the result
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
}