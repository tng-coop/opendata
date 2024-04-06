<div id="mapid"></div>
<style>
    #mapid {
        -webkit-user-select: none;
        /* Safari */
        -moz-user-select: none;
        /* Firefox */
        -ms-user-select: none;
        /* Internet Explorer/Edge */
        user-select: none;
        /* Non-prefixed version, currently supported by Chrome, Opera, and Edge */
    }
</style>
<script>
    // Initialize the map and set its view to our chosen geographical coordinates and a zoom level
    var mymap = L.map('mapid', {
        center: [35.1509, 139.1237],
        zoom: 13,
        scrollWheelZoom: false, // Disable scroll wheel zoom by default
        dragging: false // Start with dragging disabled to allow page scrolling
    });
    latitudeOriginal = "<?= $latitude ?>";
    longitudeOriginal = "<?= $longitude ?>";
    <?php
    // Use the fetchLatestBBS function to get the data
    $coordinates = fetchValidGpsData();
    // Loop through the coordinates to place markers
    // Loop through the coordinates to place markers
    function processCoordinate($coordinate) {
        $uuid = $coordinate['id'];
        global $appConfig;
        $url = $appConfig->get('url.base') . $appConfig->get('url.root') . 'uuid/' . $uuid;
        $la= $coordinate['latitude'];
        $lo = $coordinate['longitude'];
        $encodedUrl = json_encode($url);
        echo "var marker = L.marker([\"$la\", \"$lo\"]).addTo(mymap);";
        echo "marker.bindPopup('<a href=\"$encodedUrl\" target=\"_blank\">{$coordinate['name']}</a>');";
    }
    foreach ($coordinates as $coordinate) {
        processCoordinate($coordinate);
    }
    require_once('map-script.js'); ?>
</script>