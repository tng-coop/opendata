<script>
    function extractCoordinatesFromUrl(event) {
        // Access the pasted text directly from the clipboard event
        const pastedText = (event.clipboardData || window.clipboardData).getData('text');

        // Decode the URI component to handle any URL encoding such as %2C for commas
        const decodedUrl = decodeURIComponent(pastedText);

        // Updated regex to accurately match the latitude and longitude in the URL
        const regex = /query=([\d.-]+),([\d.-]+)/;
        const match = decodedUrl.match(regex);

        if (match && match.length >= 3) {
            // Assign extracted values to the input fields
            document.getElementById('latitude').value = match[1];
            document.getElementById('longitude').value = match[2];
        } else {
            alert('No coordinates found in the URL. Please check the format and try again.');
        }
        // Optionally, prevent the default paste action
        event.preventDefault();
    }
</script>