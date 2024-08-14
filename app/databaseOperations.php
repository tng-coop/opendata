<?php
require 'DatabaseConnector.php';

function doesUuidExist($uuid)
{
    $databaseConnector = new DatabaseConnector();
    $pdo = $databaseConnector->getConnection();

    $sql = 'SELECT COUNT(*) as count FROM opendata WHERE id = :uuid;';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':uuid', $uuid, PDO::PARAM_STR);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result['count'] > 0;
}

function createEntityIfNotExists($uuid)
{
    $databaseConnector = new DatabaseConnector();
    $pdo = $databaseConnector->getConnection();

    try {
        // Start a transaction
        $pdo->beginTransaction();

        // Lock the table to prevent race conditions
        $pdo->exec('LOCK TABLE opendata IN EXCLUSIVE MODE;');

        // Check if the UUID exists
        if (!doesUuidExist($uuid)) {
            // Insert new entity if it does not exist
            $sql = 'INSERT INTO opendata (id) VALUES (:uuid);';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':uuid', $uuid, PDO::PARAM_STR);
            $stmt->execute();
        }

        // Commit the transaction
        $pdo->commit();
    } catch (Exception $e) {
        // Rollback the transaction in case of an error
        $pdo->rollBack();
        throw $e;
    }
}

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
        $result2 = json_decode($result['json'], true);
    }

    return $result2;
}
function fetchLatestPointsForUuid($uuid, $n = 20)
{
    $databaseConnector = new DatabaseConnector();
    $pdo = $databaseConnector->getConnection();

    $sql = 'SELECT json FROM opendata WHERE id = :uuid;';
    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':uuid', $uuid, PDO::PARAM_STR);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result && isset($result['json'])) {
        $data = json_decode($result['json'], true);
        $data = array_slice($data, -$n); // Get the last n elements
        return $data;
    }

    return [];
}
function fetchLatestBBS()
{
    $databaseConnector = new DatabaseConnector();
    $pdo = $databaseConnector->getConnection();

    // Updated SQL query with array check and safer JSON data manipulation
    $sql = "SELECT 
                (json->(jsonb_array_length(json) - 1))->>'name' AS name,
                (json->(jsonb_array_length(json) - 1))->>'district' AS district,
                to_char(last_update, 'Mon DD, YYYY HH24:MI') AS formatted_last_update,
                (json->(jsonb_array_length(json) - 1))->>'text' AS last_element,
                id
            FROM 
                opendata
            WHERE 
                jsonb_typeof(json) = 'array'
            ORDER BY 
                last_update DESC;";



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
                jsonb_typeof(json) = 'array' AND
                (json->(jsonb_array_length(json) - 1))->'gps' IS NOT NULL
            ORDER BY 
                last_update DESC;";

    $stmt = $pdo->query($sql); // This is suitable for a simple, parameter-less query

    // Fetch all matching rows
    $gpsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $gpsData;
}
