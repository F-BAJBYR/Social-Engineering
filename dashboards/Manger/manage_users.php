<?php
session_start();

// Check if user is logged in and is admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: /extra_pages/login.php"); // Redirect if not admin
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "social engineering defense simulation";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to get users
$query = "SELECT id, username, email, role FROM users"; // Assuming 'role' exists in your 'users' table
$result = $conn->query($query);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manage Users</title>
</head>
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

    h2 {
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

    /* Edit Button */
    button[onclick^="editUser"] {
        background-color: #5cb85c;
        color: #fff;
    }

    /* Delete Button */
    button[onclick^="deleteUser"] {
        background-color: #d9534f;
        color: #fff;
    }

    /* Modal styling */
    .modal {
        display: none;
        position: fixed;
        z-index: 1;
        padding-top: 100px;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
    }

    .modal-content {
        background-color: #fff;
        margin: auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
        max-width: 500px;
        border-radius: 8px;
    }

    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }
</style>
<body>
    <div class="container">
        <h2>Manage Users</h2>
        <table class="table table-striped table-dark">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($user = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($user['id']) . "</td>";
                        echo "<td>" . htmlspecialchars($user['username']) . "</td>";
                        echo "<td>" . htmlspecialchars($user['email']) . "</td>";
                        echo "<td>" . htmlspecialchars($user['role']) . "</td>";
                        echo "<td>";
                        echo "<button onclick='editUser(" . $user['id'] . ")'>Edit</button>";
                        echo "<button onclick='deleteUser(" . $user['id'] . ")'>Delete</button>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No users found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Edit User</h2>
            <input type="hidden" id="userId">
            <label for="username">Username:</label>
            <input type="text" id="username" required>
            <br>
            <label for="email">Email:</label>
            <input type="email" id="email" required>
            <br>
            <label for="role">Role:</label>
            <input type="text" id="role" required>
            <br><br>
            <button onclick="saveUser()">Save Changes</button>
        </div>
    </div>

    <script>
    function editUser(userId) {
        fetch('Manger/get_user.php?id=' + userId)
            .then(response => response.json())
            .then(data => {
                document.getElementById('userId').value = data.id;
                document.getElementById('username').value = data.username;
                document.getElementById('email').value = data.email;
                document.getElementById('role').value = data.role;
                document.getElementById('editModal').style.display = 'block';
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to load user data.');
            });
    }

    function saveUser() {
        const userId = document.getElementById('userId').value;
        const username = document.getElementById('username').value;
        const email = document.getElementById('email').value;
        const role = document.getElementById('role').value;

        fetch('Manger/edit_user.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `id=${userId}&username=${username}&email=${email}&role=${role}`
        })
        .then(response => response.text())
        .then(data => {
            alert(data);
            closeModal();
            location.reload();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to update user.');
        });
    }

    function deleteUser(userId) {
        if (confirm('Are you sure you want to delete this user?')) {
            fetch('Manger/delete_user.php?id=' + userId)
                .then(response => response.text())
                .then(data => {
                    alert(data);
                    location.reload();
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to delete user.');
                });
        }
    }

    function closeModal() {
        document.getElementById('editModal').style.display = 'none';
    }
    </script>

</body>

</html>

<?php
$conn->close();
?>
