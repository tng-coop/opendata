<?php

use PHPUnit\Framework\TestCase;

require_once 'app/DatabaseConnector.php'; // Adjust path as necessary

class DatabaseConnectorTest extends TestCase
{
    private $connector;

    protected function setUp(): void
    {
        global $appConfig;
        $appConfig = new AppConfig(__DIR__ . '/test_app_config.json'); // Ensure the path matches your test setup
        $this->connector = new DatabaseConnector();
    }

    public function testGetConnectionSuccess()
    {
        try {
            $connection = $this->connector->getConnection();
            $this->assertInstanceOf(PDO::class, $connection, 'The connection should be an instance of PDO.');
            $this->assertTrue($connection->query('SELECT 1') !== false, 'Database connection is successful.');
        } catch (PDOException $e) {
            $this->fail('Connection unexpectedly failed: ' . $e->getMessage());
        }
    }

    public function testConnectionRefused()
    {
        global $appConfig;
        $appConfig->config['database']['port'] = '5533'; // Change port to simulate failure

        $this->expectException(PDOException::class);
        $this->expectExceptionMessage('connection to server');

        $this->connector->getConnection();
    }

    protected function tearDown(): void
    {
        $this->connector = null; // Clean up after each test
    }
}
