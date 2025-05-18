<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'organizer') {
    header("Location: login.html");
    exit();
}

$conn = new mysqli("localhost", "root", "", "login_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$event_id = $_GET['event_id'] ?? null;
$username = $_SESSION['username'];

if (!$event_id) {
    echo "Invalid event ID.";
    exit();
}

// Check ownership
$check = $conn->prepare("SELECT * FROM events WHERE id = ? AND organizer_username = ?");
$check->bind_param("is", $event_id, $username);
$check->execute();
$result = $check->get_result();

if ($result->num_rows === 0) {
    echo "You are not authorized to view this event's signups.";
    exit();
}

// Get signups
$signup_stmt = $conn->prepare("SELECT username FROM registrations WHERE event_id = ?");
$signup_stmt->bind_param("i", $event_id);
$signup_stmt->execute();
$signup_result = $signup_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Event Signups</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f7f7f7;
            padding: 40px;
            color: #333;
        }

        .box {
            max-width: 600px;
            margin: auto;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        h2 {
            color: #2c3e50;
            text-align: center;
        }

        ul {
            list-style: none;
            padding-left: 0;
        }

        li {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }

        .back {
            display: inline-block;
            margin-top: 20px;
            background-color: #ff4200;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            transition: background 0.3s ease;
        }

        .back:hover {
            background-color: #e13c02;
        }
    </style>
</head>
<body>
    <div class="box">
        <h2>👥 Signups for This Event</h2>

        <?php if ($signup_result->num_rows > 0): ?>
            <ul>
                <?php while ($row = $signup_result->fetch_assoc()): ?>
                    <li><?= htmlspecialchars($row['username']) ?></li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>No users have signed up for this event yet.</p>
        <?php endif; ?>

        <a href="my_events.php" class="back">← Back to Events</a>
    </div>
</body>
</html>

<?php
$signup_stmt->close();
$check->close();
$conn->close();
?>