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

    // Check if the UUID is stored in the session
    if (isset($_SESSION['currentDataUuid']) && !empty($_SESSION['currentDataUuid'])) {
        $uuidFromSession = htmlspecialchars($_SESSION['currentDataUuid']); // Sanitize the UUID to prevent XSS attacks
        // validate uuid using ramsey
        if (!Ramsey\Uuid\Uuid::isValid($uuidFromSession)) {
            echo "Invalid UUID provided.";
            exit;
        }


        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Retrieve the submitted text content
            $submittedText = $_POST['textEditorContent'] ?? '';
            appendOpDataWithUuid($uuidFromSession, ['text' => $submittedText]);
        
            // Assuming appendOpDataWithUuid() processes the form submission without errors
        
            // Construct the redirect path including the UUID
            $scriptPathDir = dirname($_SERVER['PHP_SELF']); // Get the directory of the current script
            $redirectPath = rtrim($scriptPathDir, '/') . '/uuid/' . $uuidFromSession; // Construct the redirect URL
            
            // Redirect to the constructed URL to prevent form re-submission on refresh
            header('Location: ' . $redirectPath);
            exit; // Important: stop further script execution
        }
        echo "UUID from URL: " . $uuidFromSession . "<br>";
        // Fetch the initial JSON and last_update using the function
        $result = fetchJsonForUuid($uuidFromSession);

        if ($result && !empty($result['json'])) {
            $textToDisplay = getLastElementText($result['json']); // Pass the JSON string from the 'json' key to the function
        }
    } else {
        echo "No UUID provided.";
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


    <script>
        const localStorageKey = 'textEditorContent';
        const lastUpdateKey = 'lastUpdate';
        let originalHash = ''; // Initialize variable to store the original hash of the content

        function saveToLocalStorage(uuid, content, lastUpdate) {
            console.log('Saving content to localStorage');
            localStorage.setItem(localStorageKey, content);
            localStorage.setItem(lastUpdateKey, JSON.stringify({
                [uuid]: lastUpdate
            }));
        }

        function base64DecodeUtf8(str) {
            return decodeURIComponent(Array.prototype.map.call(atob(str), (c) => {
                return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
            }).join(''));
        }

        function simpleHash(text) {
            let hash = 0;
            for (let i = 0; i < text.length; i++) {
                const character = text.charCodeAt(i);
                hash = ((hash << 5) - hash) + character;
                hash = hash & hash; // Convert to 32bit integer
            }
            return hash;
        }

        window.onload = function() {
            const lastUpdateFromDb = '<?php echo $result ? $result['last_update'] : ''; ?>';
            const xxx = JSON.parse(localStorage.getItem(lastUpdateKey));
            const lastUpdateFromLocalStorage = xxx?.['<?php echo $uuidFromSession ?>'];
            const contentFromDb = base64DecodeUtf8('<?php echo base64_encode($textToDisplay); ?>');
            const indicatorCircle = document.querySelector('#indicator svg circle');
            const indicatorText = document.getElementById('indicatorText');

            // Hide the element before the assignment
            document.getElementById('textEditor').style.display = 'none';

            document.getElementById('textEditor').value = contentFromDb;
            originalHash = simpleHash(document.getElementById('textEditor').value); // Compute the original hash when content is loaded
            console.log(lastUpdateFromDb);
            console.log(lastUpdateFromLocalStorage);
            if (lastUpdateFromLocalStorage && (new Date(lastUpdateFromDb) > new Date(lastUpdateFromLocalStorage))) {
                console.log('Updating localStorage')
                document.getElementById('textEditor').value = contentFromDb;
                saveToLocalStorage('<?php echo $uuidFromSession ?>', contentFromDb, lastUpdateFromDb);
            } else if (lastUpdateFromLocalStorage) {
                console.log('Loading from localStorage')
                const savedContent = localStorage.getItem(localStorageKey);
                if (savedContent) {
                    document.getElementById('textEditor').value = savedContent;
                }
            }
            // Show the element after the assignment
            document.getElementById('textEditor').style.display = ''; // Use 'block', 'inline', etc., if the element had a specific display style initially


            // Function to Get Editor Content and Calculate Hash
            function getEditorContentAndHash() {
                const editorContent = document.getElementById('textEditor').value;
                const currentHash = simpleHash(editorContent);
                return {
                    editorContent,
                    currentHash
                };
            }

            // Function to Update Indicator
            function updateIndicator() {
                const {
                    editorContent,
                    currentHash
                } = getEditorContentAndHash();
                if (originalHash !== currentHash) {
                    // If the hash has changed, update the indicator to red
                    indicatorCircle.setAttribute('fill', 'red');
                    indicatorText.textContent = 'Unsaved changes';
                } else {
                    // If the content is unchanged, keep or reset the indicator to green
                    indicatorCircle.setAttribute('fill', 'green');
                    indicatorText.textContent = 'No changes';
                }
            }

            // Function to Log Information
            // function logInformation(currentHash, originalHash, editorContent, contentFromDb) {
            //     console.log("currentHash: " + currentHash + " originalHash: " + originalHash);
            //     console.log("current content: " + JSON.stringify(editorContent) + " original content: " + JSON.stringify(contentFromDb));
            // }

            // Function to Save Content to Local Storage
            function saveEditorContentToLocalStorage(uuidFromSession, editorContent, lastUpdate) {
                saveToLocalStorage(uuidFromSession, editorContent, lastUpdate);
            }

                // updateIndicator(currentHash, originalHash);
            updateIndicator();
            // Set Interval Call
            setInterval(function() {
                const {
                    editorContent,
                    currentHash
                } = getEditorContentAndHash();

                updateIndicator();
                // logInformation(currentHash, originalHash, editorContent, contentFromDb);
                saveEditorContentToLocalStorage('<?php echo $uuidFromSession ?>', editorContent, lastUpdateFromDb);
            }, 3000);

        };
    </script>

</body>

</html>