<?php
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

// استعلام لجلب جميع الدورات المتاحة
$sql = "SELECT * FROM courses";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الدورات المتاحة</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* تنسيقات CSS */
        body {
            background: linear-gradient(-45deg, #3a0ca3, #3f37c9, #4361ee, #4895ef, #b5179e);
            background-size: 300% 300%;
            animation: gradientAnimation 15s ease infinite;
            color: #ffffff;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh; /* تأكد من أن العنصر يغطي الصفحة بالكامل */
        }

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

        h1 {
            text-align: center;
            color: #333;
            margin-top: 20px;
        }
        h2 {
            color: #333;
            margin-top: 20px;
        }
        p{
            color: #333;
            margin-top: 20px;
        }
        .course-list {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: 20px;
        }

        .course-item {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 20px;
            margin-bottom: 20px;
            width: 80%;
            max-width: 600px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .message {
            text-align: center;
            color: green;
            font-size: 18px;
        }
    </style>
</head>
<body>
    <h1> Available Courses</h1>

    <?php
    // عرض رسالة النجاح أو الفشل
    session_start(); // تأكد من بدء الجلسة
    if (isset($_SESSION['message'])) {
        echo "<p class='message'>" . $_SESSION['message'] . "</p>";
        unset($_SESSION['message']);
    }
    ?>

    <div class="course-list">
        <?php
        // عرض جميع الدورات المتاحة مع زر التسجيل
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<div class='course-item'>";
                echo "<h2>" . htmlspecialchars($row['course_name']) . "</h2>";
                echo "<p>" . htmlspecialchars($row['description']) . "</p>";
                echo "<p><strong>المدة:</strong> " . htmlspecialchars($row['start_date']) . " إلى " . htmlspecialchars($row['end_date']) . "</p>";

                // زر التسجيل في الدورة
                echo "<form method='post' action='courses/register_course.php'>";
                echo "<input type='hidden' name='course_id' value='" . htmlspecialchars($row['id']) . "'>";
                echo "<button type='submit' name='register_course'>تسجيل</button>";
                echo "</form>";

                echo "</div>";
            }
        } else {
            echo "<p>There are no courses available at this time.</p>";
        }
        ?>
    </div>
</body>
</html>
