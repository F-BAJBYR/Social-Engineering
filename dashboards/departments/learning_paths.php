<?php
session_start(); // بدء الجلسة

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "social engineering defense simulation";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch active learning paths with instructor information
$sql = "SELECT lp.path_id, lp.path_name, lp.description, lp.level, lp.duration, 
               ins.name AS instructor_name, ins.bio AS instructor_bio 
        FROM learning_paths lp 
        JOIN instructors ins ON lp.instructor_id = ins.id 
        WHERE lp.status = 'active'";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learning Paths</title>

    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- Custom CSS -->
    <style>
        /* Background gradient and styles */
        body {
            background: linear-gradient(-45deg, #3a0ca3, #3f37c9, #4361ee, #4895ef, #b5179e);
            background-size: 300% 300%;
            animation: gradientAnimation 15s ease infinite;
            color: #ffffff;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        @keyframes gradientAnimation {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        h1 {
            text-align: center;
            color: #333;
            margin-top: 20px;
        }
        h2 {
            text-align: center;
            color: #333;
            margin-top: 20px;
        }
        p {
            text-align: center;
            color: #333;
            margin-top: 20px;
        }
        .path-list {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: 20px;
        }

        .path-item {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 20px;
            margin-bottom: 20px;
            width: 80%;
            max-width: 600px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .button-primary {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .button-primary:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

    <h1>Learning Paths</h1>
    <div class="path-list">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='path-item'>";
                echo "<h2>" . htmlspecialchars($row["path_name"]) . "</h2>";
                echo "<p>" . htmlspecialchars($row["description"]) . "</p>";
                echo "<p><strong>Level:</strong> " . htmlspecialchars($row["level"]) . "</p>";
                echo "<p><strong>Duration:</strong> " . htmlspecialchars($row["duration"]) . " hours</p>";
                echo "<hr>";
                echo "<p><strong>Instructor:</strong> " . htmlspecialchars($row["instructor_name"]) . "</p>";
                echo "<p><strong>Bio:</strong> " . htmlspecialchars($row["instructor_bio"]) . "</p>";
                
                // Register button
                echo "<form method='post' action='courses/register_course.php'>";
                echo "<input type='hidden' name='path_id' value='" . htmlspecialchars($row['path_id']) . "'>"; // إرسال path_id بشكل صحيح
                echo "<button type='submit' name='register_course' class='button-primary'>Register</button>";
                echo "</form>";
                echo "</div>";
            }
        } else {
            echo "<p>No learning paths available at this time.</p>";
        }
        
        $conn->close();
        ?>
    </div>

    <script type="text/javascript" src="js/js.js"></script>

</body>
</html>
