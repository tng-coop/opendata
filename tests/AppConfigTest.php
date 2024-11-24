<?php

use PHPUnit\Framework\TestCase;

require_once 'app/loadAppConfig.php'; // Include the necessary files for AppConfig

class AppConfigTest extends TestCase
{
    /**
     * @var AppConfig|null
     */
    private $appConfig;

    protected function setUp(): void
    {
        // Load the configuration from a valid file before each test
        $this->appConfig = new AppConfig(__DIR__ . '/../app.json'); // Adjust path as needed
    }

    public function testLoadConfigurationSuccessfully()
    {
        $this->assertNotNull($this->appConfig, 'AppConfig should be successfully instantiated.');
    }

    public function testDatabaseHostIsValid()
    {
        $dbHost = $this->appConfig->get('database.host');
        $this->assertIsString($dbHost, 'Database host should be a string.');
        $this->assertEquals('localhost', $dbHost, 'Database host should match the expected value.');
    }

    public function testDatabaseUserIsValid()
    {
        $dbUser = $this->appConfig->get('database.user');
        $this->assertIsString($dbUser, 'Database user should be a string.');
        $this->assertEquals('yasu', $dbUser, 'Database user should match the expected value.');
    }

    public function testDatabasePortIsValid()
    {
        $dbPort = $this->appConfig->get('database.port');
        $this->assertIsString($dbPort, 'Database port should be a string.');
        // assert it is a number
        $this->assertIsNumeric($dbPort, 'Database port should be a number.');
    }

    public function testBaseURLIsValid()
    {
        $baseURL = $this->appConfig->get('url.base');
        $this->assertIsString($baseURL, 'Base URL should be a string.');
        $this->assertEquals('http://10.11.12.122:8000', $baseURL, 'Base URL should match the expected value.');
    }

    public function testRootURLIsValid()
    {
        $rootURL = $this->appConfig->get('url.root');
        $this->assertIsString($rootURL, 'Root URL should be a string.');
        $this->assertEquals('/', $rootURL, 'Root URL should match the expected value.');
    }

    public function testThrowsExceptionForMissingConfiguration()
    {
        $this->expectException(Exception::class);
        new AppConfig(__DIR__ . '/missing.json'); // File doesn't exist
    }

    public function testThrowsExceptionForInvalidJSON()
    {
        // Write an invalid JSON file to test
        $invalidFile = __DIR__ . '/invalid.json';
        file_put_contents($invalidFile, '{invalid_json}');
        
        $this->expectException(Exception::class);
        new AppConfig($invalidFile);

        // Clean up
        unlink($invalidFile);
    }

    public function testMissingKeyReturnsNull()
    {
        $missingValue = $this->appConfig->get('nonexistent.key');
        $this->assertNull($missingValue, 'Missing key should return null.');
    }
}
