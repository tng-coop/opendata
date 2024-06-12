<?php 
// Display the UUID from the session
echo 'UUID: ' . htmlspecialchars($_SESSION['umb-uuid']) . '<br>';

// Display all GET parameters
if (!empty($_GET)) {
    echo 'GET Parameters:<br>';
    foreach ($_GET as $key => $value) {
        echo htmlspecialchars($key) . ': ' . htmlspecialchars($value) . '<br>';
    }
} else {
    echo 'No GET parameters passed.';
}