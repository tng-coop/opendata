<?php
// Database connection parameters
$host = 'localhost';
// show unix user name for the current user
$db   = 'tng';
$user = get_current_user();
$pass = 'tng123'; // Replace 'your_password' with the actual password

// Data Source Name (DSN)
$dsn = "pgsql:host=$host;dbname=$db";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    // Create a PDO instance (connect to the database)
    $pdo = new PDO($dsn, $user, $pass, $options);

    // SQL query
    $sql = 'SELECT count(*) FROM opendata;';

    // Execute the query and fetch the results
    $stmt = $pdo->query($sql);
    while ($row = $stmt->fetch()) {
        // Output the results, for example, as JSON
        echo json_encode($row) . PHP_EOL;
    }
} catch (\PDOException $e) {
    // Handle error
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
?>
