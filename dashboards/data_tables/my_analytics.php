<?php
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

// Fetch user data
$user_id = 1; // Change this to dynamic user_id in production

// Fetch current and previous month analytics
$current_month = date('n');
$current_year = date('Y');
$previous_month = $current_month - 1;
$previous_year = $current_month == 1 ? $current_year - 1 : $current_year;

$sql_current = "SELECT * FROM monthly_analytics WHERE user_id = $user_id AND month = $current_month AND year = $current_year";
$sql_previous = "SELECT * FROM monthly_analytics WHERE user_id = $user_id AND month = $previous_month AND year = $previous_year";

$result_current = $conn->query($sql_current);
$result_previous = $conn->query($sql_previous);

$current_data = $result_current->fetch_assoc();
$previous_data = $result_previous->fetch_assoc();

// Fetch monthly trends data for chart
$monthly_courses_viewed = [];
$monthly_videos_viewed = [];
$monthly_total_time_watched = [];
for ($month = 1; $month <= 12; $month++) {
    $sql_trend = "SELECT courses_viewed, videos_viewed, total_time_watched FROM monthly_analytics WHERE user_id = $user_id AND month = $month AND year = $current_year";
    $result_trend = $conn->query($sql_trend);
    if ($result_trend->num_rows > 0) {
        $trend_data = $result_trend->fetch_assoc();
        $monthly_courses_viewed[] = $trend_data['courses_viewed'] ?? 0;
        $monthly_videos_viewed[] = $trend_data['videos_viewed'] ?? 0;
        $monthly_total_time_watched[] = $trend_data['total_time_watched'] ?? 0;
    } else {
        $monthly_courses_viewed[] = 0;
        $monthly_videos_viewed[] = 0;
        $monthly_total_time_watched[] = 0;
    }
}

// Fetch course history
$sql_history = "SELECT * FROM user_course WHERE user_id = $user_id ORDER BY registration_date DESC";
$result_history = $conn->query($sql_history);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
          /* تصميم الشريط الجانبي */
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

        h1 {
            text-align: center;
            color: #333;
            margin-top: 20px;
        }
        h5 {
            color: #333;
            margin-top: 20px;
        }
        p {
            text-align: center;
            color: #333;
            margin-top: 20px;
        }
        .analytics-list {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: 20px;
        }

        .analytics-item {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 20px;
            margin-bottom: 20px;
            width: 100%;
            max-width: 800px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>

<div class="analytics-list">
    <h1>My Analytics</h1>

    <!-- Current vs Previous Month Section -->
    <div class="analytics-item">
        <h2>Current Month vs Previous Month</h2>
        <div class="row">
            <div class="col-md-6">
                <h5>Current Month</h5>
                <p><strong>Courses Viewed:</strong> <?= $current_data['courses_viewed'] ?? 0 ?></p>
                <p><strong>Videos Viewed:</strong> <?= $current_data['videos_viewed'] ?? 0 ?></p>
                <p><strong>Total Time Watched:</strong> <?= $current_data['total_time_watched'] ?? 0 ?> hours</p>
            </div>
            <div class="col-md-6">
                <h5>Previous Month</h5>
                <p><strong>Courses Viewed:</strong> <?= $previous_data['courses_viewed'] ?? 0 ?></p>
                <p><strong>Videos Viewed:</strong> <?= $previous_data['videos_viewed'] ?? 0 ?></p>
                <p><strong>Total Time Watched:</strong> <?= $previous_data['total_time_watched'] ?? 0 ?> hours</p>
            </div>
        </div>
    </div>

    <!-- Monthly Trends Section -->
    <div class="analytics-item">
        <h2>Monthly Trends</h2>
        <canvas id="trendsChart" style="width:100%; max-width:600px; height:400px;"></canvas>
    </div>

    <!-- Course History Section -->
    <div class="analytics-item">
        <h2>Course History</h2>
        <?php
        if ($result_history->num_rows > 0) {
            while ($row_history = $result_history->fetch_assoc()) {
                echo "<p>Course ID: " . htmlspecialchars($row_history["course_id"]) . " - Date: " . htmlspecialchars($row_history["registration_date"]) . "</p>";
            }
        } else {
            echo "<p>No courses registered.</p>";
        }
        ?>
    </div>
</div>

<script>
    const ctx = document.getElementById('trendsChart').getContext('2d');
    const trendsChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            datasets: [{
                label: 'Courses Viewed',
                data: [<?= implode(',', $monthly_courses_viewed) ?>],
                borderColor: 'rgba(75, 192, 192, 1)',
                fill: false
            }, {
                label: 'Videos Viewed',
                data: [<?= implode(',', $monthly_videos_viewed) ?>],
                borderColor: 'rgba(153, 102, 255, 1)',
                fill: false
            }, {
                label: 'Total Time Watched (hours)',
                data: [<?= implode(',', $monthly_total_time_watched) ?>],
                borderColor: 'rgba(255, 159, 64, 1)',
                fill: false
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

</body>
</html>

<?php $conn->close(); ?>
