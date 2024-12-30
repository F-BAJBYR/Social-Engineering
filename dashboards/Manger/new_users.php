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

// Query to get new users
$query = "SELECT id, username, firstname, lastname, email, created_at FROM users WHERE is_new = 1 ORDER BY created_at DESC";
$result = $conn->query($query);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Users</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* General Body Styling */
        body {
            background: #f0f2f5;
            font-family: Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        /* Container styling */
        .container {
            max-width: 800px;
            width: 100%;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            box-sizing: border-box;
        }

        h3 {
            color: #333;
            text-align: center;
            font-weight: bold;
        }

        /* Table Styling */
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .table th,
        .table td {
            padding: 12px;
            text-align: left;
        }

        .table thead th {
            background-color: #333;
            color: #fff;
        }

        .table tbody tr:nth-child(odd) {
            background-color: #f7f7f7;
        }

        .table tbody tr:hover {
            background-color: #e2e6ea;
            transition: background-color 0.3s;
        }

        .table-striped.table-dark th,
        .table-striped.table-dark td {
            border: 1px solid #ccc;
        }

        /* Button Styling */
        button {
            padding: 8px 12px;
            margin: 0 5px;
            border: none;
            border-radius: 4px;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            opacity: 0.9;
        }

        /* Action Button */
        .btn-success {
            background-color: #5cb85c;
            color: #fff;
        }

        .btn-success:hover {
            opacity: 0.9;
        }
    </style>
</head>

<body>

    <div class="container">
        <?php
        if ($result->num_rows > 0) {
            echo "<h3>New Users</h3>";
            echo "<table class='table table-striped'>";
            echo "<thead><tr><th>Username</th><th>First Name</th><th>Last Name</th><th>Email</th><th>Created At</th><th>Actions</th></tr></thead>";
            echo "<tbody>";

            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                echo "<td>" . htmlspecialchars($row['firstname']) . "</td>";
                echo "<td>" . htmlspecialchars($row['lastname']) . "</td>";
                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
                echo "<td><form method='POST' action='Manger/mark_as_seen.php'>
                          <input type='hidden' name='user_id' value='" . $row['id'] . "'>
                          <button type='submit' class='btn btn-success'>Mark as Seen</button>
                        </form></td>";
                echo "</tr>";
            }

            echo "</tbody></table>";
        } else {
            echo "<p>No new users found.</p>";
        }

        $conn->close();
        ?>
    </div>
</body>

</html>
