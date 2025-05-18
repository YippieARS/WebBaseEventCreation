<?php
session_start();

// Assume the session variable 'role' is set when the user logs in, e.g.:
// $_SESSION['role'] = 'admin'; or $_SESSION['role'] = 'organizer';
// For normal users (or guests), it might be empty or set to 'user'.

$events = [];
if (file_exists('events.json')) {
    $json = file_get_contents('events.json');
    $events = json_decode($json, true);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Main Website - All Events</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f2f2;
            padding: 30px;
        }
        .event {
            background: white;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
        }
        .delete-btn {
            background-color: #e74c3c;
            color: white;
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .delete-btn:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>

    <h1>📅 Upcoming Events</h1>

    <?php if (!empty($events)): ?>
        <?php foreach ($events as $index => $event): ?>
            <div class="event">
                <h2><?= htmlspecialchars($event['title']) ?></h2>
                <p><strong>Date:</strong> <?= htmlspecialchars($event['date']) ?> at <?= htmlspecialchars($event['time']) ?></p>
                <p><strong>Location:</strong> <?= htmlspecialchars($event['location']) ?></p>
                <p><?= nl2br(htmlspecialchars($event['description'])) ?></p>

                <!-- Only show the delete button if the user is admin or organizer -->
                <?php if (isset($_SESSION['role']) && ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'organizer')): ?>
                    <form method="POST" action="delete_event.php" onsubmit="return confirm('Delete this event?');">
                        <input type="hidden" name="index" value="<?= $index ?>">
                        <button class="delete-btn" type="submit">🗑️ Delete</button>
                    </form>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No events hosted yet.</p>
    <?php endif; ?>

    <br><a href="host_event.html"><button>➕ Host a New Event</button></a>

</body>
</html>