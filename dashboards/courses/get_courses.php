<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "social engineering defense simulation";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$userId = $_SESSION['user_id']; // Assuming user ID is stored in session

// جلب الدورات المتاحة
$availableCourses = [];
$upcomingCourses = [];
$activeCourses = [];

// تنفيذ استعلام لجلب الدورات بناءً على الحالة
$query = "SELECT id, course_name, start_date, end_date, status FROM courses WHERE user_id = $userId";
$result = $conn->query($query);
while ($row = $result->fetch_assoc()) {
    switch ($row['status']) {
        case 'available':
            $availableCourses[] = $row;
            break;
        case 'upcoming':
            $upcomingCourses[] = $row;
            break;
        case 'active':
            $activeCourses[] = $row;
            break;
    }
}

$conn->close();

// إرجاع البيانات في تنسيق JSON
echo json_encode([
    'available' => $availableCourses,
    'upcoming' => $upcomingCourses,
    'active' => $activeCourses,
]);
?>
