    <label for="latitude">Latitude:</label><br>
    <input type="text" id="latitude" name="latitude" placeholder="Enter latitude" value="<?php echo $latitude ?>"><br>
    
    <label for="longitude">Longitude:</label><br>
    <input type="text" id="longitude" name="longitude" placeholder="Enter longitude" value="<?php echo $longitude ?>"><br>
    
    <label for="osmUrl">OpenStreetMap URL (optional):</label><br>
    <input type="text" id="osmUrl" name="osmUrl" placeholder="Paste OSM URL here" onpaste="extractCoordinatesFromUrl(event)">