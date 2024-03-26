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
            echo "<td>" . htmlspecialchars($entry['id'] ?? '') . "</td>";
            echo "<td>" . htmlspecialchars($entry['last_update'] ?? '') . "</td>";
            echo "<td>" . htmlspecialchars($entry['last_element'] ?? '') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No BBS entries found.</p>";
    }
}

displayLatestBBSTable();

$data = fetchOpDataCount(); // Let global exception handler manage any errors.
echo json_encode($data);
