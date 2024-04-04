
    <script>
        const localStorageKey = 'textEditorContent';
        const lastUpdateKey = 'lastUpdate';
        let originalHash = ''; // Initialize variable to store the original hash of the content

        function saveToLocalStorage(uuid, content, lastUpdate) {
            // console.log('Saving content to localStorage');
            localStorage.setItem(localStorageKey, content);
            localStorage.setItem(lastUpdateKey, JSON.stringify({
                ['<?php echo $uuid ?>']: lastUpdate
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
            // Retrieving the last update date from the database or an empty string if not available
            const lastUpdateFromDb = '<?php echo $result ? $result['last_update'] : ''; ?>';
            console.log('<?php echo $uuid ?>');
            console.log(localStorage.getItem(lastUpdateKey))

            // Accessing local storage to retrieve the last update date for the specific UUID
            const lastUpdateFromLocalStorage = JSON.parse(localStorage.getItem(lastUpdateKey))?.['<?php echo $uuid ?>'];

            // Decoding the base64-encoded content from the database for display
            const contentFromDb = base64DecodeUtf8('<?php echo base64_encode($textToDisplay); ?>');

            // Selecting the indicator circle element within the SVG in the DOM
            const indicatorCircle = document.querySelector('#indicator svg circle');

            // Getting the indicator text element by its ID
            const indicatorText = document.getElementById('indicatorText');


            // Cache the element for re-use
            const textEditor = document.getElementById('textEditor');

            // Hide the text editor initially
            textEditor.style.display = 'none';

            // Load initial content from the database
            textEditor.value = contentFromDb;
            originalHash = simpleHash(textEditor.value); // Update the hash based on the current value

            // Determine if the local storage needs to be updated or is used for loading content
            const lastUpdateFromLocalStorageDate = lastUpdateFromLocalStorage ? new Date(lastUpdateFromLocalStorage) : null;
            const lastUpdateFromDbDate = new Date(lastUpdateFromDb);

            // Check if local storage should be updated
            if (lastUpdateFromDbDate > lastUpdateFromLocalStorageDate) {
                saveToLocalStorage('<?php echo $uuid ?>', contentFromDb, lastUpdateFromDb);
            }
            // Check if content should be loaded from local storage
            else if (lastUpdateFromLocalStorage) {
                const savedContent = localStorage.getItem(localStorageKey);
                if (savedContent) {
                    textEditor.value = savedContent;
                }
            }

            // Show the element after the assignment
            textEditor.style.display = ''; // Use 'block', 'inline', etc., if the element had a specific display style initially

            // Function to Update Indicator
            function updateIndicator() {
                if (originalHash === simpleHash(textEditor.value)) {
                    // If the content is unchanged, keep or reset the indicator to green
                    indicatorCircle.setAttribute('fill', 'green');
                    indicatorText.textContent = 'No changes';
                } else {
                    // If the hash has changed, update the indicator to red
                    indicatorCircle.setAttribute('fill', 'red');
                    indicatorText.textContent = 'Unsaved changes';
                }
            }

            updateIndicator();
            // Set Interval Call
            setInterval(function() {
                updateIndicator();
                saveToLocalStorage('<?php echo $uuid ?>', textEditor.value, lastUpdateFromDb);
            }, 3000);

        };
    </script>