<?php
require 'DatabaseConnector.php';

function fetchOpDataCount()
{
    $databaseConnector = new DatabaseConnector();
    $pdo = $databaseConnector->getConnection();
    $sql = 'SELECT count(*) FROM opendata;';
    $stmt = $pdo->query($sql); // For a simple, parameter-less query, query() is sufficient
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
function fetchJsonForUuid($uuid)
{
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
function fetchLatestBBS()
{
    $databaseConnector = new DatabaseConnector();
    $pdo = $databaseConnector->getConnection();

    // Corrected SQL query to select id, last_update, and the text of the last element in the JSON array
    // Using PostgreSQL syntax for JSON data manipulation
    $sql = "SELECT 
    (json->(jsonb_array_length(json) - 1))->>'name' AS name,
    (json->(jsonb_array_length(json) - 1))->>'district' AS district,
    to_char(last_update, 'Mon DD, YYYY HH24:MI') AS formatted_last_update,
    (json->(jsonb_array_length(json) - 1))->>'text' AS last_element,
    id
FROM opendata
ORDER BY last_update DESC;";


    $stmt = $pdo->query($sql); // For a simple, parameter-less query, query() is sufficient

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}



function insertOpDataWithUuid($uuid)
{
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

        // Using JSONB function to append the new data directly in the database
        // Ensure your $newData is an array that can be converted into JSON
        $newDataJson = json_encode($newData); // Convert newData to JSON string
        $sqlUpdate = 'UPDATE opendata SET json = json || :newData::jsonb WHERE id = :uuid;';

        $stmtUpdate = $pdo->prepare($sqlUpdate);
        $stmtUpdate->bindParam(':newData', $newDataJson, PDO::PARAM_STR);
        $stmtUpdate->bindParam(':uuid', $uuid, PDO::PARAM_STR);
        
        $executionResult = $stmtUpdate->execute();

        // Check if any row was actually updated, if not, it means the UUID doesn't exist
        if ($stmtUpdate->rowCount() === 0) {
            throw new Exception("No record found for the provided UUID: " . $uuid);
        }

        // Commit transaction
        $pdo->commit();
    } catch (Exception $e) {
        // An error occurred, rollback the transaction
        $pdo->rollBack();
        // Rethrow the exception to be handled by the caller
        throw $e;
    }
}



function getLastElement($dataArray, $what)
{
    // if dataarray.what is non-empty, retun lastElemetn.what
    if (!empty($dataArray) && !empty($dataArray[count($dataArray) - 1][$what])) {
        return $dataArray[count($dataArray) - 1][$what];
    }
    // if what is json, return empty array
    if ($what == 'gps') {
        return [];
    }
    return '';
}

function fetchValidGpsData()
{
    $databaseConnector = new DatabaseConnector();
    $pdo = $databaseConnector->getConnection();

    // Corrected SQL query for jsonb
    $sql = "SELECT 
                id,
                (json->(jsonb_array_length(json) - 1))->>'name' AS name,
                (json->(jsonb_array_length(json) - 1))->'gps'->>'latitude' AS latitude,
                (json->(jsonb_array_length(json) - 1))->'gps'->>'longitude' AS longitude
            FROM 
                opendata
            WHERE 
                (json->(jsonb_array_length(json) - 1))->'gps' IS NOT NULL
            ORDER BY 
                last_update DESC;";

    $stmt = $pdo->query($sql); // This is suitable for a simple, parameter-less query

    // Fetch all matching rows
    $gpsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $gpsData;
}
