<?php

use PHPUnit\Framework\TestCase;

require_once 'app/config.php';
require_once 'app/DatabaseConnector.php';

class DatabaseConnectorTest extends TestCase
{
    protected function setUp(): void
    {
        global $appConfig;
        $appConfig = new AppConfig(__DIR__ . '/../app.json'); // Adjust the path to match your structure
    }

    public function testGetConnectionSuccess()
    {
        $connector = new DatabaseConnector();
        $connection = $connector->getConnection();
        $this->assertInstanceOf(PDO::class, $connection);
    }
}
