<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editor Page</title>
    <!-- Add any additional head content here (e.g., CSS links) -->
    <style>
        .led-indicator {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background-color: gray;
            position: fixed;
            /* Or adjust based on your layout */
            top: 20px;
            /* Adjust as needed */
            right: 20px;
            /* Adjust as needed */
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
            transition: background-color 0.3s;
        }
    </style>


</head>

<body>
    <?php
    $textToDisplay = ""; // Variable to hold the text to be displayed in the textarea
    if ($method == "POST") {
        // Retrieve the submitted text content
        $submittedText = $_POST['textEditorContent'] ?? '';
        $submittedName = $_POST['name'] ?? '';
        $submittedDistrict = $_POST['district'] ?? '';
        $submittedLatitude = $_POST['latitude'] ?? '';
        $submittedLongitude = $_POST['longitude'] ?? '';

        // Combine latitude and longitude into a GPS JSON object
        $gpsCoordinates = new stdClass(); // Create a standard class object
        $gpsCoordinates->latitude = $submittedLatitude;
        $gpsCoordinates->longitude = $submittedLongitude;

        // Now, include the GPS JSON string along with other attributes
        appendOpDataWithUuid($uuid, [
            'text' => $submittedText,
            'name' => $submittedName,
            'district' => $submittedDistrict,
            'gps' => $gpsCoordinates // Include the GPS coordinates as a JSON string
        ]);

        // Assuming appendOpDataWithUuid() processes the form submission without errors
        // Construct the redirect path including the UUID
        $redirectPath = $appConfig->get('url.base') . $appConfig->get('url.root') . 'uuid/' . $uuid; // Construct the redirect URL

        // Redirect to the constructed URL to prevent form re-submission on refresh
        header('Location: ' . $redirectPath);
        exit; // Important: stop further script execution
    }
    echo "My Page: " . $uuid . "<br>";
    // Fetch the initial JSON and last_update using the function
    $result = fetchJsonForUuid($uuid);

    if ($result && !empty($result['json'])) {
        $textToDisplay = getLastElement($result['json'], 'text'); // Pass the JSON string from the 'json' key to the function
        $name = getLastElement($result['json'], 'name');
        $district = getLastElement($result['json'], 'district');
        $gps = getLastElement($result['json'], 'gps');
        $longitude=$gps['longitude'];
        $latitude=$gps['latitude'];
    }

    // Handling form submission is not modified, assuming it's processed server-side as before
    ?>
    <?php
    require('editor-script.php');
    require('streetmap-url-script.php');
    ?>
    <form method="post">
        <?php
        require 'name-input.php';
        require 'district-input.php';
        require 'streetmap-url-input.php';
        require 'editor.php'; // Include the editor script and then exit the script
        ?>
        <input type="submit" value="Submit">
    </form>
    <!-- Go to Top Link -->
    <div style="text-align: center; margin-top: 20px;">
        <a href="./../" style="text-decoration: none; font-size: 16px;">Go to Top</a>
    </div>
</body>

</html>