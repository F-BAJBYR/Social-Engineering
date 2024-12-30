<?php
// الاتصال بقاعدة البيانات
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "social engineering defense simulation";

$conn = new mysqli($servername, $username, $password, $dbname);

// تحقق من الاتصال
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// استعلام لجلب المفضلات للمستخدم المحدد (استبدل '1' بالـ user_id الديناميكي في الإنتاج)
$bookmark_id = 1;
$sql = "SELECT course_id, date_added FROM bookmarks WHERE bookmark_id = $bookmark_id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookmarked Courses</title>

    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- CSS مخصص -->
    <style>
        /* تصميم الشريط الجانبي */
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
        

      /* تصميم منطقة المحتوى الرئيسي */
      .bookmark-list {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: 20px;
        }

        .bookmark-item {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 20px;
            margin-bottom: 20px;
            width: 80%;
            max-width: 600px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .bookmark-item h2 {
            color: #34495e;
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .bookmark-item p {
            margin: 5px 0;
        }

        hr {
            border: none;
            border-top: 1px solid #bdc3c7;
            margin: 15px 0;
        }

        /* تصميم بطاقة المفضلة */
        .bookmark-card {
            background-color: #fff;
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .bookmark-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }
        .bookmark-card h2 {
            font-size: 1.2rem;
            margin-bottom: 10px;
        }
        .bookmark-card p {
            color: #555;
            font-size: 0.9rem;
        }

        /* زر تسجيل الخروج مخصص */
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
    </style>
</head>
<body>

<div class="bookmark-list">
    <h1>Bookmarked Courses</h1>
    <div class="bookmark-container">
        <?php
        if ($result->num_rows > 0) {
            // إذا كانت هناك مفضلات، يتم عرضها
            while($row = $result->fetch_assoc()) {
                echo "<div class='bookmark-card'>";
                echo "<h2>" . htmlspecialchars($row["course_name"]) . "</h2>";
                echo "<p><strong>Date Added:</strong> " . htmlspecialchars($row["date_added"]) . "</p>";
                echo "</div>";
            }
        } else {
            // إذا لم توجد مفضلات، يعرض النص التالي
            echo "<p>No bookmarks available.</p>";
        }
        $conn->close();
        ?>
    </div>
</div>

<script type="text/javascript" src="js/js.js"></script>
</body>
</html>
