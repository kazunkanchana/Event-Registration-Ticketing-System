<?php
require_once 'config/database.php';
require_once 'core/Database.php';
require_once 'core/helpers.php';
require_once 'models/Event.php';

echo "<h1>Debug Data</h1>";

echo "<h2>1. Testing event_get_upcoming()</h2>";
$events = event_get_upcoming(10);
echo "<pre>Count: " . count($events) . "</pre>";
if (!empty($events)) {
    echo "<table border='1'><tr><th>ID</th><th>Title</th><th>Status</th><th>Date</th></tr>";
    foreach ($events as $e) {
        echo "<tr>";
        echo "<td>" . $e['id'] . "</td>";
        echo "<td>" . $e['title'] . "</td>";
        echo "<td>" . $e['status'] . "</td>";
        echo "<td>" . $e['event_date'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "No events returned from function.<br>";
}

echo "<h2>2. Testing Raw SQL</h2>";
$sql = "SELECT * FROM events";
$all = db_query_all($sql);
echo "<pre>Total in DB: " . count($all) . "</pre>";

echo "<h2>3. Testing Status 'upcoming'</h2>";
$sql = "SELECT * FROM events WHERE status = 'upcoming'";
$upcoming = db_query_all($sql);
echo "<pre>Total 'upcoming' in DB: " . count($upcoming) . "</pre>";

?>
