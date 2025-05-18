<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

$username = $_SESSION['username'];
$event_id = $_POST['event_id'] ?? null;
$amount = $_POST['amount'] ?? null;

if (!$event_id || $amount === null) {
    echo "Invalid payment request.";
    exit();
}

$conn = new mysqli("localhost", "root", "", "login_db");
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Simulate payment success (you can add real payment gateway here)

// Check if already registered
$check = $conn->prepare("SELECT * FROM registrations WHERE event_id = ? AND username = ?");
$check->bind_param("is", $event_id, $username);
$check->execute();
$check_result = $check->get_result();

if ($check_result->num_rows === 0) {
    // Register user for the event
    $stmt = $conn->prepare("INSERT INTO registrations (event_id, username, payment_status) VALUES (?, ?, ?)");
    $status = "Paid";
    $stmt->bind_param("iss", $event_id, $username, $status);
    $stmt->execute();
    $stmt->close();
}

$check->close();
$conn->close();

// Redirect back to events page with success message (or you can make a thank-you page)
header("Location: user_event.php");
exit();
?>