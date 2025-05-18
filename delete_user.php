<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    die("Access denied.");
}

include 'db_connect.php';

if (isset($_GET['id'])) {
    $user_id = intval($_GET['id']);
    
    // Prevent deletion of the main admin (optional safety check)
    if ($user_id == 1) {
        die("Cannot delete the main admin.");
    }

    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        header("Location: users_list.php?msg=User+deleted");
    } else {
        echo "Error deleting user.";
    }

    $stmt->close();
} else {
    echo "No user ID provided.";
}
?>