<!-- gps-info.php -->
<div id="gps-info" style="margin-top: 20px;">
    <h2>GPS Information</h2>
    <div id="gps-coordinates">
        <h3>Current Location:</h3>
        <p id="latitude">Latitude: </p>
        <p id="longitude">Longitude: </p>
    </div>
</div>

<script>
    function displayGPSInfo() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition);
        } else {
            document.getElementById('latitude').textContent = "Geolocation is not supported by this browser.";
            document.getElementById('longitude').textContent = "";
        }
    }

    function showPosition(position) {
        document.getElementById('latitude').textContent = "Latitude: " + position.coords.latitude;
        document.getElementById('longitude').textContent = "Longitude: " + position.coords.longitude;
    }

    document.addEventListener('DOMContentLoaded', displayGPSInfo);
</script>
