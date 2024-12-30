<?php
session_start(); // بدء الجلسة

// اتصال بقاعدة البيانات
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "social engineering defense simulation";

$conn = new mysqli($servername, $username, $password, $dbname);

// تحقق من الاتصال
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// معرف المستخدم (يمكن استبدال 1 بمعرف المستخدم الفعلي)
$user_id = 1;

// تحقق من إتمام جميع الدورات
$sql_courses = "SELECT COUNT(*) AS incomplete_courses 
                FROM user_course 
                WHERE user_id = ? AND completion_status != 'completed'";
$stmt_courses = $conn->prepare($sql_courses);
$stmt_courses->bind_param("i", $user_id);
$stmt_courses->execute();
$result_courses = $stmt_courses->get_result();
$row_courses = $result_courses->fetch_assoc();

if ($row_courses['incomplete_courses'] == 0) {
    // تحديث حالة الشهادة إلى "available" إذا كانت جميع الدورات مكتملة
    $sql_update_certificate = "UPDATE certifications 
                               SET status = 'available', issue_date = NOW() 
                               WHERE user_id = ? AND status = 'pending'";
    $stmt_update_certificate = $conn->prepare($sql_update_certificate);
    $stmt_update_certificate->bind_param("i", $user_id);
    $stmt_update_certificate->execute();
}

// استعلام للحصول على الشهادات المتاحة فقط
$sql_certifications = "SELECT certificate_name, issue_date, certificate_url 
                       FROM certifications 
                       WHERE user_id = ? AND status = 'available'";
$stmt_certifications = $conn->prepare($sql_certifications);
$stmt_certifications->bind_param("i", $user_id);
$stmt_certifications->execute();
$result_certifications = $stmt_certifications->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Certifications</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Custom CSS -->
    <style>
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

        .certifications-list {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: 20px;
        }

        .certification-item {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 20px;
            margin-bottom: 20px;
            width: 80%;
            max-width: 600px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .pdf-viewer {
            width: 100%;
            height: 500px;
            border: none;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<div class="certifications-list">
    <h1>My Certifications</h1>
    <div class="certifications-container">
        <?php
        if ($result_certifications && $result_certifications->num_rows > 0) {
            while($row = $result_certifications->fetch_assoc()) {
                echo "<div class='certification-item'>";
                echo "<h2>" . htmlspecialchars($row["certificate_name"]) . "</h2>";
                echo "<p><strong>Issue Date:</strong> " . htmlspecialchars($row["issue_date"]) . "</p>";
                
                // عرض الشهادة كملف PDF إذا كانت URL تشير إلى PDF
                if (pathinfo($row["certificate_url"], PATHINFO_EXTENSION) == 'pdf') {
                    echo "<iframe class='pdf-viewer' src='" . htmlspecialchars($row["certificate_url"]) . "' frameborder='0'></iframe>";
                } else {
                    // إذا كانت الشهادة ليست PDF، إظهار رابط فقط
                    echo "<a href='" . htmlspecialchars($row["certificate_url"]) . "' target='_blank'>View Certificate</a>";
                }
                echo "</div>";
            }
        } else {
            echo "<p>No certifications available.</p>";
        }
        $conn->close();
        ?>
    </div>
</div>

</body>
</html>
