<?php
if (isset($_GET['longitude']) && isset($_GET['latitude'])) {
    $longitude = $_GET['longitude'];
    $latitude = $_GET['latitude'];
    $uuid = $_SESSION['umb-uuid'];
    appendOpDataWithUuid($uuid, [
        'umb-gps' => [
            'longitude' => $longitude,
            'latitude' => $latitude
        ]
    ]);

    echo "Longitude: " . htmlspecialchars($longitude) . "<br>";
    echo "Latitude: " . htmlspecialchars($latitude);
} else {
    echo "Longitude and latitude parameters are missing.";
}