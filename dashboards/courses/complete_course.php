<?php
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $courseId = $input['courseId'];

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "social engineering defense simulation";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        echo json_encode(['message' => 'Connection failed: ' . $conn->connect_error]);
        exit();
    }

    $userId = $_SESSION['user_id']; // Assuming user ID is stored in session

    // تنفيذ استعلام لإكمال الدورة
    $query = "UPDATE courses SET status = 'completed' WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $courseId, $userId);
    if ($stmt->execute()) {
        echo json_encode(['message' => 'Course completed successfully!']);
    } else {
        echo json_encode(['message' => 'Failed to complete course.']);
    }

    $stmt->close();
    $conn->close();
}
?>
