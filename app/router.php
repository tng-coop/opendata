<?php

use Ramsey\Uuid\Uuid;
function goToTop()
{
    global $appConfig;
    header('Location: ' . $appConfig->get('url.base') . $appConfig->get('url.root'));
    exit;
}

$validTime = time() + (365 * 24 * 60 * 60);


// Check if the URI is /hello and include hello.php
if ($uri === $appConfig->get('url.root') . 'umbrella-labels') {
    require 'umbrella-labels.php';
    exit;
}

// Check if the URI contains a UUID in the /umb/<uuid> format
if (preg_match('/\/umb\/([a-f0-9\-]+)$/', $uri, $matches)) {
    $uuid = $matches[1]; // Extract the UUID string from the matches
    if (!Uuid::isValid($uuid)) {
        echo "Invalid UUID format.";
        exit;
    }
    $uuid = htmlspecialchars($uuid); // Sanitize the UUID
    // Store the UUID in the session
    $_SESSION['umb-uuid'] = $uuid;
    $redirectPath = $appConfig->get('url.base') . $appConfig->get('url.root') . 'umb.php';
    header('Location: ' . $redirectPath);
    exit;
}


// Check if the URI contains a UUID
if (preg_match('/\/uuid\/([a-f0-9\-]+)$/', $uri, $matches)) {
    $uuid = $matches[1]; // Extract the UUID string from the matches
    if (!Uuid::isValid($uuid)) {
        $_SESSION['error'] = 'Invalid UUID format.';
        gotoTop();
    }
    $uuid = htmlspecialchars($uuid); // Sanitize the UUID
    require 'mypage.php'; // Include the editor script and then exit the script
    exit;
}

// Handle UUID generation and redirect if the request method is POST and generate_uuid is set
if ($method === 'POST' && isset($_POST['generate_uuid'])) {
    $uuid = Uuid::uuid4()->toString();
    $_SESSION['generated_uuid'] = $uuid;
    insertOpDataWithUuid($uuid);
    setcookie('persistent_uuid', $uuid, $validTime, '/');
    $redirectPath = $appConfig->get('url.base') . $appConfig->get('url.root') . 'uuid/' . $uuid;
    header('Location: ' . $redirectPath);
    exit;
}
// Handle UUID generation and redirect if the request method is POST and goto_uuid is set
if ($method === 'POST' && isset($_POST['goto_uuid'])) {
    $uuid = trim($_POST['uuid']);

    // if uuid is blank, go to top
    if (empty($uuid)) {
        $_SESSION['error'] = 'UUID was blank.';
        goToTop();
    }
    if (!Uuid::isValid($uuid)) {
        $_SESSION['error'] = 'Invalid UUID format.';
        goToTop();
    }
    // Check if a cookie named 'persistent_uuid' is not set and set it with the current UUID
    if (!isset($_COOKIE['persistent_uuid'])) {
        // Set the cookie to expire in 1 year (365 days)
        setcookie('persistent_uuid', $uuid, $validTime, '/');
        // Note: Modify the expiration time and path as per your requirements
    }

    $redirectPath = $appConfig->get('url.base') . $appConfig->get('url.root') . 'uuid/' . $uuid;
    header('Location: ' . $redirectPath);
    exit;
}
// Handle UUID generation and redirect if the request method is POST and generate_uuid is set
if ($method === 'POST' && isset($_POST['forget_uuid'])) {
    // Clear session data
    session_unset();

    // Destroy the session
    session_destroy();
    // Check if the cookie exists and delete it by setting its expiration to the past
    if (isset($_COOKIE['persistent_uuid'])) {
        unset($_COOKIE['persistent_uuid']);
        // empty value and old timestamp
        setcookie('persistent_uuid', '', time() - 3600, '/');
    }
    // Redirect to the homepage or login page
    header('Location: ' . $appConfig->get('url.base') . $appConfig->get('url.root'));
    exit;
}
// Check if the URI is /umb.php and include umb.php
if ($uri === $appConfig->get('url.root') . 'umb.php') {
    require 'umb.php';
    exit;
}

// at this point, if uri is not / , redirect to / to keep it clean
if ($uri !== $appConfig->get('url.root')) {
    goToTop();
}
require_once 'frontpage.php';
