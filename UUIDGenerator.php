<?php
use Ramsey\Uuid\Uuid;

function generateAndRedirect($scriptPathDir) {
    try {
        $uuid4 = Uuid::uuid4();
        $_SESSION['generated_uuid'] = $uuid4->toString();
       // Ensure there's no double slash if scriptPathDir is root ('/')
       $redirectPath = rtrim($scriptPathDir, '/') . '/uuid/' . $uuid4->toString();
       header('Location: ' . $redirectPath);
        exit;
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        exit;
    }
}


function showEditor($uuidFromUrl) {
    echo "UUID from URL: " . htmlspecialchars($uuidFromUrl) . "<br>";
}