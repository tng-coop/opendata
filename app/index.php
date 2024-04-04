<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Application's Title</title>
        <!-- Leaflet CSS -->
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
              integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
              crossorigin=""/>
    
    <!-- Leaflet JavaScript -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
            crossorigin=""></script>
    <style>
        #mapid { height: 400px; }
    </style>
</head>

<body>
    <?php
    $uri = $_SERVER['REQUEST_URI'];
    $method = $_SERVER['REQUEST_METHOD'];
    require_once 'config.php';
    require('router.php');
    require('error.php');
    require('goto.php');
    require('map.php');
    require('list.php');
    ?>
</body>

</html>