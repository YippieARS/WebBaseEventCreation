<?php
session_start();

// Ensure the user is logged in and has proper role (admin or organizer)
if (!isset($_SESSION['username']) || !in_array($_SESSION['role'], ['admin', 'organizer'])) {
    header("Location: login.html");
    exit();
}

// Check if event ID and form data are provided
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $event_id = (int)$_POST['id'];  // Sanitize event ID

    // Sanitize and validate other form inputs
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $date = $_POST['date'];
    $time = $_POST['time'];
    $location = trim($_POST['location']);

    // Database connection
    $conn = new mysqli("localhost", "root", "", "login_db");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the update query
    $stmt = $conn->prepare("UPDATE events SET title = ?, description = ?, date = ?, time = ?, location = ? WHERE id = ?");
    $stmt->bind_param("sssssi", $title, $description, $date, $time, $location, $event_id);

    // Execute the update query
    if ($stmt->execute()) {
        // Redirect to the dashboard after successful update
        header("Location: organizer_dashboard.php"); 
        exit();
    } else {
        echo "Error updating event: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request.";
    exit();
}
?>