<?php
// Get the HTTP host (domain name) and the request URI to construct the full URL
$host = $_SERVER['HTTP_HOST'];
$uri = $_SERVER['REQUEST_URI'];

// Construct the full URL
$fullURL = 'http://' . $host . $uri;

// Display the URL
echo 'Current URL: ' . $fullURL;
?>
