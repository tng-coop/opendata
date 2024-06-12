<!-- gps-info.php -->
<div id="gps-info" style="margin-top: 20px;">
    <h2>GPS Information</h2>
    <div id="gps-coordinates">
        <h3>Current Location:</h3>
        <p id="latitudeGPS">Latitude: </p>
        <p id="longitudeGPS">Longitude: </p>
    </div>
    <div id="fetched-content">
        <h3>Fetched Content:</h3>
        <textarea id="contentBox" rows="10" cols="50"></textarea>
    </div>
</div>

<script>
    const displayGPSInfo = () => {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition);
        } else {
            document.getElementById('latitudeGPS').textContent = "Geolocation is not supported by this browser.";
            document.getElementById('longitudeGPS').textContent = "";
        }
    };

    const showPosition = async (position) => {
        const { latitude, longitude } = position.coords;
        document.getElementById('latitudeGPS').textContent = `Latitude: ${latitude}`;
        document.getElementById('longitudeGPS').textContent = `Longitude: ${longitude}`;

        try {
            const response = await fetch('https://microsoft.com');
            const data = await response.text();
            document.getElementById('contentBox').value = data;
        } catch (error) {
            console.error('Error fetching content:', error);
            document.getElementById('contentBox').value = 'Error fetching content.';
        }
    };

    document.addEventListener('DOMContentLoaded', displayGPSInfo);
</script>
