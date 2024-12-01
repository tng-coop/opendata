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
        // Fetch non-loopback IP dynamically
        $nonLoopbackIP = $this->getNonLoopbackIP();
        
        // Build the expected base URL
        $expectedBaseURL = "http://$nonLoopbackIP:8000";
        
        // Retrieve base URL from appConfig
        $baseURL = $this->appConfig->get('url.base');
        
        // Assert base URL is valid
        $this->assertIsString($baseURL, 'Base URL should be a string.');
        $this->assertEquals($expectedBaseURL, $baseURL, 'Base URL should match the expected value.');
    }
    
    /**
     * Get the first non-loopback IP address of the server.
     */
    private function getNonLoopbackIP(): string
    {
        // Execute shell command to find the non-loopback IP
        $output = shell_exec("ip addr show | grep 'inet ' | grep -v '127.0.0.1' | awk '{print $2}' | cut -d'/' -f1 | head -n 1");
    
        // Trim and return the result
        $nonLoopbackIP = trim($output);
    
        // Validate result
        if (filter_var($nonLoopbackIP, FILTER_VALIDATE_IP)) {
            return $nonLoopbackIP;
        }
    
        // Fallback to default or throw an exception if no valid IP is found
        throw new RuntimeException('Unable to determine the non-loopback IP address.');
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
