<?php
// Get the UUID from the session
$uuid = $_SESSION['umb-uuid'];

// Check if the UUID is valid
if (!empty($uuid) && Ramsey\Uuid\Uuid::isValid($uuid)) {
    echo "<h1>UUID: $uuid</h1>";
} else {
    echo $_SESSION;
    echo "<h1>Invalid or missing UUID</h1>";
}

// Include any additional HTML or logic needed for your page
?>
