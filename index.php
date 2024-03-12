<?php
// Get the HTTP host (domain name) and the request URI to construct the full URL
$host = $_SERVER['HTTP_HOST'];
$uri = $_SERVER['REQUEST_URI'];

// Construct the full URL
$fullURL = 'http://' . $host . $uri;

// Display the URL
echo 'Current URL: ' . $fullURL;

// Load the JSON configuration data
$configJson = file_get_contents('app.json'); // Specify the correct path to your app.json file
$config = json_decode($configJson, true); // Decode the JSON into an associative array

// Access the openjson value from the datadir section
$openjson = $config['datadir']['openjson'];

// Display the openjson value
echo 'OpenJSON: ' . $openjson;
