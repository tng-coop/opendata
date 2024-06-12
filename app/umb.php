<?php
// Get the UUID from the session
$uuid = $_SESSION['umb-uuid'];

// Check if the UUID is valid
if (!empty($uuid) && Ramsey\Uuid\Uuid::isValid($uuid)) {
    createEntityIfNotExists($uuid);
} else {
    echo "<h1>Invalid or missing UUID</h1>";
    exit;
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
    </script>

    <div id="mapid"></div>
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
            } catch (error) {
                console.error('Error fetching content:', error);
                document.getElementById('contentBox').innerHTML = 'Error fetching content.';
                // also show error
                document.getElementById('contentBox').innerHTML += `<p>${error}</p>`;
            }
        };


        document.addEventListener('DOMContentLoaded', displayGPSInfo);
    </script>
</body>