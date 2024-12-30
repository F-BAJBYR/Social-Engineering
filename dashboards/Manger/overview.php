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

    <style>
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

        /* Main Content Styling */
        .main-content {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin: 30px auto;
            /* يجعل الحاوية في منتصف الصفحة */
            color: #333333;
            font-family: Arial, sans-serif;
            text-align: center;
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