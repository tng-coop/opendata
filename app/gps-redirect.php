<!-- gps-info.php -->
<div id="gps-info" style="margin-top: 20px;">
    <h2>GPS Information</h2>
    <div id="gps-coordinates">
    </div>
    <div id="fetched-content">
        <h3>Fetched Content:</h3>
        <div id="contentBox"></div>
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