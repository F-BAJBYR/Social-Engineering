<?php
session_start();

// Check if user is logged in and is admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: /extra_pages/login.php"); // Redirect if not admin
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data
    $courseName = $_POST['course_name'];
    $startDate = $_POST['start_date'];
    $endDate = $_POST['end_date'];
    $courseDescription = $_POST['course_description'];
    $videoUrl = $_POST['video_url'];
    $status = $_POST['status'];
    $userId = $_SESSION['user_id'];  // Assuming the logged-in user has a user_id

    // Get instructor ID from form (or another method)
    $instructorId = $_POST['instructor_id'];

    // Assuming you have a database connection file
    // الاتصال بقاعدة البيانات
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "social engineering defense simulation";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // التحقق من الاتصال
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Insert the course into the database
    $query = "INSERT INTO courses (course_name, description, start_date, end_date, status, video_url, user_id, instructorcourses) 
              VALUES (:course_name, :description, :start_date, :end_date, :status, :video_url, :user_id, :instructor_id)";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':course_name', $courseName);
    $stmt->bindParam(':description', $courseDescription);
    $stmt->bindParam(':start_date', $startDate);
    $stmt->bindParam(':end_date', $endDate);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':video_url', $videoUrl);
    $stmt->bindParam(':user_id', $userId);
    $stmt->bindParam(':instructor_id', $instructorId);

    if ($stmt->execute()) {
        $message = "Course added successfully!";
    } else {
        $message = "Error adding course.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Course</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h2 class="text-center">Add New Course</h2>

        <?php if (isset($message)) : ?>
            <div class="alert alert-info"><?php echo $message; ?></div>
        <?php endif; ?>

        <form method="POST" action="add_course.php">
            <div class="mb-3">
                <label for="course_name" class="form-label">Course Name</label>
                <input type="text" name="course_name" id="course_name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="start_date" class="form-label">Start Date</label>
                <input type="date" name="start_date" id="start_date" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="end_date" class="form-label">End Date</label>
                <input type="date" name="end_date" id="end_date" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="course_description" class="form-label">Course Description</label>
                <textarea name="course_description" id="course_description" class="form-control" rows="4" required></textarea>
            </div>

            <div class="mb-3">
                <label for="video_url" class="form-label">Video URL</label>
                <input type="url" name="video_url" id="video_url" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">Course Status</label>
                <select name="status" id="status" class="form-control" required>
                    <option value="available">Available</option>
                    <option value="active">Active</option>
                    <option value="completed">Completed</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="instructor_id" class="form-label">Instructor ID</label>
                <input type="number" name="instructor_id" id="instructor_id" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">Add Course</button>
        </form>
    </div>
</body>

</html>