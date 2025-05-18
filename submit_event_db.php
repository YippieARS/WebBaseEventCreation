<?php
// Get submitted values
$title = $_POST['title'] ?? '';
$date = $_POST['date'] ?? '';
$time = $_POST['time'] ?? '';
$description = $_POST['description'] ?? '';
$location = $_POST['location'] ?? '';


if ($title && $date && $time && $description && $location) {
    $event = [
        "title" => $title,
        "date" => $date,
        "time" => $time,
        "description" => $description,
        "location" => $location

    ];

    // Load existing events
    $file = 'events.json';
    $events = [];

    if (file_exists($file)) {
        $json = file_get_contents($file);
        $events = json_decode($json, true);
    }

    // Add new event
    $events[] = $event;

    // Save all events back to file
    file_put_contents($file, json_encode($events, JSON_PRETTY_PRINT));
}
?>

<!DOCTYPE html>
<html>
<head><title>Event Hosted</title></head>
<body>
    <h2>✅ Event Hosted Successfully!</h2>
    <a href="organizer_dashboard.php">Go Back To Dashboard</a>
</body>
</html>