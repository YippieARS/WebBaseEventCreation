<?php
session_start();

// Ensure user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

$conn = new mysqli("localhost", "root", "", "login_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = $_SESSION['username'];
$role = $_SESSION['role'];

if ($role === 'organizer') {
    $stmt = $conn->prepare("SELECT * FROM events WHERE organizer_username = ? ORDER BY date ASC");
    $stmt->bind_param("s", $username);
} else {
    $stmt = $conn->prepare("SELECT * FROM events ORDER BY date ASC");
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Active Events</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background: #f0f2f5;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        .event-box {
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            margin: 20px auto;
            max-width: 600px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .event-box img {
            width: 100%;
            max-height: 300px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .event-box h3 {
            margin-top: 0;
            color: #2c3e50;
        }

        .event-box p {
            margin: 5px 0;
        }

        button {
            padding: 6px 12px;
            border: none;
            border-radius: 5px;
            margin: 5px 5px 0 0;
            cursor: pointer;
            font-weight: bold;
        }

        button.edit {
            background-color: #3498db;
            color: white;
        }

        button.delete {
            background-color: #e74c3c;
            color: white;
        }

        .back-button {
            text-align: center;
            margin-top: 30px;
        }

        .back-button a {
            text-decoration: none;
            background-color: #ff4200;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .back-button a:hover {
            background-color: #e13c02;
        }
    </style>
</head>
<body>

<h2>Your Active Events</h2>

<?php if ($result->num_rows > 0): ?>
    <?php while ($event = $result->fetch_assoc()): ?>
        <div class="event-box">
            <?php if (!empty($event['image'])): ?>
                <img src="<?= htmlspecialchars($event['image']) ?>" alt="Event Image">
            <?php endif; ?>

            <h3><?= htmlspecialchars($event['title']) ?></h3>
            <p><?= htmlspecialchars($event['description']) ?></p>
            <?php
                // Ensure price is numeric
                $raw_price = preg_replace('/[^0-9.]/', '', $event['price']);
                $formatted_price = number_format((float)$raw_price, 2);
            ?>
            <p>
                <strong>Date:</strong> <?= $event['date'] ?> |
                <strong>Time:</strong> <?= $event['time'] ?> |
                <strong>Fee:</strong> RM<?= $formatted_price ?>
            </p>
            <p><strong>Location:</strong> <?= htmlspecialchars($event['location']) ?></p>
            <p><strong>Organizer:</strong> <?= htmlspecialchars($event['organizer_username']) ?></p>

            <?php if ($role === 'organizer' && $username === $event['organizer_username']): ?>
                <form method="GET" action="organizer_edit_event.php" style="display:inline;">
                    <input type="hidden" name="id" value="<?= $event['id'] ?>">
                    <button type="submit" class="edit">Edit</button>
                </form>

                <form method="POST" action="delete_event.php" style="display:inline;">
                    <input type="hidden" name="id" value="<?= $event['id'] ?>">
                    <button type="submit" class="delete" onclick="return confirm('Are you sure you want to delete this event?')">Delete</button>
                </form>

                <!-- Add "View Signups" button -->
                <form method="GET" action="view_signups.php" style="display:inline;">
                    <input type="hidden" name="event_id" value="<?= $event['id'] ?>">
                    <button type="submit" class="edit" style="background-color:#2ecc71;">View Signups</button>
                </form>
            <?php endif; ?>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <p style="text-align:center;">No active events found👀.</p>
<?php endif; ?>

<div class="back-button">
    <a href="organizer_dashboard.php">🚪Return To Dashboard</a>
</div>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>