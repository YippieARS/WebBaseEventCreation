<?php
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$ticket = $_POST['ticket'] ?? '';

echo "<h2>🎉 Thank You for Registering!</h2>";
echo "<p><strong>Name:</strong> $name</p>";
echo "<p><strong>Email:</strong> $email</p>";
echo "<p><strong>Ticket:</strong> $ticket</p>";
?>