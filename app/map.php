<div id="mapid"></div>
<script>
// Initialize the map and set its view to our chosen geographical coordinates and a zoom level:
var mymap = L.map('mapid').setView([35.1509, 139.1237], 13); // Central point between the two new locations

// Set up the OpenStreetMap layer
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '© OpenStreetMap contributors'
}).addTo(mymap);

<?php
// Your new GPS coordinates array
$coordinates = [
    ['name' => 'Location 1', 'lat' => 35.1476, 'lon' => 139.1124],
    ['name' => 'Location 2', 'lat' => 35.1542, 'lon' => 139.1350],
    // Add more coordinates as needed
];

// Loop through the coordinates to place markers
foreach ($coordinates as $coordinate) {
    echo "L.marker([{$coordinate['lat']}, {$coordinate['lon']}]).addTo(mymap)
         .bindPopup('{$coordinate['name']}').openPopup();";
}
?>
</script>
