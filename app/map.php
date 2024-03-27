<div id="mapid"></div>
<?php
// Use the fetchLatestBBS function to get the data
$coordinates = fetchValidGpsData();
print_r($gpsCoordinates);
echo "<br>";
print_r($coordinates);
?>
<script>
// Initialize the map and set its view to our chosen geographical coordinates and a zoom level:
var mymap = L.map('mapid').setView([35.1509, 139.1237], 13); // Central point between the two new locations

// Set up the OpenStreetMap layer
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '© OpenStreetMap contributors'
}).addTo(mymap);

<?php


// Loop through the coordinates to place markers
foreach ($coordinates as $coordinate) {
    echo "L.marker([{$coordinate['latitude']}, {$coordinate['longitude']}]).addTo(mymap)
         .bindPopup('{$coordinate['name']}').openPopup();";
}
?>
</script>
