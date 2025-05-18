<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'organizer') {
    header("Location: login.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Organizer Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
        }

        body {
            display: flex;
            height: 100vh;
            background: url('websitebg4.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #333;
        }

        .sidebar {
            width: 250px;
            background-color: #ddd7d5;
            padding: 30px 20px 80px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.05);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            transition: transform 0.3s ease;
            z-index: 1000;
        }

        .sidebar.collapsed {
            transform: translateX(-100%);
        }

        .sidebar h2 {
            font-size: 22px;
            margin-bottom: 40px;
            color: #fe4500;
        }

        .sidebar a {
            text-decoration: none;
            color: #333;
            margin-bottom: 20px;
            font-size: 16px;
            padding: 10px 15px;
            border-radius: 6px;
            transition: background-color 0.3s;
        }

        .sidebar a:hover {
            background-color: #fe4500;
            color: white;
        }

        .main-content {
            flex: 1;
            margin-left: 250px;
            padding: 40px;
            transition: margin-left 0.3s ease;
        }

        .main-content.collapsed {
            margin-left: 0;
        }

        .welcome-box {
            max-width: 600px;
            background-color: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            margin: auto;
            text-align: center;
        }

        .toggle-btn {
            position: fixed;
            bottom: 20px;
            left: 270px;
            background-color: #fe4500;
            color: white;
            border: none;
            font-size: 18px;
            padding: 10px 15px;
            border-radius: 6px;
            cursor: pointer;
            z-index: 1100;
            transition: left 0.3s ease;
        }

        .toggle-btn.move-right {
            left: 20px;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.collapsed {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .main-content.collapsed {
                margin-left: 0;
            }

            .toggle-btn {
                left: 20px;
            }

            .toggle-btn.move-right {
                left: 270px;
            }
        }
    </style>
</head>
<body>

<div class="sidebar" id="sidebar">
    <h2>Web Based Event Creation Management System</h2>
    <a href="create_event.php">➕ Host Event</a>
    <a href="my_events.php">📋 My Events</a>
    <a href="ContactOrganizer.html">📞 Contact Us</a>
    <a href="aboutorganizers.html">ℹ️ About System</a>
    <a href="organizer_change_password.php">🔒 Change Password</a>
    <a href="logout.php">🚪 Logout</a>
</div>

<div class="main-content" id="mainContent">
    <div class="welcome-box">
        <h1>Welcome, <?= htmlspecialchars($_SESSION['username']); ?>!</h1>
        <p>This is your organizer dashboard. Use the side menu to manage your events.</p>
    </div>
</div>

<!-- Toggle Button -->
<button class="toggle-btn" id="toggleBtn">☰</button>

<script>
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('mainContent');
    const toggleBtn = document.getElementById('toggleBtn');

    toggleBtn.addEventListener('click', () => {
        sidebar.classList.toggle('collapsed');
        mainContent.classList.toggle('collapsed');
        toggleBtn.classList.toggle('move-right');
    });
</script>

</body>
</html>