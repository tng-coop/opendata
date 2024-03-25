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
    // Include or require the PHP file that contains the fetchJsonForUuid function here
    include 'path_to_your_php_file.php'; // Adjust this path as needed

    session_start();

    $data = null;
    $uuidFromSession = '';

    // Check if the UUID is stored in the session
    if (isset($_SESSION['currentDataUuid']) && !empty($_SESSION['currentDataUuid'])) {
        $uuidFromSession = htmlspecialchars($_SESSION['currentDataUuid']); // Sanitize the UUID to prevent XSS attacks
        echo "UUID from URL: " . $uuidFromSession . "<br>";
        // Fetch the initial JSON and last_update using the function
        $data = fetchJsonForUuid($uuidFromSession);
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
            localStorage.setItem(localStorageKey, content);
            localStorage.setItem(lastUpdateKey, lastUpdate);
        }

        window.onload = function() {
            const lastUpdateFromDb = '<?php echo $data ? $data['last_update'] : ''; ?>';
            const lastUpdateFromLocalStorage = localStorage.getItem(lastUpdateKey);
            const contentFromDb = '<?php echo $data ? addslashes($data['json']) : ''; ?>'; // addslashes to escape any single quotes in the JSON string

            if (lastUpdateFromDb && (lastUpdateFromDb !== lastUpdateFromLocalStorage)) {
                // If last_update from DB is different than what's in localStorage, use DB data and update localStorage
                document.getElementById('textEditor').value = contentFromDb;
                saveToLocalStorage(contentFromDb, lastUpdateFromDb);
            } else {
                // Else, load content from localStorage if available
                const savedContent = localStorage.getItem(localStorageKey);
                if (savedContent) {
                    document.getElementById('textEditor').value = savedContent;
                }
            }

            // Save the textarea content to localStorage every 5 seconds
            setInterval(function() {
                const editorContent = document.getElementById('textEditor').value;
                saveToLocalStorage(editorContent, lastUpdateFromDb || lastUpdateFromLocalStorage);
            }, 5000);
        };
    </script>
</body>
</html>
