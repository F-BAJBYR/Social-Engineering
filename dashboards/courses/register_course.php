<?php
session_start();
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

// تحقق مما إذا كان المستخدم قد قام بتسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    header("Location: /extra_pages/login.php");
    exit;
}

$userId = $_SESSION['user_id'];

if (isset($_POST['register_course'])) {
    $courseId = $_POST['course_id'];  // تأكد من أن هذه القيم موجودة وصحيحة
    $pathId = $_POST['path_id'];
    echo "Course ID: " . $courseId . " Path ID: " . $pathId;  // تحقق من القيم المرسلة


    // التحقق مما إذا كان path_id موجودًا في جدول learning_paths
    $check_path_sql = "SELECT * FROM learning_paths WHERE path_id = ?";
    $stmt_check_path = $conn->prepare($check_path_sql);
    $stmt_check_path->bind_param("i", $pathId);
    $stmt_check_path->execute();
    $path_result = $stmt_check_path->get_result();

    // عرض محتوى النتائج للتحقق من البيانات
    var_dump($path_result->fetch_all());  // يعرض جميع النتائج إذا كانت موجودة

    // إذا لم يتم العثور على path_id في جدول learning_paths
    if ($path_result->num_rows == 0) {
        $_SESSION['message'] = "Invalid Path ID. This path does not exist!";
        header("Location: AvailableCourses.php");
        exit;
    }

    // التحقق إذا كان المستخدم مسجل بالفعل في الدورة
    $check_sql = "SELECT * FROM user_active_courses WHERE user_id = ? AND course_id = ? AND path_id = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("iii", $userId, $courseId, $pathId);
    $stmt->execute();
    $check_result = $stmt->get_result();

    if ($check_result->num_rows > 0) {
        $_SESSION['message'] = "You are already registered in this course.";
    } else {
        // إدخال الدورة في جدول الدورات النشطة
        $sql = "INSERT INTO user_active_courses (user_id, course_id, path_id) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iii", $userId, $courseId, $pathId);

        if ($stmt->execute()) {
            $_SESSION['message'] = "Successfully registered for the course!";
        } else {
            $_SESSION['message'] = "Error: " . $stmt->error;
        }
    }

    // إعادة التوجيه إلى صفحة الدورات المتاحة مع رسالة النجاح أو الفشل
    header("Location: AvailableCourses.php");
    exit;
}
