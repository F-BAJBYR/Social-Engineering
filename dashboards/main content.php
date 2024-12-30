<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main Content</title>

    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- Custom CSS -->
    <style>
        /* Body and Animation */
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

        /* Main Content */
        .main-content {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            color: #ffffff;
        }

        h2 {
            font-size: 24px;
            margin-bottom: 15px;
            color: #f3e7ff;
            border-bottom: 2px solid #4895ef;
            padding-bottom: 5px;
            text-align: center;
        }

        .course-section {
            background: rgba(30, 30, 60, 0.8);
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.4);
        }

        .course-item {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 10px;
            background-color: rgba(20, 20, 50, 0.9);
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: transform 0.2s;
        }

        .course-item:hover {
            transform: scale(1.02);
        }

        .course-item span {
            font-weight: bold;
            color: #e0e0ff;
        }

        .course-item button {
            background-color: #f72585;
            color: #ffffff;
            border: none;
            border-radius: 5px;
            padding: 5px 15px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s;
        }

        .course-item button:hover {
            background-color: #ff3b8f;
        }

        .complete-btn {
            background-color: #4895ef;
        }

        .complete-btn:hover {
            background-color: #5aa5ff;
        }
    </style>
</head>

<body>
    <!-- Main Content Area -->
    <div class="main-content" id="main-content">
        <h2>Available Courses</h2>
        <div id="available-courses" class="course-section"></div>

        <h2>Upcoming Courses</h2>
        <div id="upcoming-courses" class="course-section"></div>

        <h2>Active Courses</h2>
        <div id="active-courses" class="course-section"></div>
    </div>

    <script>
        function loadCourses() {
            const courses = {
                available: [
                    { id: 1, name: "Course 1", button: "Enroll" },
                    { id: 2, name: "Course 2", button: "Enroll" }
                ],
                upcoming: [
                    { id: 3, name: "Course 3", button: "Enroll" },
                    { id: 4, name: "Course 4", button: "Enroll" }
                ],
                active: [
                    { id: 5, name: "Course 5", button: "Complete" },
                    { id: 6, name: "Course 6", button: "Complete" }
                ]
            };

            Object.keys(courses).forEach(type => {
                const section = document.getElementById(`${type}-courses`);
                section.innerHTML = courses[type].map(course => `
                    <div class="course-item">
                        <span>${course.name}</span>
                        <button class="${course.button === 'Complete' ? 'complete-btn' : ''}" onclick="${course.button === 'Complete' ? 'completeCourse' : 'enrollCourse'}(${course.id})">${course.button}</button>
                    </div>
                `).join('');
            });
        }

        document.addEventListener("DOMContentLoaded", loadCourses);

        function enrollCourse(courseId) {
            fetch('courses/enroll_course.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ courseId })
                })
                .then(response => response.json())
                .then(data => alert(data.message))
                .catch(error => console.error('Error:', error));
        }

        function completeCourse(courseId) {
            fetch('courses/complete_course.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ courseId })
                })
                .then(response => response.json())
                .then(data => alert(data.message))
                .catch(error => console.error('Error:', error));
        }
    </script>
</body>

</html>
