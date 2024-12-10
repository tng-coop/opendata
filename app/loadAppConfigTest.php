<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/loadAppConfig.php';

class AppConfigTest2 extends TestCase
{
    private $configFilePath;

    protected function setUp(): void
    {
        // Create a temporary config file for testing
        $this->configFilePath = __DIR__ . '/../app_test.json';
        
        $testConfig = [
            "database" => [
                "host" => "localhost",
                "port" => 3306,
                "username" => "test_user",
                "password" => "secret"
            ],
            "api" => [
                "endpoint" => "https://api.example.com",
                "timeout" => 30
            ],
            "feature_flags" => [
                "new_dashboard" => true
            ]
        ];

        file_put_contents($this->configFilePath, json_encode($testConfig, JSON_PRETTY_PRINT));
    }

    protected function tearDown(): void
    {
        // Clean up the temporary config file
        if (file_exists($this->configFilePath)) {
            unlink($this->configFilePath);
        }
    }

    public function testConfigLoadSuccess()
    {
        $appConfig = new AppConfig($this->configFilePath);

        $this->assertNotNull($appConfig->get('database'));
        $this->assertEquals('localhost', $appConfig->get('database.host'));
        $this->assertEquals(3306, $appConfig->get('database.port'));
        $this->assertEquals('test_user', $appConfig->get('database.username'));
        $this->assertEquals('secret', $appConfig->get('database.password'));

        $this->assertEquals('https://api.example.com', $appConfig->get('api.endpoint'));
        $this->assertEquals(30, $appConfig->get('api.timeout'));
        
        $this->assertTrue($appConfig->get('feature_flags.new_dashboard'));
    }

    public function testConfigMissingKeyReturnsNull()
    {
        $appConfig = new AppConfig($this->configFilePath);
        
        $this->assertNull($appConfig->get('non_existent_key'));
        $this->assertNull($appConfig->get('database.non_existent_sub_key'));
    }

    public function testConfigFileNotFoundThrowsException()
    {
        $this->expectException(Exception::class);
        new AppConfig(__DIR__ . '/../non_existent_file.json');
    }

    public function testInvalidJsonThrowsException()
    {
        $invalidJsonPath = __DIR__ . '/../invalid_test.json';
        file_put_contents($invalidJsonPath, '{invalid_json'); // intentionally malformed JSON

        $this->expectException(Exception::class);
        $this->expectExceptionMessageMatches('/Error decoding JSON/');

        try {
            new AppConfig($invalidJsonPath);
        } finally {
            if (file_exists($invalidJsonPath)) {
                unlink($invalidJsonPath);
            }
        }
    }
}
