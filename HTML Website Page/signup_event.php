<?php
session_start();

// Make sure the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

$conn = new mysqli("localhost", "root", "", "login_db");

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = $_SESSION['username']; // Get the logged-in user's username
$event_id = $_POST['event_id']; // Get the event ID from the form

// Check if the user has already registered for the event
$stmt = $conn->prepare("SELECT * FROM registrations WHERE event_id = ? AND username = ?");
$stmt->bind_param("is", $event_id, $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    // Insert the user into the registrations table
    $stmt = $conn->prepare("INSERT INTO registrations (event_id, username) VALUES (?, ?)");
    $stmt->bind_param("is", $event_id, $username);
    $stmt->execute();

    echo "You have successfully signed up for the event!";
    header("Location: user_event.php"); // Redirect to the user's events page after sign-up
} else {
    echo "You are already signed up for this event.";
}

$stmt->close();
$conn->close();
?>