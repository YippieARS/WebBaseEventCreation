<?php
// Start session
session_start();

// Check if the user is logged in, otherwise redirect to login page
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

// If logged in, display the welcome message
echo "<h1>Welcome to the Dashboard, " . $_SESSION['username'] . "!</h1>";

// Link to home page (index.html) inside "home" folder
echo "<p><a href='Home.html'>Go to Home Page</a></p>";

// Logout link
echo "<a href='logout.php'>Logout</a>";
?>