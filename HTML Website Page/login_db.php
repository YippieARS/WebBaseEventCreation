<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "login_db"; // replace with your DB name

$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>