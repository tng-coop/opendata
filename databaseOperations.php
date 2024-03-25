<?php
require 'DatabaseConnector.php';

function fetchOpDataCount() {
    $databaseConnector = new DatabaseConnector();
    $pdo = $databaseConnector->getConnection();
    $sql = 'SELECT count(*) FROM opendata;';
    $stmt = $pdo->query($sql); // For a simple, parameter-less query, query() is sufficient
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}