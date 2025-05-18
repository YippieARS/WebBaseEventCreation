<?php
// Connect to DB
$conn = new mysqli("localhost", "root", "", "login_db");

// Check for errors
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Get data from form
$name = $_POST['name'];
$email = $_POST['email'];
$message = $_POST['message'];

// Insert into DB
$stmt = $conn->prepare("INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $name, $email, $message);

if ($stmt->execute()) {
  echo "Message sent!";
  header("Location: contact_success.html");
} else {
  echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>