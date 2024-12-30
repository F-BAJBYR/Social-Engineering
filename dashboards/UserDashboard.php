<?php
session_start();

// Check if the user is logged in and has the 'user' role
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
    header("Location: /extra_pages/login.php"); // Redirect to login page if not a user
    exit();
}

$username = $_SESSION['username'] ?? 'Guest';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>

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
        display: flex; /* إضافة flexbox */
        justify-content: center; /* توسيط أفقي */
        align-items: center; /* توسيط عمودي */
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

    /* Sidebar */
    .sidebar {
        height: 100vh;
        background-color: #2c3e50;
        color: #ecf0f1;
        padding: 20px;
        position: fixed;
        width: 250px;
        top: 0;
        left: 0;
    }

    .sidebar-header {
        font-size: 1.5rem;
        font-weight: bold;
        text-align: center;
        color: #ecf0f1;
        margin-bottom: 20px;
    }

    .sidebar a {
        color: #bdc3c7;
        text-decoration: none;
        padding: 10px;
        display: block;
        border-radius: 4px;
        margin-bottom: 5px;
        transition: background 0.3s;
    }

    .sidebar a:hover,
    .sidebar a.active {
        background-color: #34495e;
        color: #ecf0f1;
    }

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

    /* Main Content */
    .main-content {
        width: 70%; /* ضبط العرض */
        max-width: 800px;
        padding: 20px;
        background-color: #1f1f38;
        border-radius: 15px;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.4);
        margin-left: 270px; /* ضمان الفصل عن الشريط الجانبي */
    }

    /* Headers */
    h2 {
        font-size: 24px;
        margin-bottom: 15px;
        color: #f3e7ff;
        border-bottom: 2px solid #4895ef;
        padding-bottom: 5px;
        text-align: center;
    }

    /* Tables */
    .course-section {
        width: 100%;
        background: rgba(30, 30, 60, 0.85);
        border-radius: 10px;
        margin-bottom: 20px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        overflow: hidden;
        border-collapse: collapse;
    }

    .course-section th,
    .course-section td {
        padding: 12px;
        text-align: left;
        color: #e0e0ff;
        border-bottom: 1px solid #444;
    }

    .course-section th {
        background-color: #2b2b50;
        font-weight: bold;
        text-transform: uppercase;
        border-top: 1px solid #444;
    }

    .course-section td {
        background-color: rgba(20, 20, 50, 0.8);
        transition: background-color 0.2s;
    }

    .course-section tbody tr:hover td {
        background-color: #343456;
    }

    /* Action Buttons */
    .course-section button {
        background-color: #f72585;
        color: #ffffff;
        border: none;
        border-radius: 5px;
        padding: 5px 12px;
        cursor: pointer;
        font-size: 14px;
        transition: background-color 0.2s;
    }

    .course-section button:hover {
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

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">SEDS</div>

        <!-- Navigation Links -->
        <a href="javascript:void(0);" class="active">
            <i class="fas fa-home icon"></i> Home
        </a>
        <a href="javascript:void(0);" onclick="loadContent('edit-profile.php')">
            <i class="fas fa-user icon"></i> Profile Information
        </a>
        <a href="javascript:void(0);" onclick="loadContent('courses/active_courses.php')">
            <i class="fas fa-book icon"></i> Active Courses
        </a>
        <a href="javascript:void(0);" onclick="loadContent('courses/AvailableCourses.php')">
            <i class="fas fa-graduation-cap icon"></i> Available Courses
        </a>
        <a href="javascript:void(0);" onclick="loadContent('departments/learning_paths.php')">
            <i class="fas fa-map-signs icon"></i> Learning Paths
        </a>
        <a href="javascript:void(0);" onclick="loadContent('data_tables/my_analytics.php')">
            <i class="fas fa-chart-line icon"></i> My Analytics
        </a>
        <a href="javascript:void(0);" onclick="loadContent('Bookmarks/bookmarks.php')">
            <i class="fas fa-bookmark icon"></i> Bookmarks
        </a>
        <a href="javascript:void(0);" onclick="loadContent('Certifications/certifications.php')">
            <i class="fas fa-certificate icon"></i> Certifications
        </a>

        <!-- Logout -->
        <form action="logout.php" method="POST">
            <input type="submit" class="logout-btn" value="Logout">
        </form>
    </div>

    <!-- Main Content Area -->
    <div class="main-content" id="main-content">
        <h2>Available Courses</h2>
        <table id="available-courses" class="course-section">
            <thead>
                <tr>
                    <th>Course Name</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>

        <h2>Upcoming Courses</h2>
        <table id="upcoming-courses" class="course-section">
            <thead>
                <tr>
                    <th>Course Name</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>

        <h2>Active Courses</h2>
        <table id="active-courses" class="course-section">
            <thead>
                <tr>
                    <th>Course Name</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <script>
        function loadCourses() {
            fetch('courses/get_courses.php')
                .then(response => response.json())
                .then(courses => {
                    // Iterate through each course type and display data in table rows
                    ["available", "upcoming", "active"].forEach(type => {
                        const tbody = document.querySelector(`#${type}-courses tbody`);
                        tbody.innerHTML = courses[type].map(course => `
                        <tr>
                            <td>${course.course_name}</td>
                            <td>${course.start_date}</td>
                            <td>${course.end_date}</td>
                            <td>
                                <button class="${course.status === 'active' ? 'complete-btn' : 'enroll-btn'}" 
                                    onclick="${course.status === 'active' ? 'completeCourse' : 'enrollCourse'}(${course.id})">
                                    ${course.status === 'active' ? 'Complete' : 'Enroll'}
                                </button>
                            </td>
                        </tr>
                    `).join('');
                    });
                })
                .catch(error => console.error('Error:', error));
        }

        document.addEventListener("DOMContentLoaded", loadCourses);

        function enrollCourse(courseId) {
            fetch('courses/enroll_course.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        courseId
                    })
                })
                .then(response => response.json())
                .then(data => alert(data.message))
                .catch(error => console.error('Error:', error));
        }

        function completeCourse(courseId) {
            fetch('courses/complete_course.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        courseId
                    })
                })
                .then(response => response.json())
                .then(data => alert(data.message))
                .catch(error => console.error('Error:', error));
        }

        function loadContent(page) {
            fetch(page)
                .then(response => response.text())
                .then(data => document.getElementById("main-content").innerHTML = data)
                .catch(error => console.error('Error loading content:', error));
        }
    </script>
</body>

</html>