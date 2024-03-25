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
    // Start the session to use session variables
    session_start();

    // Check if the UUID is stored in the session
    if (isset($_SESSION['currentDataUuid']) && !empty($_SESSION['currentDataUuid'])) {
        // Sanitize the UUID to prevent XSS attacks
        $uuidFromSession = htmlspecialchars($_SESSION['currentDataUuid']);
        echo "UUID from URL: " . $uuidFromSession . "<br>";
    } else {
        echo "No UUID provided.";
    }

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['textEditorContent'])) {
        // Sanitize the input to prevent XSS attacks
        $textEditorContent = htmlspecialchars($_POST['textEditorContent']);
    }
    ?>

    <!-- Text Editor Form -->
    <form method="post">
        <label for="textEditor">Text Editor:</label><br>
        <textarea id="textEditor" name="textEditorContent" rows="10" cols="50">
        </textarea><br>
        <input type="submit" value="Submit">
    </form>


    <script>
        // Key to store the data under in localStorage
        const localStorageKey = 'textEditorContent';

        // Function to save textarea content to localStorage
        function saveToLocalStorage() {
            const editorContent = document.getElementById('textEditor').value;
            localStorage.setItem(localStorageKey, editorContent);
            // console.log('Content saved to localStorage:', editorContent);
        }

        // Load saved content from localStorage if available
        window.onload = function() {
            const savedContent = localStorage.getItem(localStorageKey);
            if (savedContent) {
                document.getElementById('textEditor').value = savedContent;
            }

            // Save the textarea content to localStorage every 5 seconds
            setInterval(saveToLocalStorage, 5000);
        };
    </script>
</body>

</html>