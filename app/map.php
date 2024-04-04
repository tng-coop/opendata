<div id="mapid"></div>
<script>
    // Initialize the map and set its view to our chosen geographical coordinates and a zoom level
    var mymap = L.map('mapid').setView([35.1509, 139.1237], 13);

    <?php
    // Use the fetchLatestBBS function to get the data
    $coordinates = fetchValidGpsData();
    // Loop through the coordinates to place markers
    // Loop through the coordinates to place markers
    foreach ($coordinates as $coordinate) {
        // Assuming each $coordinate has a 'uuid' key and $appConfig is correctly initialized
        $uuid = $coordinate['id'];
        $url = $appConfig->get('url.base') . $appConfig->get('url.root') . 'uuid/' . $uuid;
        // Prepare the latitude and longitude for JavaScript.
        $latitude = $coordinate['latitude'];
        $longitude = $coordinate['longitude'];

        // Encode the URL for safe inclusion in JavaScript.
        $encodedUrl = json_encode($url);

        // Use HEREDOC syntax for clarity and to avoid issues with quotes.
    ?>
        latitudeOriginal = <?= $latitude ?>;
        longitudeOriginal = <?= $longitude ?>;
        var marker = L.marker([<?= $latitude ?>, <?= $longitude; ?>]).addTo(mymap);
        marker.bindPopup('<a href="<?= $encodedUrl ?>" target="_blank"><?= $coordinate['name'] ?></a>');
    <?php
        json_encode($coordinate['name']) . " + '</a>');";
    }
    require_once('map-script.js'); ?>
</script>