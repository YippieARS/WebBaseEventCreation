<?php

// Get POST data
$name = $_POST['name'];
$mid  = $_POST['mid'];
$email = $_POST['email'];
$eventitle = $_POST['eventitle'];
$eventdate = $_POST['eventdate'];
$eventdesc = $_POST['eventdesc'];

// Database connection
$conn = new mysqli('localhost', '127.0.0.1', 'databaseec','eventreg');

// Check connection
if ($conn->connect_error) {
    die('Connection Failed: ' .$conn->connect_error);
} else {
    // Prepare SQL statement
    $stmt = $conn->prepare("INSERT INTO registration (name, mid, email, eventitle, eventdate, eventdesc) 
                            VALUES (?, ?, ?, ?, ?, ?)");

    // Bind parameters (s = string, i = integer)
    $stmt->bind_param("sissss", $name, $mid, $email, $eventitle, $eventdate, $eventdesc);

    // Execute the statement
    if ($stmt->execute()) {
        echo "Event Registration Successful!";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close connections
    $stmt->close();
    $conn->close();
}

?>
