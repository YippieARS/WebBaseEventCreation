<?php
session_start();

// Ensure the user is logged in and is an admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.html");
    exit();
}

// Connect to the database
$conn = new mysqli("localhost", "root", "", "login_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM events ORDER BY date ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Events</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: url('websitebg5.jpg') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 20px;
        }

        h2 {
            text-align: center;
            color: #fff;
            margin-bottom: 20px;
        }

        .back-btn {
            display: block;
            text-align: center;
            margin-bottom: 30px;
        }

        .back-btn a {
            background-color: #fe4500;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            transition: background 0.3s;
        }

        .back-btn a:hover {
            background-color: #c73a00;
        }

        .event-box {
            background: rgba(0, 0, 0, 0.7);
            color: #fff;
            border-radius: 10px;
            padding: 20px;
            margin: 15px auto;
            max-width: 700px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.3);
        }

        .event-box h3 {
            margin-top: 0;
            font-size: 1.6em;
            color: #fe9a00;
        }

        .event-box p {
            margin: 8px 0;
        }

        .event-box form {
            display: inline-block;
            margin-top: 10px;
        }

        .event-box button {
            padding: 10px 18px;
            border: none;
            border-radius: 5px;
            margin-right: 10px;
            cursor: pointer;
            font-weight: bold;
            background-color: #fe4500;
            color: #fff;
            transition: background 0.3s;
        }

        .event-box button:hover {
            background-color: #c73a00;
        }

        @media screen and (max-width: 768px) {
            .event-box {
                padding: 15px;
            }
            .event-box h3 {
                font-size: 1.3em;
            }
        }
    </style>
</head>
<body>

    <h2>Manage Events</h2>

    <div class="back-btn">
        <a href="admin_dashboard.php">← Return to Dashboard</a>
    </div>

    <?php if ($result->num_rows > 0): ?>
        <?php while ($event = $result->fetch_assoc()): ?>
            <div class="event-box">
                <h3><?= htmlspecialchars($event['title']) ?></h3>
                <p><?= htmlspecialchars($event['description']) ?></p>
                <p><strong>Date:</strong> <?= $event['date'] ?> | <strong>Time:</strong> <?= $event['time'] ?></p>
                <p><strong>Location:</strong> <?= htmlspecialchars($event['location']) ?></p>

                <form method="GET" action="edit_event.php">
                    <input type="hidden" name="id" value="<?= $event['id'] ?>">
                    <button type="submit">Edit</button>
                </form>

                <form method="POST" action="delete_event.php" onsubmit="return confirm('Are you sure you want to delete this event?');">
                    <input type="hidden" name="id" value="<?= $event['id'] ?>">
                    <button type="submit">Delete</button>
                </form>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p style="text-align: center; color: white;">No events found.</p>
    <?php endif; ?>

</body>
</html>

<?php $conn->close(); ?>
