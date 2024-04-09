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

        $this->user = get_current_user(); // Use the current Unix user
        $this->pass = $appConfig->get('database.pass'); // Use the password from the configuration
    }

    public function getConnection()
    {
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
