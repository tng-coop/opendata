<?php
require 'DatabaseConnector.php';

function fetchOpDataCount() {
    $databaseConnector = new DatabaseConnector();
    $pdo = $databaseConnector->getConnection();
    $sql = 'SELECT count(*) FROM opendata;';
    $stmt = $pdo->query($sql); // For a simple, parameter-less query, query() is sufficient
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
function fetchJsonForUuid($uuid) {
    $databaseConnector = new DatabaseConnector();
    $pdo = $databaseConnector->getConnection();
    
    $sql = 'SELECT json, last_update FROM opendata WHERE id = :uuid;';
    $stmt = $pdo->prepare($sql);
    
    $stmt->bindParam(':uuid', $uuid, PDO::PARAM_STR);
    
    $stmt->execute();
    
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Check if result is not false and has a json key
    if ($result && isset($result['json'])) {
        // Decode the JSON data
        $result['json'] = json_decode($result['json'], true);
    }
    
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

function appendOpDataWithUuid($uuid, $newData)
{
    $databaseConnector = new DatabaseConnector();
    $pdo = $databaseConnector->getConnection();

    try {
        // Begin transaction
        $pdo->beginTransaction();

        // Step 1: Retrieve the existing JSON array by UUID
        $sqlSelect = 'SELECT json FROM opendata WHERE id = :uuid FOR UPDATE;';
        $stmtSelect = $pdo->prepare($sqlSelect);
        $stmtSelect->bindParam(':uuid', $uuid, PDO::PARAM_STR);
        $stmtSelect->execute();
        $result = $stmtSelect->fetch(PDO::FETCH_ASSOC);

        // If no record was found, throw an exception
        if (!$result) {
            throw new Exception("No record found for the provided UUID: " . $uuid);
        }

        // Decode the JSON array from the retrieved record
        $currentJsonData = json_decode($result['json'], true);

        // Step 2: Append the new data to the JSON array
        array_push($currentJsonData, $newData);

        // Step 3: Encode the updated JSON array back into a string
        $updatedJsonData = json_encode($currentJsonData);

        // Step 4: Update the record with the new JSON data
        $sqlUpdate = 'UPDATE opendata SET json = :json WHERE id = :uuid;';
        $stmtUpdate = $pdo->prepare($sqlUpdate);
        $stmtUpdate->bindParam(':json', $updatedJsonData, PDO::PARAM_STR);
        $stmtUpdate->bindParam(':uuid', $uuid, PDO::PARAM_STR);
        $stmtUpdate->execute();

        // Commit transaction
        $pdo->commit();
    } catch (Exception $e) {
        // An error occurred, rollback the transaction
        $pdo->rollBack();
        // Rethrow the exception to be handled by the caller
        throw $e;
    }
}


function getLastElementText($dataArray) {
    // Check if the array is empty
    if (empty($dataArray)) {
        return "";
    }
    // Get the last element of the array
    $lastElement = end($dataArray);
    
    // Check if the "text" key exists and return its value, otherwise return an empty string
    $txt= isset($lastElement["text"]) ? $lastElement["text"] : "";
    return $txt;

}
