<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
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
            background: url('websitebg5.jpg') no-repeat center center fixed;
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
            overflow-y: auto;
            transition: margin-left 0.3s ease;
        }

        .main-content.collapsed {
            margin-left: 0;
        }

        .welcome {
            font-size: 22px;
            margin-bottom: 30px;
            color: white;
            text-shadow: 1px 1px 3px black;
        }

        .card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .card {
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
            transition: transform 0.2s;
	    color: #ffffff;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card a {
            display: inline-block;
            margin-top: 10px;
            text-decoration: none;
            color: #fe4500;
            font-weight: bold;
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
                position: fixed;
                height: 100%;
                transform: translateX(-100%);
                z-index: 1000;
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
    <a href="users_list.php">Manage Users ⚙️</a>
    <a href="admin_manage_events.php">Manage Events 🗒️</a>
    <a href="aboutadmin.html">About System ℹ️</a>
    <a href="change_password.php">Change Password 🔒</a>
    <a href="logout.php">Logout 🚪</a>
</div>

<div class="main-content" id="mainContent">
    <div class="welcome">
        Welcome, <?= htmlspecialchars($_SESSION['username']); ?>!
    </div>

    <div class="card-grid">
        <div class="card">
            <h3>Manage Users</h3>
            <p>View, edit, or remove user accounts.</p>
            <a href="users_list.php">Go to Users</a>
        </div>

        <div class="card">
            <h3>Manage Events</h3>
            <p>Review and manage all event listings.</p>
            <a href="admin_manage_events.php">Go to Events</a>
        </div>

        <div class="card">
            <h3>Logout</h3>
            <p>End your session securely.</p>
            <a href="logout.php">Logout</a>
        </div>
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