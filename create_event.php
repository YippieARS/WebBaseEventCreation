<?php
session_start();

// Show PHP errors for debugging (remove in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Only allow access if logged in and role is organizer or admin
if (!isset($_SESSION['username']) || ($_SESSION['role'] !== 'organizer' && $_SESSION['role'] !== 'admin')) {
    header("Location: login.html");
    exit();
}

$conn = new mysqli("localhost", "root", "", "login_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST["title"];
    $description = $_POST["description"];
    $date = $_POST["date"];
    $time = $_POST["time"];
    $location = $_POST["location"];
    $price = $_POST["price"];  // Get price from form
    $organizer = $_SESSION["username"];

    // Handle image upload
    $imagePath = "";
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] === UPLOAD_ERR_OK) {
        $imageName = basename($_FILES["image"]["name"]);
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $targetFile = $targetDir . time() . "_" . $imageName;
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
            $imagePath = $targetFile;
        } else {
            $message = "❌ Failed to upload image.";
        }
    }

    if ($imagePath !== "") {
        $stmt = $conn->prepare("INSERT INTO events (title, description, date, time, location, price, image, organizer_username) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $title, $description, $date, $time, $location, $price, $imagePath, $organizer);

        if ($stmt->execute()) {
            $message = "✅ Event created successfully!";
        } else {
            $message = "❌ Error creating event: " . $stmt->error;
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Host An Event</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-image: url('websitebg4.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .container {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="date"],
        input[type="time"],
        input[type="file"],
        input[type="number"],
        textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 14px;
        }

        button {
            margin-top: 20px;
            width: 100%;
            padding: 12px;
            background-color: #fe4500;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #ad4f2c;
        }

        .back-button {
            display: inline-block;
            margin-top: 15px;
            text-align: center;
            color: #333;
            text-decoration: underline;
            font-size: 14px;
        }

        .message {
            text-align: center;
            font-weight: bold;
            margin-top: 10px;
            margin-bottom: 10px;
        }

        .message.success {
            color: green;
        }

        .message.error {
            color: red;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Host A New Event</h2>

        <?php if (!empty($message)): ?>
            <div class="message <?php echo (strpos($message, '✅') === 0) ? 'success' : 'error'; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <form action="create_event.php" method="POST" enctype="multipart/form-data">
            <label for="title">Event Title</label>
            <input type="text" name="title" id="title" required>

            <label for="description">Description</label>
            <textarea name="description" id="description" rows="4" required></textarea>

            <label for="date">Date</label>
            <input type="date" name="date" id="date" required>

            <label for="time">Time</label>
            <input type="time" name="time" id="time" required>

            <label for="location">Location</label>
            <input type="text" name="location" id="location" required>

            <label for="price">Fee (optional)</label>
            <input type="text" name="price" id="price" placeholder="Enter event price (optional)">

            <label for="image">Event Image</label>
            <input type="file" name="image" id="image" accept="image/*" required>

            <button type="submit">Create Event</button>
        </form>
        <div class="back-button">
            <a href="organizer_dashboard.php">← Return To Dashboard</a>
        </div>
    </div>
</body>
</html>