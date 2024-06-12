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
    // Function to check if the user is on a smartphone
    function isSmartphone() {
        return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
    }

    var mymap = L.map('mapid', {
        center: [35.1509, 139.1237],
        zoom: 13,
        scrollWheelZoom: false, // Disable scroll wheel zoom by default
        dragging: !isSmartphone() // Disable dragging only for smartphones
    });

    $latitude = "<?= $_GET['latitude'] ?>";
    $longitude = "<?= $_GET['longitude'] ?>";
    <?php
    // Use the fetchLatestBBS function to get the data
    $coordinates = fetchValidGpsData();
    // Loop through the coordinates to place markers
    // Loop through the coordinates to place markers
    function processCoordinate($latitude, $longitude)
    {
        $uuid = $_SESSION['umb-uuid'];
        global $appConfig;
        $url = $appConfig->get('url.base') . $appConfig->get('url.root') . 'uuid/' . $uuid;
        $la = $latitude;
        $lo = $longitude;
        $encodedUrl = json_encode($url);
        $name='current'
        ?>
        var marker = L.marker([<?= json_encode($la) ?>, <?= json_encode($lo) ?>]).addTo(mymap);
        marker.bindPopup('<a href=<?= $encodedUrl ?> target="_blank"><?= $name ?></a>');
        <?php
    }
    foreach ($coordinates as $coordinate) {
        processCoordinate($coordinate);
    }
    require_once('map-script.js'); ?>
</script>