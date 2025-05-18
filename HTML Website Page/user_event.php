<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

$conn = new mysqli("localhost", "root", "", "login_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = $_SESSION['username'];
$stmt = $conn->prepare("SELECT * FROM events ORDER BY date ASC");
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Upcoming Events</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
  <style>
    body {
      margin: 0;
      font-family: 'Poppins', sans-serif;
      background: url('websitebg2.jpg') no-repeat center center fixed;
      background-size: cover;
      color: #fff;
    }

    .container {
      max-width: 900px;
      margin: auto;
      padding: 40px 20px;
    }

    h2 {
      text-align: center;
      color: #fff;
      font-size: 36px;
      margin-bottom: 30px;
    }

    .event-box {
      background-color: rgba(255, 255, 255, 0.9);
      color: #333;
      padding: 20px;
      margin-bottom: 20px;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.2);
    }

    .event-box h3 {
      font-size: 24px;
      margin-top: 0;
      color: #fe4500;
    }

    .event-box p {
      font-size: 16px;
      margin: 8px 0;
    }

    .signup-btn, .view-btn {
      padding: 10px 20px;
      margin-right: 10px;
      background-color: #fe4500;
      color: white;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-weight: 500;
      transition: background 0.3s ease;
    }

    .signup-btn:hover, .view-btn:hover {
      background-color: #d63b00;
    }

    .back-btn {
      display: inline-block;
      margin-top: 30px;
      padding: 12px 25px;
      background-color: #222;
      color: #fff;
      text-decoration: none;
      border-radius: 8px;
      transition: background 0.3s ease;
    }

    .back-btn:hover {
      background-color: #fe4500;
    }

    @media (max-width: 600px) {
      .signup-btn, .view-btn {
        display: block;
        margin-top: 10px;
        width: 100%;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>🎫 Upcoming Events</h2>

    <?php
    if ($result->num_rows > 0) {
        while ($event = $result->fetch_assoc()) {
            $check_registration = $conn->prepare("SELECT * FROM registrations WHERE event_id = ? AND username = ?");
            $check_registration->bind_param("is", $event['id'], $username);
            $check_registration->execute();
            $check_result = $check_registration->get_result();

            // Clean price field
            $raw_price = trim(str_replace('RM', '', $event['price']));
            $clean_price = (float) $raw_price;

            echo '<div class="event-box">';
            echo '<h3>' . htmlspecialchars($event['title']) . '</h3>';
            echo '<p><strong>Date:</strong> ' . htmlspecialchars($event['date']) . ' | <strong>Time:</strong> ' . htmlspecialchars($event['time']) . '</p>';
            echo '<p><strong>Location:</strong> ' . htmlspecialchars($event['location']) . '</p>';

            // Only show price if it exists and is greater than 0
            if (!empty($raw_price) && $clean_price > 0) {
                echo '<p><strong>Fee:</strong> RM' . number_format($clean_price, 2) . '</p>';
            }

            echo '<p><strong>Hosted by:</strong> ' . htmlspecialchars($event['organizer_username']) . '</p>';

            if ($check_result->num_rows == 0) {
                // Redirect to payment page if paid event
                $form_action = ($clean_price > 0) ? 'payment.php' : 'signup_event.php';

                echo '<form method="POST" action="' . $form_action . '" style="display:inline;">
                        <input type="hidden" name="event_id" value="' . $event['id'] . '">
                        <input type="hidden" name="amount" value="' . $clean_price . '">
                        <button type="submit" class="signup-btn">Sign Up</button>
                      </form>';
            } else {
                // Show payment status if the event has a price
                if (!empty($raw_price) && $clean_price > 0) {
                    echo '<p><em style="color:green;">✅ Payment successful</em></p>';
                }

                echo '<form method="POST" action="deregister_event.php" style="display:inline;">
                        <input type="hidden" name="event_id" value="' . $event['id'] . '">
                        <button type="submit" class="signup-btn" style="background-color:#999;">Deregister</button>
                      </form>';
            }

            echo '<form method="GET" action="view_event.php" style="display:inline;">
                    <input type="hidden" name="event_id" value="' . $event['id'] . '">
                    <button type="submit" class="view-btn">View Details</button>
                  </form>';
            echo '</div>';

            $check_registration->close();
        }
    } else {
        echo '<p>No events found.</p>';
    }

    $stmt->close();
    $conn->close();
    ?>

    <a href="HomeUser.html" class="back-btn">Return to Homepage</a>
  </div>
</body>
</html>