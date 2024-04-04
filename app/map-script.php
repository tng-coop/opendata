    <script>
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
    </script>