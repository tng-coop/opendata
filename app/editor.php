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
        appendOpDataWithUuid($uuid, ['text' => $submittedText]);

        // Assuming appendOpDataWithUuid() processes the form submission without errors
        // Construct the redirect path including the UUID
        $redirectPath = $appConfig->get('url.base') . $appConfig->get('url.root') . 'uuid/' . $uuid; // Construct the redirect URL

        // Redirect to the constructed URL to prevent form re-submission on refresh
        header('Location: ' . $redirectPath);
        exit; // Important: stop further script execution
    }
    echo "UUID from URL: " . $uuid . "<br>";
    // Fetch the initial JSON and last_update using the function
    $result = fetchJsonForUuid($uuid);

    if ($result && !empty($result['json'])) {
        $textToDisplay = getLastElementText($result['json']); // Pass the JSON string from the 'json' key to the function
    }

    // Handling form submission is not modified, assuming it's processed server-side as before
    ?>

    <form method="post">
        <label for="textEditor">Text Editor:</label><br>
        <textarea id="textEditor" name="textEditorContent" rows="10" cols="50"></textarea><br>
        <!-- SVG Indicator -->
        <div id="indicator" style="margin-bottom: 10px;">
            <svg width="20" height="20" viewPort="0 0 12 12" version="1.1" xmlns="http://www.w3.org/2000/svg">
                <circle cx="10" cy="10" r="10" fill="green" />
            </svg>
            <span id="indicatorText" style="vertical-align: super;">No changes</span>
        </div>
        <input type="submit" value="Submit">

    </form>
    <!-- Go to Top Link -->
    <div style="text-align: center; margin-top: 20px;">
        <a href="./../" style="text-decoration: none; font-size: 16px;">Go to Top</a>
    </div>
    <?php
    require('editor-script.php');
    ?>
</body>

</html>