<?php
// الاتصال بقاعدة البيانات
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "social engineering defense simulation";

$conn = new mysqli($servername, $username, $password, $dbname);

// التحقق من الاتصال
if ($conn->connect_error) {
    die("خطأ في الاتصال بقاعدة البيانات: " . $conn->connect_error);
}

// بدء الجلسة
session_start();

// التأكد من أن المستخدم مسجل الدخول
if (!isset($_SESSION['user_id'])) {
    header("Location: /extra_pages/login.php");
    exit;
}

$user_id = $_SESSION['user_id']; // الحصول على user_id من الجلسة

// استعلام جلب الدورات النشطة التي سجل فيها المستخدم
$sql = "SELECT ac.course_name, ac.description, ac.instructor, ac.start_date, ac.end_date 
        FROM active_courses ac
        INNER JOIN user_course uc ON ac.id = uc.course_id
        WHERE uc.user_id = ? AND ac.status = 'active'";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("خطأ في تحضير الاستعلام: " . $conn->error);
}

// ربط المعرف
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    die("خطأ في تنفيذ الاستعلام: " . $stmt->error);
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الدورات النشطة</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(-45deg, #3a0ca3, #3f37c9, #4361ee, #4895ef, #b5179e);
            background-size: 300% 300%;
            animation: gradientAnimation 15s ease infinite;
            color: #ffffff;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }

        @keyframes gradientAnimation {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        h1 {
            text-align: center;
            color: #ffffff;
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
            color: #000;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .message {
            text-align: center;
            color: yellow;
            font-size: 18px;
        }
    </style>
</head>
<body>
    <h1>الدورات النشطة</h1>
    <div class="course-list">
        <?php
        // التحقق من وجود بيانات في النتائج
        if ($result->num_rows > 0) {
            // إذا كانت هناك نتائج، عرض الدورات
            while ($row = $result->fetch_assoc()) {
                echo "<div class='course-item'>";
                echo "<h2>" . htmlspecialchars($row["course_name"]) . "</h2>";
                echo "<p>" . htmlspecialchars($row["description"]) . "</p>";
                echo "<p><strong>المدرب:</strong> " . htmlspecialchars($row["instructor"]) . "</p>";
                echo "<p><strong>المدة:</strong> " . htmlspecialchars($row["start_date"]) . " إلى " . htmlspecialchars($row["end_date"]) . "</p>";
                echo "</div>";
            }
        } else {
            // إذا لم توجد دورات نشطة
            echo "<p class='message'>لا توجد دورات نشطة في الوقت الحالي.</p>";
        }
        // إغلاق الاتصال بقاعدة البيانات
        $conn->close();
        ?>
    </div>
</body>
</html>
