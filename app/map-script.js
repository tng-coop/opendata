    // Set up the OpenStreetMap layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap contributors'
    }).addTo(mymap);
    
    // Define a custom green icon
    var greenIcon = new L.Icon({
        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
    });

    // Initialize a placeholder marker variable
    var placeholderMarker = null;

    // Long press implementation
    var longPressTimeout;
    var touchDuration = 800; // Duration required to treat touch as long press (in ms)
    function onTouchStart(e) {
        // Start the timer to detect long press
        longPressTimeout = setTimeout(function() {
            onLongPress(e);
        }, touchDuration);
    }

    function onTouchEnd() {
        // If the touch ends before the long press duration, clear the timer
        clearTimeout(longPressTimeout);
    }

    function onLongPress(e) {
        // Prevent firing of the default event
        e.preventDefault();

        var touchEvent = e.touches[0];
        var latlng = mymap.layerPointToLatLng(mymap.containerPointToLayerPoint([touchEvent.pageX, touchEvent.pageY]));

        var lat = latlng.lat.toFixed(6);
        var lng = latlng.lng.toFixed(6);

        // Update the input fields with the latitude and longitude values
        document.getElementById('latitude').value = lat;
        document.getElementById('longitude').value = lng;

        // Update or create the placeholder marker at the long press location
        updateOrCreateMarker(latlng);
    }

    function updateOrCreateMarker(latlng) {
        if (placeholderMarker) {
            // Move the existing marker
            placeholderMarker.setLatLng(latlng);
        } else {
            // Create a new marker and add it to the map
            placeholderMarker = L.marker(latlng, {
                icon: greenIcon
            }).addTo(mymap);
        }
    }


    // Add event listeners for touch start and end to implement long press
    mymap.getContainer().addEventListener('touchstart', onTouchStart, false);
    mymap.getContainer().addEventListener('touchend', onTouchEnd, false);

    // Existing right-click event listener remains for non-touch devices
    mymap.on('contextmenu', function(e) {
        // Check if both latitude and longitude input elements exist
        var latInput = document.getElementById('latitude');
        var lngInput = document.getElementById('longitude');
        if (!latInput || !lngInput) {
            // If either input is not found, return early from the function
            return;
        }
        // Update the input fields with the latitude and longitude values
        document.getElementById('latitude').value = latInput;
        document.getElementById('longitude').value = lngInput;

        // Update or create the placeholder marker at the right-click location
        updateOrCreateMarker(e.latlng);
    });