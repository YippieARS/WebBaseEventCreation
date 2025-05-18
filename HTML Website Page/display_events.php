<?php
session_start();
$conn = new mysqli("localhost", "root", "", "login_db");

$role = $_SESSION['role'] ?? '';
$loggedInUser = $_SESSION['username'] ?? '';

$sql = "SELECT * FROM events ORDER BY date ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head><title>Events</title></head>
<body>
<h2>Upcoming Events</h2>

<?php while($row = $result->fetch_assoc()): ?>
    <div>
        <h3><?= htmlspecialchars($row['title']) ?></h3>
        <p><?= htmlspecialchars($row['description']) ?></p>
        <p>Date: <?= $row['date'] ?> | Time: <?= $row['time'] ?> | Location: <?= $row['location'] ?></p>

        <?php if ($role === 'admin' || $loggedInUser === $row['organizer_username']): ?>
            <form action="edit_event.php" method="GET" style="display:inline;">
                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                <button type="submit">Edit</button>
            </form>
            <form action="delete_event.php" method="POST" style="display:inline;">
                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                <button type="submit">Delete</button>
            </form>

	<a href="display_events.php">View Upcoming Events</a>	

        <?php endif; ?>
    </div>
<?php endwhile; ?>

</body>
</html>