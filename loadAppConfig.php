<?php
function loadAppConfig() {
    static $config = null;

    if ($config === null) {
        // Define the path to the configuration file relative to this script
        $configPath = __DIR__ . '/app.json';
        
        if (!file_exists($configPath)) {
            throw new Exception("Configuration file not found: {$configPath}");
        }

        $configContent = file_get_contents($configPath);
        $config = json_decode($configContent, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Error decoding JSON from configuration file: " . json_last_error_msg());
        }

        // Optionally, add any additional processing or validation of $config here
    }

    return $config;
}
