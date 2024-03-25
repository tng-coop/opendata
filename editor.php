<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editor Page</title>
    <!-- Add any additional head content here (e.g., CSS links) -->
</head>

<body>
    <?php

    error_log("editor.php accessed");
    $textToDisplay = ""; // Variable to hold the text to be displayed in the textarea

    // Check if the UUID is stored in the session
    if (isset($_SESSION['currentDataUuid']) && !empty($_SESSION['currentDataUuid'])) {
        $uuidFromSession = htmlspecialchars($_SESSION['currentDataUuid']); // Sanitize the UUID to prevent XSS attacks
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Retrieve the submitted text content
            $submittedText = $_POST['textEditorContent'] ?? '';
            appendOpDataWithUuid($uuidFromSession, ['text' => $submittedText]);
        }
        echo "UUID from URL: " . $uuidFromSession . "<br>";
        // Fetch the initial JSON and last_update using the function
        $result = fetchJsonForUuid($uuidFromSession);

        if ($result && !empty($result['json'])) {
            // Assuming getLastElementText function is defined and available
            error_log("json: " . json_encode($result['json']));
            $textToDisplay = getLastElementText($result['json']); // Pass the JSON string from the 'json' key to the function
        }
    } else {
        echo "No UUID provided.";
    }

    // Handling form submission is not modified, assuming it's processed server-side as before
    ?>

    <!-- Text Editor Form -->
    <form method="post">
        <label for="textEditor">Text Editor:</label><br>
        <textarea id="textEditor" name="textEditorContent" rows="10" cols="50"></textarea><br>
        <input type="submit" value="Submit">
    </form>

    <script>
        const localStorageKey = 'textEditorContent';
        const lastUpdateKey = 'lastUpdate';

        function saveToLocalStorage(content, lastUpdate) {
            console.log('Saving content to localStorage');
            localStorage.setItem(localStorageKey, content);
            localStorage.setItem(lastUpdateKey, lastUpdate);
        }

        window.onload = function() {
            const lastUpdateFromDb = '<?php echo $result ? $result['last_update'] : ''; ?>';
            const lastUpdateFromLocalStorage = localStorage.getItem(lastUpdateKey);
            const contentFromDb = atob('<?php echo base64_encode($textToDisplay); ?>');
            console.log('<?php echo json_encode($result) ?>');

            console.log('Last update from DB:', new Date(lastUpdateFromDb))
            console.log('Last update from localStorage:', new Date(lastUpdateFromLocalStorage));
            if (new Date(lastUpdateFromDb) > new Date(lastUpdateFromLocalStorage)) {
                console.log('Loading content from DB')
                console.log('Last update from DB:', lastUpdateFromDb)
                console.log('Last update from localStorage:', lastUpdateFromLocalStorage)
                // If last_update from DB is different than what's in localStorage, use DB data and update localStorage
                document.getElementById('textEditor').value = contentFromDb;
                saveToLocalStorage(contentFromDb, lastUpdateFromDb);
            } else {
                console.log('Loading content from localStorage')
                console.log('Last update from DB:', lastUpdateFromDb)
                console.log('Last update from localStorage:', lastUpdateFromLocalStorage)
                // Else, load content from localStorage if available
                const savedContent = localStorage.getItem(localStorageKey);
                if (savedContent) {
                    document.getElementById('textEditor').value = savedContent;
                }
            }

            // Save the textarea content to localStorage every 5 seconds
            setInterval(function() {
                const editorContent = document.getElementById('textEditor').value;
                saveToLocalStorage(editorContent, lastUpdateFromDb);
            }, 5000);
        };
    </script>
</body>

</html>