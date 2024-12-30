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

// التأكد من أن المستخدم هو إدمن
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: /extra_pages/login.php");
    exit();
}

// جلب الدورات من قاعدة البيانات
$courses = [];
$result = $conn->query("SELECT id, course_name, description, start_date, end_date, video_url, user_id, status FROM courses");
while ($row = $result->fetch_assoc()) {
    $courses[] = $row;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manage Courses</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #1c2331;
            margin: 0;
            padding: 0;
            color: #e0e0e0;
        }

        .container {
            background-color: #2a3b52;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            margin: 30px auto;
            width: 95%;
            /* تناسب العرض مع حجم الشاشة */
            max-width: 1200px;
            /* الحد الأقصى للعرض */
        }

        h2 {
            font-size: 24px;
            color: #ffffff;
            margin-bottom: 15px;
            text-align: center;
            text-transform: uppercase;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            color: #e0e0e0;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #444;
        }

        th {
            background-color: #3e5064;
            color: #ffffff;
            text-transform: uppercase;
        }

        tr {
            background-color: #2f3d4f;
        }

        tr:nth-child(even) {
            background-color: #3a485a;
        }

        tr:hover {
            background-color: #4a5b6c;
        }

        button {
            padding: 8px 12px;
            margin: 3px;
            border: none;
            border-radius: 4px;
            background-color: #007bff;
            color: #ffffff;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #0056b3;
        }

        button.edit-btn {
            background-color: #4caf50;
        }

        button.edit-btn:hover {
            background-color: #388e3c;
        }

        button.delete-btn {
            background-color: #f44336;
        }

        button.delete-btn:hover {
            background-color: #d32f2f;
        }


        /* نمط النافذة المنبثقة */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            padding-top: 100px;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
        }

        .modal-content {
            background-color: #2a3b52;
            /* لون خلفية النافذة */
            margin: auto;
            padding: 20px;
            border: 1px solid #444;
            width: 80%;
            max-width: 500px;
            border-radius: 8px;
            color: #e0e0e0;
            /* لون النص */
        }

        .close {
            color: #bbb;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: #fff;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Manage Courses</h2>
        <button onclick="openModal('add')">Add New Course</button>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Course Name</th>
                    <th>Description</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Upload Video</th>
                    <th>Status</th>
                    <th>Actions</th>

                </tr>
            </thead>
            <tbody id="courseTable">
                <?php foreach ($courses as $courses): ?>
                    <tr data-id="<?php echo $courses['id']; ?>">
                        <td><?php echo htmlspecialchars($courses['id']); ?></td>
                        <td><?php echo htmlspecialchars($courses['course_name']); ?></td>
                        <td><?php echo htmlspecialchars($courses['description']); ?></td>
                        <td><?php echo htmlspecialchars($courses['start_date']); ?></td>
                        <td><?php echo htmlspecialchars($courses['end_date']); ?></td>
                        <td><?php echo htmlspecialchars($courses['video_url']); ?></td>
                        <td><?php echo htmlspecialchars($courses['status']); ?></td>


                        <td>
                            <button class="edit-btn" onclick="openModal('edit', <?php echo $course['id']; ?>)">Edit</button>
                            <button class="delete-btn" onclick="deleteCourse(<?php echo $course['id']; ?>)">Delete</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div id="courseModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2 id="modalTitle">Add Course</h2>

            <input type="hidden" id="courseId">

            <label for="courseName">Course Name:</label>
            <input type="text" id="courseName" required>

            <label for="courseDescription">Description:</label>
            <textarea id="courseDescription" required></textarea>

            <label for="courseStartDate">Start Date:</label>
            <input type="date" id="courseStartDate" required>

            <label for="courseEndDate">End Date:</label>
            <input type="date" id="courseEndDate" required>

            <label for="courseVideo">Upload Video:</label>
            <input type="file" id="courseVideo" accept="video/*">

            <button onclick="saveCourse()">Save Changes</button>
        </div>
    </div>

    <script>
        function openModal(action, courseId = null) {
            const modal = document.getElementById('courseModal');
            modal.style.display = 'block';

            if (action === 'add') {
                document.getElementById('modalTitle').textContent = 'Add New Course';
                document.getElementById('courseName').value = '';
                document.getElementById('courseDescription').value = '';
                document.getElementById('courseStartDate').value = '';
                document.getElementById('courseEndDate').value = '';
                document.getElementById('courseVideo').value = '';
            } else if (action === 'edit') {
                document.getElementById('modalTitle').textContent = 'Edit Course';
                loadCourseData(courseId); // احصل على بيانات الدورة للتعديل
            }
        }

        // دالة إغلاق الـ Modal
        function closeModal() {
            const modal = document.getElementById('courseModal');
            modal.style.display = 'none';
        }

        // إذا نقر المستخدم في أي مكان خارج النافذة، يتم إغلاقها
        window.onclick = function(event) {
            const modal = document.getElementById('courseModal');
            if (event.target == modal) {
                closeModal();
            }
        }


        function saveCourse() {
            const courseId = document.getElementById('courseId').value;
            const name = document.getElementById('courseName').value;
            const description = document.getElementById('courseDescription').value;
            const startDate = document.getElementById('courseStartDate').value;
            const endDate = document.getElementById('courseEndDate').value;
            const videoFile = document.getElementById('courseVideo').files[0];

            if (!name || !description || !startDate || !endDate) {
                alert("Please fill in all required fields.");
                return;
            }

            const formData = new FormData();
            formData.append('action', courseId ? 'edit' : 'add');
            formData.append('id', courseId);
            formData.append('name', name);
            formData.append('description', description);
            formData.append('start_date', startDate);
            formData.append('end_date', endDate);

            if (videoFile) {
                formData.append('video', videoFile); // Append the video file
            } else {
                alert("Please upload a video file.");
                return;
            }

            fetch('courses/courses_handler.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    console.log("Response from server:", data);
                    alert(data);
                    location.reload();
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert("An error occurred. Please try again.");
                });
        }




        function deleteCourse(courseId) {
            if (confirm('Are you sure you want to delete this course?')) {
                fetch('courses/courses_handler.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: `action=delete&id=${courseId}`
                    })
                    .then(response => response.text())
                    .then(data => {
                        alert(data);
                        location.reload();
                    })
                    .catch(error => console.error('Error:', error));
            }
        }
    </script>
</body>

</html>