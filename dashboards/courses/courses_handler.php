<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "social engineering defense simulation";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $startDate = $_POST['start_date'];
    $endDate = $_POST['end_date'];
    $userId = $_POST['user_id'];
    $videoPath = null;

    // Handle video upload if file is provided
    if (isset($_FILES['video']) && $_FILES['video']['error'] === UPLOAD_ERR_OK) {
        $videoPath = 'uploads/' . basename($_FILES['video']['name']);
        move_uploaded_file($_FILES['video']['tmp_name'], $videoPath);
    }

    if ($action === 'add') {
        $stmt = $conn->prepare("INSERT INTO courses (course_name, description, start_date, end_date, video_url, user_id) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssi", $name, $description, $startDate, $endDate, $videoPath, $userId);
        $stmt->execute();
        echo "Course added successfully.";

    } elseif ($action === 'edit') {
        $courseId = $_POST['id'];

        // Construct query based on whether a new video is provided
        if ($videoPath) {
            $stmt = $conn->prepare("UPDATE courses SET course_name=?, description=?, start_date=?, end_date=?, video_url=?, user_id=? WHERE id=?");
            $stmt->bind_param("sssssii", $name, $description, $startDate, $endDate, $videoPath, $userId, $courseId);
        } else {
            $stmt = $conn->prepare("UPDATE courses SET course_name=?, description=?, start_date=?, end_date=?, user_id=? WHERE id=?");
            $stmt->bind_param("ssssii", $name, $description, $startDate, $endDate, $userId, $courseId);
        }
        $stmt->execute();
        echo "Course updated successfully.";

    } elseif ($action === 'delete') {
        $courseId = $_POST['id'];
        $stmt = $conn->prepare("DELETE FROM courses WHERE id = ?");
        $stmt->bind_param("i", $courseId);
        $stmt->execute();
        echo "Course deleted successfully.";
    }

    $stmt->close();
}

$conn->close();
?>
