<?php
// Start the session to check if the user is logged in
session_start();

// Make sure the user is logged in and has the 'organizer' role
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'organizer') {
    header("Location: login.html");  // Redirect to login page if not logged in
    exit();
}

$conn = new mysqli("localhost", "root", "", "login_db");  // Connect to the database

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$organizer = $_SESSION['username']; // Get the logged-in organizer's username
$today = date("Y-m-d"); // Get today's date

// Prepare the SQL statement to fetch active events created by the logged-in organizer
$stmt = $conn->prepare("SELECT * FROM events WHERE organizer_username = ? AND date >= ? ORDER BY date ASC");
$stmt->bind_param("ss", $organizer, $today); // Bind the organizer's username and today's date as parameters
$stmt->execute();  // Execute the statement
$result = $stmt->get_result();  // Get the result set

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Active Events</title>
    <style>
        /* Basic CSS for layout */
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        .event-box {
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 10px;
            background-color: #f9f9f9;
        }
        .event-box h3 {
            margin: 0;
            font-size: 1.5em;
        }
        .event-box p {
            margin: 5px 0;
        }
        button {
            padding: 5px 10px;
            margin: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>

<h2>My Active Events</h2>

<?php if ($result->num_rows > 0): ?>
    <?php while ($event = $result->fetch_assoc()): ?>
        <div class="event-box">
            <h3><?= htmlspecialchars($event['title']) ?></h3>
            <p><?= htmlspecialchars($event['description']) ?></p>
            <p><strong>Date:</strong> <?= $event['date'] ?> | <strong>Time:</strong> <?= $event['time'] ?></p>
            <p><strong>Location:</strong> <?= htmlspecialchars($event['location']) ?></p>

            <!-- Edit event button -->
            <form method="GET" action="edit_event.php" style="display:inline;">
                <input type="hidden" name="id" value="<?= $event['id'] ?>">
                <button type="submit">Edit</button>
            </form>

            <!-- Delete event button -->
            <form method="POST" action="delete_event.php" style="display:inline;">
                <input type="hidden" name="id" value="<?= $event['id'] ?>">
                <button type="submit" onclick="return confirm('Are you sure you want to delete this event?')">Delete</button>
            </form>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <p>No active events found.</p>
<?php endif; ?>

</body>
</html>

<?php
$stmt->close();  // Close the statement
$conn->close();  // Close the database connection
?>

