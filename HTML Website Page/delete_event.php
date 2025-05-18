<?php
session_start();

// Check if user is logged in and has the correct role
if (!isset($_SESSION['username']) || !in_array($_SESSION['role'], ['admin', 'organizer'])) {
    header("Location: login.html");
    exit();
}

// Check if event ID is provided
if (!isset($_POST['id'])) {
    die("Event ID missing.");
}

$conn = new mysqli("localhost", "root", "", "login_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$event_id = $_POST['id'];

// If the user is an organizer, only allow deletion of their own event
if ($_SESSION['role'] === 'organizer') {
    $stmt = $conn->prepare("DELETE FROM events WHERE id = ? AND organizer_username = ?");
    $stmt->bind_param("is", $event_id, $_SESSION['username']);
} else {
    // Admins can delete any event
    $stmt = $conn->prepare("DELETE FROM events WHERE id = ?");
    $stmt->bind_param("i", $event_id);
}

if ($stmt->execute()) {
    // Redirect to the appropriate dashboard after deletion
    if ($_SESSION['role'] === 'admin') {
        header("Location: admin_manage_events.php");
    } else {
        header("Location: my_events.php");
    }
    exit();
} else {
    echo "Failed to delete event.";
}

$stmt->close();
$conn->close();
?>