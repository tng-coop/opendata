<?php
// Get the UUID from the session
$uuid = $_SESSION['umb-uuid'];

// Check if the UUID is valid
if (!empty($uuid) && Ramsey\Uuid\Uuid::isValid($uuid)) {
    createEntityIfNotExists($uuid);
} else {
    echo "<h1>Invalid or missing UUID</h1>";
    exit;
}
require_once('gps-redirect.php');

