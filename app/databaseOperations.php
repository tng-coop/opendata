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
    $sql = "SELECT (json->(json_array_length(json) - 1))->>'name' AS name ,
                   (json->(json_array_length(json) - 1))->>'district' AS district ,
                   to_char(last_update, 'Mon DD, YYYY HH24:MI') AS formatted_last_update,
                    (json->(json_array_length(json) - 1))->>'text' AS last_element,
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


function getLastElement($dataArray, $what)
{
    // Check if the array is empty
    if (empty($dataArray)) {
        return "";
    }
    // Get the last element of the array
    $lastElement = end($dataArray);

    // Check if the "text" key exists and return its value, otherwise return an empty string
    $txt = isset($lastElement[$what]) ? $lastElement[$what] : "";
    return $txt;
}

function fetchValidGpsData()
{
    $databaseConnector = new DatabaseConnector();
    $pdo = $databaseConnector->getConnection();

    // SQL query to fetch rows with valid GPS coordinates, ordered by the last update
    $sql = "SELECT 
                (json->(json_array_length(json) - 1))->>'name' AS name,
                (json->(json_array_length(json) - 1))->'gps'->>'latitude' AS latitude,
                (json->(json_array_length(json) - 1))->'gps'->>'longitude' AS longitude
            FROM 
                opendata
            WHERE 
                (json->(json_array_length(json) - 1))->'gps' IS NOT NULL
            ORDER BY 
                last_update DESC;";

    $stmt = $pdo->query($sql); // This is suitable for a simple, parameter-less query

    // Fetch all matching rows
    $gpsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $gpsData;
}
