<?php
// Get the UUID from the session
$uuid = $_SESSION['umb-uuid'];

// Check if the UUID is valid
if (!empty($uuid) && Ramsey\Uuid\Uuid::isValid($uuid)) {
    createEntityIfNotExists($uuid);
    $oldPoints = fetchLatestPointsForUuid($uuid);
} else {
    echo "<h1>Invalid or missing UUID</h1>";
    exit;
}
// Function to process each coordinate
function processCoordinate($coordinate)
{
    $latitude = $coordinate['umb-gps']['latitude'];
    $longitude = $coordinate['umb-gps']['longitude'];
    global $appConfig;
    $baseURL = $appConfig->get('url.base') . $appConfig->get('url.root');
    $encodedURL = json_encode($baseURL);
    ?>
    var marker = L.marker([<?= json_encode($latitude) ?>, <?= json_encode($longitude) ?>]).addTo(mymap);
    marker.bindPopup('<a href=<?= $encodedURL ?> target="_blank">GPS Coordinate</a>');
    <?php
}

// Iterate over the data and process each coordinate
foreach ($data as $coordinate) {
    processCoordinate($coordinate);
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Application's Title</title>
    <?php
    require_once ('leaflet.php');
    ?>
</head>

<body>
    <div id="mapid"></div>
    <script>
        const oldPoints = <?php echo json_encode($oldPoints); ?>;
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
    </script>

    <div id="gps-info" style="margin-top: 20px;">
        <div id="gps-coordinates">
        </div>
        <div id="contentBox"></div>
    </div>

    <script>
        const displayGPSInfo = () => {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition);
            } else {
                console.log('Geolocation is not supported by this browser.');
            }
        };

        const showPosition = async (position) => {
            const { latitude, longitude } = position.coords;
            try {
                // Retrieve the UUID from the PHP session variable
                const baseURL = '<?php echo $appConfig->get('url.base') . $appConfig->get('url.root') . 'umb-gps.php'; ?>';
                const urlWithParams = `${baseURL}?latitude=${latitude}&longitude=${longitude}`;

                // Perform the fetch request with the updated URL
                const response = await fetch(urlWithParams, {
                    method: 'GET',
                });
                const data = await response.text();
                document.getElementById('contentBox').innerHTML = data;
                // Add user's current position marker to the map
                var userMarker = L.marker([latitude, longitude]).addTo(mymap);
                userMarker.bindPopup(`Your current position: (${latitude}, ${longitude})`).openPopup();
                // Use Leaflet Routing Machine to create a route that follows roads
                L.Routing.control({
                    waypoints: [
                        L.latLng(latitude, longitude),
                        L.latLng(35.156233, 139.132804)
                    ],
                    routeWhileDragging: true,
                    createMarker: function (i, waypoint, n) {
                        var markerOptions = {};
                        if (i === n - 1) {
                            markerOptions.icon = redIcon;
                        }
                        return L.marker(waypoint.latLng, markerOptions);
                    }
                }).addTo(mymap);



            } catch (error) {
                console.error('Error fetching content:', error);
                document.getElementById('contentBox').innerHTML = 'Error fetching content.';
                // also show error
                document.getElementById('contentBox').innerHTML += `<p>${error}</p>`;
            }
        };


        document.addEventListener('DOMContentLoaded', displayGPSInfo);


        // Function to add markers from PHP data
        const addMarkersFromData = () => {
            oldPoints.forEach(coordinate => {
                var marker = L.marker([coordinate['umb-gps']['latitude'], coordinate['umb-gps']['longitude']]).addTo(mymap);
                marker.bindPopup('<a href="<?php echo json_encode($baseURL); ?>" target="_blank">GPS Coordinate</a>');
            });

            // Define a custom red icon
            var redIcon = new L.Icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            });

            // Adding a specific marker with the red icon
            var specificMarker = L.marker([35.156233, 139.132804], { icon: redIcon }).addTo(mymap);
            specificMarker.bindPopup('Return Umbrella Here!');
        };

        document.addEventListener('DOMContentLoaded', addMarkersFromData);
        <?php require_once ('map-script.js'); ?>
    </script>
</body>