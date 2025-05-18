<?php
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'organizer') {
    die("Access denied. You must be an organizer to create events.");
}

$conn = new mysqli("localhost", "root", "", "login_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['submit'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $location = $_POST['location'];
    $organizer_username = $_SESSION['username'];

    // Handle image upload
    $image = $_FILES['image']['name'];
    $target = "uploads/" . basename($image);

    if (!file_exists("uploads")) {
        mkdir("uploads", 0777, true); // Create uploads folder if it doesn't exist
    }

    if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
        $stmt = $conn->prepare("INSERT INTO events (title, description, date, time, location, image, organizer_username) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $title, $description, $date, $time, $location, $image, $organizer_username);

        if ($stmt->execute()) {
            echo "✅ Event created!";
        } else {
            echo "❌ Database Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "❌ Failed to upload image.";
    }
}

$conn->close();
?>