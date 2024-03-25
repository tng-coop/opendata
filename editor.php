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
    // Check if the UUID is stored in the session
    if (isset($_SESSION['currentDataUuid']) && !empty($_SESSION['currentDataUuid'])) {
        // Sanitize the UUID to prevent XSS attacks
        $uuidFromSession = htmlspecialchars($_SESSION['currentDataUuid']);
        echo "UUID f3rom URL: " . $uuidFromSession . "<br>";
    } else {
        echo "No UUID provided.";
    }
    ?>
    <!-- The rest of your HTML content goes here -->
    <!-- This could include forms, buttons, or other interactive elements -->
</body>
</html>
