<?php

use PHPUnit\Framework\TestCase;

require_once 'loadAppConfig.php'; // Ensure the app config is loaded
require_once 'DatabaseConnector.php'; // Load the DatabaseConnector class
require_once 'databaseOperations.php'; // Load the script with the functions to test

class DatabaseFunctionsTest extends TestCase
{
    private $databaseConnector;
    private $pdo;

    protected function setUp(): void
    {
        // Initialize the DatabaseConnector
        $this->databaseConnector = new DatabaseConnector();
        $this->pdo = $this->databaseConnector->getConnection();

        // Ensure a clean state
        $this->pdo->exec('DELETE FROM opendata;');
    }

    public function testDoesUuidExist()
    {
        // Prepare a test UUID
        $testUuid = '123e4567-e89b-12d3-a456-426614174000';

        // Insert a test row
        $this->pdo->exec("INSERT INTO opendata (id) VALUES ('$testUuid');");

        // Call the function
        $result = doesUuidExist($testUuid);

        // Assert that the UUID exists
        $this->assertTrue($result);
    }

    public function testCreateEntityIfNotExists()
    {
        // Test UUID
        $testUuid = '123e4567-e89b-12d3-a456-426614174001';

        // Call the function
        createEntityIfNotExists($testUuid);

        // Verify the UUID was inserted
        $stmt = $this->pdo->prepare('SELECT COUNT(*) as count FROM opendata WHERE id = :uuid');
        $stmt->bindParam(':uuid', $testUuid, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->assertEquals(1, $result['count']);
    }

    public function testFetchOpDataCount()
    {
        // Use valid UUIDs
        $validUuid1 = '123e4567-e89b-12d3-a456-426614174001';
        $validUuid2 = '123e4567-e89b-12d3-a456-426614174002';
    
        // Insert test rows with valid UUIDs
        $this->pdo->exec("INSERT INTO opendata (id) VALUES ('$validUuid1');");
        $this->pdo->exec("INSERT INTO opendata (id) VALUES ('$validUuid2');");
    
        // Call the function
        $result = fetchOpDataCount();
    
        // Assert the count is correct
        $this->assertEquals(2, $result[0]['count']);
    }
    
    public function testFetchJsonForUuid()
    {
        // Test UUID and JSON data
        $testUuid = '123e4567-e89b-12d3-a456-426614174002';
        $jsonData = json_encode(['key' => 'value']);
        $this->pdo->exec("INSERT INTO opendata (id, json) VALUES ('$testUuid', '$jsonData');");

        // Call the function
        $result = fetchJsonForUuid($testUuid);

        // Assert the JSON data is fetched and decoded correctly
        $this->assertEquals(['key' => 'value'], $result);
    }

    public function testAppendOpDataWithUuid()
    {
        // Test UUID and initial data
        $testUuid = '123e4567-e89b-12d3-a456-426614174003';
        $initialData = json_encode([['existing' => 'data']]);
        $this->pdo->exec("INSERT INTO opendata (id, json) VALUES ('$testUuid', '$initialData');");
    
        // New data to append
        $newData = ['new' => 'data'];
    
        // Call the function
        appendOpDataWithUuid($testUuid, $newData);
    
        // Verify the JSON was updated
        $stmt = $this->pdo->prepare('SELECT json FROM opendata WHERE id = :uuid');
        $stmt->bindParam(':uuid', $testUuid, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        // Decode both expected and actual JSON for comparison
        $expectedData = [['existing' => 'data'], ['new' => 'data']];
        $actualData = json_decode($result['json'], true);
    
        // Assert the decoded arrays are equal
        $this->assertEquals($expectedData, $actualData);
    }

    protected function tearDown(): void
    {
        // Clean up the database
        $this->pdo->exec('DELETE FROM opendata;');
    }
}
