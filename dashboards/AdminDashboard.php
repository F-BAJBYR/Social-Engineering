<?php
session_start();

// Check if user is logged in and is admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: /extra_pages/login.php"); // Redirect if not admin
    exit();
}

$username = $_SESSION['username'] ?? 'Admin';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>

    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- Custom CSS -->
    <style>
        /* General Body Styling */
        body {
            background: linear-gradient(-45deg, #2c3e50, #4e5d6c, #6f7e8d);
            background-size: 300% 300%;
            animation: gradientAnimation 15s ease infinite;
            color: #ffffff;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        /* Keyframes for Background Animation */
        @keyframes gradientAnimation {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        /* Sidebar Styling */
        .sidebar {
            height: 100vh;
            background-color: #34495e;
            color: #ecf0f1;
            padding: 20px;
            position: fixed;
            width: 250px;
            top: 0;
            left: 0;
            box-sizing: border-box;
        }

        .sidebar-header {
            font-size: 1.5rem;
            font-weight: bold;
            text-align: center;
            color: #ecf0f1;
            margin-bottom: 20px;
        }

        .sidebar a {
            color: #bdc3c7;
            text-decoration: none;
            padding: 10px;
            display: block;
            border-radius: 4px;
            margin-bottom: 5px;
            transition: background 0.3s;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background-color: #2c3e50;
            color: #ecf0f1;
        }

        /* Logout Button Styling */
        .logout-btn {
            width: 100%;
            background-color: #e74c3c;
            color: #fff;
            border: none;
            padding: 10px;
            margin-top: 20px;
            border-radius: 4px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .logout-btn:hover {
            background-color: #c0392b;
        }

        /* Main Content Styling */
        .main-content {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            max-width: 1000px;
            margin: 30px auto;
            color: #333333;
            font-family: Arial, sans-serif;
            text-align: center;
            margin-right: 270px;
            /* Adjusted to add offset from the left */
            box-sizing: border-box;
        }

        .main-content h2 {
            font-size: 28px;
            color: #192172;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .main-content p {
            font-size: 18px;
            line-height: 1.6;
            color: #666666;
        }

        /* Additional Styling for Welcome Message */
        .main-content p span {
            color: #192172;
            font-weight: bold;
        }

        /* Admin Content Container Styling */
        #admin-content {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Button Styling for Management Sections */
        .manage-button {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 20px;
            font-size: 16px;
            color: #ffffff;
            background-color: #192172;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            text-decoration: none;
        }

        .manage-button:hover {
            background-color: #099e7b;
        }
    </style>
</head>

<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">Admin Dashboard</div>

        <!-- Navigation Links -->
        <a href="javascript:void(0);" onclick="loadAdminContent('Manger/overview.php')" class="active">
            <i class="fas fa-tachometer-alt icon"></i> Overview
        </a>
        <a href="javascript:void(0);" onclick="loadAdminContent('Manger/new_users.php')">
            <i class="fas fa-user-plus icon"></i> New Users
        </a>
        <a href="javascript:void(0);" onclick="loadAdminContent('Manger/manage_users.php')">
            <i class="fas fa-users icon"></i> Manage Users
        </a>
        <a href="javascript:void(0);" onclick="loadAdminContent('Manger/manage_courses.php')">
            <i class="fas fa-book icon"></i> Manage Courses
        </a>
        <a href="javascript:void(0);" onclick="loadAdminContent('courses/get_courses.php')">
            <i class="fas fa-eye icon"></i> View Courses
        </a>

        <a href="javascript:void(0);" onclick="loadAdminContent('Manger/reports.php')">
            <i class="fas fa-chart-line icon"></i> Reports
        </a>

        <!-- Logout -->
        <form action="logout.php" method="POST">
            <input type="submit" class="logout-btn" value="Logout">
        </form>
    </div>

    <!-- Main Content Area -->
    <div class="main-content" id="admin-content">
        <h2>Overview</h2>
        <p>Welcome, <?php echo htmlspecialchars($username); ?>! Here you can manage users, courses, and view system reports.</p>
    </div>

    <script>
        function loadAdminContent(page) {
            fetch(page)
                .then(response => response.text())
                .then(data => document.getElementById("admin-content").innerHTML = data)
                .catch(error => console.error('Error:', error));
        }
    </script>
</body>

</html>