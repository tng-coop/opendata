<?php
class AppConfig
{
    private $config = [];

    public function __construct($configFilePath)
    {
        if (!file_exists($configFilePath)) {
            throw new Exception("Configuration file not found: {$configFilePath}");
        }

        $configContent = file_get_contents($configFilePath);
        $this->config = json_decode($configContent, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Error decoding JSON from configuration file: " . json_last_error_msg());
        }
    }

    public function get($key)
    {
        $keys = explode('.', $key);
        $value = $this->config;

        foreach ($keys as $k) {
            if (!isset($value[$k])) {
                return null; // Or throw an Exception if you prefer
            }
            $value = $value[$k];
        }

        return $value;
    }
}

$appConfig = new AppConfig(__DIR__ . '/../app.json');
