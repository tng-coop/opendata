<?php
require_once 'loadAppConfig.php';

class DatabaseConnector
{
    private $dsn;
    private $user;
    private $pass;
    private $pdo; // Add a property to hold the PDO instance
    private $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    public function __construct()
    {
        // Adjusted the path for app.json assuming it's at the project root
        global $appConfig;
        // Retrieve the port from configuration or default to 5432 if not set
        $databasePort = $appConfig->get('database.port') ? $appConfig->get('database.port') : 5432;

        $this->dsn = sprintf(
            "pgsql:host=%s;port=%d;dbname=%s",
            $appConfig->get('database.host'),
            $databasePort,
            $appConfig->get('database.dbname')
        );

        // Retrieve the username and password from the configuration
        $this->user = $appConfig->get('database.user'); // Set the username from config
        $this->pass = $appConfig->get('database.pass'); // Set the password from config
    }

    public function getConnection()
    {
        if ($this->pdo === null) {
            try {
                echo "Connecting to DSN: {$this->dsn}\n"; // Debug line
                $this->pdo = new PDO($this->dsn, $this->user, $this->pass, $this->options);
            } catch (PDOException $e) {
                throw new PDOException($e->getMessage(), (int)$e->getCode());
            }
        }
        return $this->pdo;
    }
}
