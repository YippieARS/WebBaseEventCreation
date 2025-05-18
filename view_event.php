<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

$conn = new mysqli("localhost", "root", "", "login_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$event_id = isset($_GET['event_id']) ? intval($_GET['event_id']) : 0;

// Fetch event details
$stmt = $conn->prepare("SELECT * FROM events WHERE id = ?");
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();
$event = $result->fetch_assoc();

if (!$event) {
    echo "Event not found!";
    exit();
}

// Clean price
$raw_price = isset($event['price']) ? trim(str_replace('RM', '', $event['price'])) : '0';
$clean_price = (float)$raw_price;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Details</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: url('websitebg2.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #fff;
        }

        h2, h3 {
            text-align: center;
            margin: 0;
            padding: 20px;
            font-size: 2em;
        }

        .event-detail {
            background-color: rgba(0, 0, 0, 0.7); 
            margin: 40px auto;
            padding: 20px;
            border-radius: 10px;
            width: 80%;
            max-width: 900px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.3);
            text-align: center; 
        }

        .event-detail img {
            width: 100%;
            max-width: 500px; 
            height: auto;
            border-radius: 8px;
            margin-bottom: 20px;
            display: block; 
            margin-left: auto; 
            margin-right: auto; 
        }

        .event-detail p {
            line-height: 1.6;
            font-size: 1.1em;
            margin-bottom: 10px;
        }

        .back-btn {
            display: block;
            width: 250px;
            margin: 20px auto;
            padding: 10px;
            background-color: #fe4500;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1.1em;
            cursor: pointer;
            text-align: center;
            transition: background-color 0.3s ease;
	        text-decoration: none;
        }

        .back-btn:hover {
            background-color: #ad4f2c;
        }
    </style>
</head>
<body>

<h2>Event Details</h2>

<div class="event-detail">
    <h3><?= htmlspecialchars($event['title']) ?></h3>
    <img src="<?= htmlspecialchars($event['image']) ?>" alt="Event Image">
    <p><strong>Description:</strong> <?= htmlspecialchars($event['description']) ?></p>
    <p><strong>Date:</strong> <?= $event['date'] ?> | <strong>Time:</strong> <?= $event['time'] ?></p>
    <p><strong>Location:</strong> <?= htmlspecialchars($event['location']) ?></p>
    <p><strong>Hosted by:</strong> <?= htmlspecialchars($event['organizer_username']) ?></p>
    <p><strong>Price:</strong> <?= $clean_price > 0 ? 'RM' . number_format($clean_price, 2) : 'Free' ?></p>
</div>

<a href="user_event.php">
    <button class="back-btn">Return to Upcoming Events</button>
</a>

</body>
</html>