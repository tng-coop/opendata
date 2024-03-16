<?php

class DatabaseConnector {
    private $dsn;
    private $user;
    private $pass;
    private $pdo; // Add a property to hold the PDO instance
    private $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    public function __construct() {
        // Adjusted the path for app.json assuming it's at the project root
        $configPath = __DIR__ . '/app.json'; 
        $config = json_decode(file_get_contents($configPath), true);
        
        if (!$config) {
            throw new Exception("Configuration file not found or invalid JSON.");
        }

        // Ensure that the 'database' key exists and has required sub-keys
        if (!isset($config['database']) || !isset($config['database']['pass'])) {
            throw new Exception("Database configuration is incomplete or missing.");
        }

        // Assuming the 'host' and 'dbname' are also under the 'database' key in the JSON
        $this->dsn = sprintf("pgsql:host=%s;dbname=%s", $config['database']['host'], $config['database']['dbname']);
        $this->user = get_current_user(); // Use the current Unix user
        $this->pass = $config['database']['pass']; // Use the password from the configuration
    }

    public function getConnection() {
        if ($this->pdo === null) {
            try {
                $this->pdo = new PDO($this->dsn, $this->user, $this->pass, $this->options);
            } catch (PDOException $e) {
                // Consider logging this error instead of directly throwing
                throw new PDOException($e->getMessage(), (int)$e->getCode());
            }
        }
        return $this->pdo;
    }
}
