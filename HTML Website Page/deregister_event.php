<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['event_id'])) {
    $username = $_SESSION['username'];
    $event_id = intval($_POST['event_id']);

    $conn = new mysqli("localhost", "root", "", "login_db");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("DELETE FROM registrations WHERE username = ? AND event_id = ?");
    $stmt->bind_param("si", $username, $event_id);
    
    if ($stmt->execute()) {
        header("Location: user_event.php?status=deregistered"); // Update with correct page name
    } else {
        echo "Error deregistering: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request.";
}
?>