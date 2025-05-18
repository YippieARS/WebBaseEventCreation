<?php
session_start();

// Ensure the user is logged in and is either an admin or an organizer
if (!isset($_SESSION['username']) || !in_array($_SESSION['role'], ['admin', 'organizer'])) {
    header("Location: login.html");
    exit();
}

// Check if event ID is passed
if (isset($_GET['id'])) {
    $event_id = (int)$_GET['id']; // Cast to integer to prevent invalid input

    // Database connection
    $conn = new mysqli("localhost", "root", "", "login_db");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch event details
    $stmt = $conn->prepare("SELECT * FROM events WHERE id = ?");
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $event = $result->fetch_assoc();
    } else {
        echo "Event not found.";
        exit();
    }
} else {
    echo "Event ID not provided.";
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        /* Reset and global styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #f2f2f2, #d9e1e8);
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            background-color: #fff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
        }

        h2 {
            text-align: center;
            font-size: 24px;
            color: #fe4500;
            margin-bottom: 30px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        input, textarea, button {
            padding: 12px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 16px;
        }

        input:focus, textarea:focus {
            border-color: #fe4500;
            outline: none;
        }

        label {
            font-size: 14px;
            font-weight: 600;
        }

        input[type="date"], input[type="time"] {
            width: 100%;
        }

        button {
            background-color: #fe4500;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #c73a00;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            font-size: 16px;
            text-decoration: none;
            color: #333;
            font-weight: 600;
        }

        .back-link:hover {
            color: #fe4500;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Edit Event</h2>

        <form method="POST" action="update_event.php">
            <input type="hidden" name="id" value="<?= $event['id'] ?>">

            <label for="title">Event Title:</label>
            <input type="text" name="title" value="<?= htmlspecialchars($event['title']) ?>" required placeholder="Enter event title">

            <label for="description">Event Description:</label>
            <textarea name="description" required placeholder="Enter event description"><?= htmlspecialchars($event['description']) ?></textarea>

            <label for="date">Event Date:</label>
            <input type="date" name="date" value="<?= $event['date'] ?>" required>

            <label for="time">Event Time:</label>
            <input type="time" name="time" value="<?= $event['time'] ?>" required>

            <label for="location">Event Location:</label>
            <input type="text" name="location" value="<?= htmlspecialchars($event['location']) ?>" required placeholder="Enter event location">

            <button type="submit">Update Event</button>
        </form>

        <a href="admin_dashboard.php" class="back-link">← Return to Dashboard</a>
    </div>

</body>
</html>

<?php
$conn->close();
?>