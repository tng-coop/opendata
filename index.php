<?php
require 'config.php';
require 'UUIDGenerator.php';
require 'databaseOperations.php';

use Ramsey\Uuid\Uuid;

$scriptPathDir = dirname($_SERVER['PHP_SELF']);
$uri = $_SERVER['REQUEST_URI'];

// Check if the URI contains a UUID
if (preg_match('/\/uuid\/([a-f0-9\-]+)$/', $uri, $matches)) {
    $uuidString = $matches[1]; // Extract the UUID string from the matches
    if (!Uuid::isValid($uuidString)) {
        throw new Exception("Invalid UUID format.");
    }
    $sanitizedUuid = htmlspecialchars($uuidString); // Sanitize the UUID
    $_SESSION['currentDataUuid'] = $sanitizedUuid;
    require 'editor.php'; // Include the editor script and then exit the script
    exit;
}

// Handle UUID generation and redirect if the request method is POST and generate_uuid is set
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['generate_uuid'])) {
    $uuid4 = Uuid::uuid4();
    $_SESSION['generated_uuid'] = $uuid4->toString();
    insertOpDataWithUuid($uuid4);
    setcookie('persistent_uuid', $uuid4, time() + (365 * 24 * 60 * 60), '/');
    $redirectPath = rtrim($scriptPathDir, '/') . '/uuid/' . $uuid4->toString();
    header('Location: ' . $redirectPath);
    exit;
}

// Handle UUID generation and redirect if the request method is POST and goto_uuid is set
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['goto_uuid'])) {
    $uuid = trim($_POST['uuid']);
    if (!Uuid::isValid($uuid)) {
        throw new Exception("Invalid UUID format.");
    }

    // Check if a cookie named 'persistent_uuid' is not set and set it with the current UUID
    if (!isset($_COOKIE['persistent_uuid'])) {
        // Set the cookie to expire in 1 year (365 days)
        setcookie('persistent_uuid', $uuid, time() + (365 * 24 * 60 * 60), '/');
        // Note: Modify the expiration time and path as per your requirements
    }

    $redirectPath = rtrim($scriptPathDir, '/') . '/uuid/' . $uuid;
    header('Location: ' . $redirectPath);
    exit;
}
// Handle UUID generation and redirect if the request method is POST and generate_uuid is set
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['forget_uuid'])) {
    // Clear session data
    session_unset();

    // Destroy the session
    session_destroy();
    // Check if the cookie exists and delete it by setting its expiration to the past
    if (isset($_COOKIE['persistent_uuid'])) {
        unset($_COOKIE['persistent_uuid']);
        // empty value and old timestamp
        setcookie('persistent_uuid', '', time() - 3600, '/');
    }

    // Redirect to the homepage or login page
    header('Location: ./');
    exit;
}

//if session does not exist and cookie does, copy cookie to session
if (!isset($_SESSION['currentDataUuid']) && isset($_COOKIE['persistent_uuid'])) {
    $_SESSION['currentDataUuid'] = $_COOKIE['persistent_uuid'];
}

/// if session uuid exists
if (isset($_SESSION['currentDataUuid']) && !empty($_SESSION['currentDataUuid'])) {
    $uuidFromSession = htmlspecialchars($_SESSION['currentDataUuid']);
?>
    <form method="post">
        <input type="text" name="uuid" value="<?php echo $uuidFromSession; ?>" size="40" readonly>
        <br>
        <input type="submit" name="goto_uuid" value="Go to My ID">
        <br>
        <br>
        <input type="submit" name="forget_uuid" value="Forget My ID">
    </form>
<?php
} else {
?>
    <form method="post">
        <input type="submit" name="generate_uuid" value="Generate UUID">
        <br>
        <br>
        <input type="text" name="uuid" size="40">
        <br>
        <input type="submit" name="goto_uuid" value="Go to My ID">
    </form>
<?php
    // Displaying the latest BBS entries in an Excel-like table
    function displayLatestBBSTable()
    {
        $latestBBS = fetchLatestBBS();

        if (!empty($latestBBS)) {
            echo "<h2>Latest BBS Entries</h2>";
            echo "<table border='1' style='width: 100%; border-collapse: collapse;'>";
            echo "<tr>";
            echo "<th>ID</th>";
            echo "<th>Last Update</th>";
            echo "<th>Last Element Text</th>";
            echo "</tr>";

            foreach ($latestBBS as $entry) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($entry['id']) . "</td>";
                echo "<td>" . htmlspecialchars($entry['last_update']) . "</td>";
                echo "<td>" . htmlspecialchars($entry['last_element']) . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No BBS entries found.</p>";
        }
    }

    displayLatestBBSTable();
}

$data = fetchOpDataCount(); // Let global exception handler manage any errors.
echo json_encode($data);
