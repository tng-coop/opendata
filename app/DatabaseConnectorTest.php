<?php

use PHPUnit\Framework\TestCase;

require_once 'app/loadAppConfig.php'; // Ensure the path matches your project structure
require_once 'app/DatabaseConnector.php';

class DatabaseConnectorTest extends TestCase
{
    protected function setUp(): void
    {
        global $appConfig;
        // Initialize $appConfig using the existing `app.json` for testing
        $appConfig = new AppConfig(__DIR__ . '/../app.json'); // Adjust the path as needed
    }

    public function testGetConnectionSuccess()
    {
        $connector = new DatabaseConnector();
        $connection = $connector->getConnection();
        $this->assertInstanceOf(PDO::class, $connection, 'The connection should be an instance of PDO.');
    }

    public function testDatabaseNameFromConfig()
    {
        global $appConfig;
        $dbname = $appConfig->get('database.dbname');
        $this->assertNotNull($dbname, 'The database name should be retrieved from the configuration.');
        $this->assertIsString($dbname, 'The database name should be a string.');
    }

    public function testHostFromConfig()
    {
        global $appConfig;
        $host = $appConfig->get('database.host');
        $this->assertNotNull($host, 'The database host should be retrieved from the configuration.');
        $this->assertIsString($host, 'The database host should be a string.');
    }

    public function testPortFromConfig()
    {
        global $appConfig;
        $port = $appConfig->get('database.port');
        $this->assertNotNull($port, 'The database port should be retrieved from the configuration.');
        $this->assertIsNumeric($port, 'The database port should be numeric.');
    }

    public function testURLBaseFromConfig()
    {
        global $appConfig;
        $baseURL = $appConfig->get('url.base');
        $this->assertNotNull($baseURL, 'The base URL should be retrieved from the configuration.');
        $this->assertIsString($baseURL, 'The base URL should be a string.');
    }

    public function testURLRootFromConfig()
    {
        global $appConfig;
        $rootURL = $appConfig->get('url.root');
        $this->assertNotNull($rootURL, 'The root URL should be retrieved from the configuration.');
        $this->assertIsString($rootURL, 'The root URL should be a string.');
    }
}
