<?php
// Start session
session_start();

// Destroy the session to log the user out
session_destroy();

// Redirect back to login page
header("Location: login.html");
exit();
?>