<?php
require 'DatabaseConnector.php';

function fetchOpDataCount() {
    $databaseConnector = new DatabaseConnector();
    $pdo = $databaseConnector->getConnection();
    $sql = 'SELECT count(*) FROM opendata;';
    $stmt = $pdo->query($sql); // For a simple, parameter-less query, query() is sufficient
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
// Function to fetch JSON data and last update time for a given UUID
function fetchJsonForUuid($uuid) {
    $databaseConnector = new DatabaseConnector();
    $pdo = $databaseConnector->getConnection();
    
    // Prepare a SQL statement to prevent SQL injection
    // Select both json and last_update columns
    $sql = 'SELECT json, last_update FROM opendata WHERE id = :uuid;';
    $stmt = $pdo->prepare($sql);
    
    // Bind the UUID parameter
    $stmt->bindParam(':uuid', $uuid, PDO::PARAM_STR);
    
    // Execute the query
    $stmt->execute();
    
    // Fetch the result
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
}

function insertOpDataWithUuid($uuid) {
    $databaseConnector = new DatabaseConnector();
    $pdo = $databaseConnector->getConnection();
    
    // Prepare a SQL statement to prevent SQL injection
    $sql = 'INSERT INTO opendata (id) VALUES (:uuid);';
    $stmt = $pdo->prepare($sql);
    
    // Bind the UUID parameter
    $stmt->bindParam(':uuid', $uuid, PDO::PARAM_STR);
    
    // Execute the query
    $stmt->execute();
}


function getLastElementText($jsonString) {
    // Decode the JSON string into an array
    $dataArray = json_decode($jsonString, true);
    
    // Check if the array is empty
    if (empty($dataArray)) {
        return "";
    }
    
    // Get the last element of the array
    $lastElement = end($dataArray);
    
    // Check if the "text" key exists and return its value, otherwise return an empty string
    return isset($lastElement["text"]) ? $lastElement["text"] : "";
}
