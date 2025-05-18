<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    die("Access denied.");
}

include 'login_db.php';

// Exclude users with the role 'admin'
$result = mysqli_query($conn, "SELECT id, username, email FROM users WHERE role != 'admin'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - User List</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 40px;
        }
        .table thead {
            background-color: #343a40;
            color: white;
        }
        .btn-danger {
            transition: 0.3s;
	    background-color: #fa5700;
        }
        .btn-danger:hover {
            background-color: #ee3f03;
        }
        .btn-back {
            margin-bottom: 20px;
            background-color: #fa5700;
        }
        .btn-back:hover {
            background-color: #ee3f03;
        }

    </style>
</head>
<body>
<div class="container">
    <!-- Return Button -->
    <a href="admin_dashboard.php" class="btn btn-primary btn-back">Return to Dashboard</a>

    <h2 class="mb-4">User Management</h2>

    <?php if (isset($_GET['msg'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_GET['msg']) ?></div>
    <?php endif; ?>

    <table class="table table-striped table-bordered shadow">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th style="width: 150px;">Action</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)) : ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['username']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td>
                    <a href="delete_user.php?id=<?= $row['id'] ?>"
                       class="btn btn-sm btn-danger"
                       onclick="return confirm('Are you sure you want to delete this user?');">
                        Delete
                    </a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>