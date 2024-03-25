<?php
use Ramsey\Uuid\Uuid;

function generateAndRedirect($scriptPathDir) {
    try {
        $uuid4 = Uuid::uuid4();
        $_SESSION['generated_uuid'] = $uuid4->toString();
        header('Location: ' . $scriptPathDir . '/uuid/' . $uuid4->toString());
        exit;
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        exit;
    }
}

function displayUUIDFromSession() {
    if (isset($_SESSION['generated_uuid'])) {
        echo "Generated UUID: " . $_SESSION['generated_uuid'] . "<br>";
        unset($_SESSION['generated_uuid']);
    }
}

function displayUUIDFromUrl($uuidFromUrl) {
    echo "UUID from URL: " . htmlspecialchars($uuidFromUrl) . "<br>";
}
