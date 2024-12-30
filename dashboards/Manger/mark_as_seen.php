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

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];

    $stmt = $conn->prepare("UPDATE users SET is_new = 0 WHERE id = ?");
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        echo "User marked as seen.";
    } else {
        echo "Error updating user.";
    }

    $stmt->close();
}

$conn->close();

// Redirect back to the new users page
header("Location: new_users.php");
exit();
?>
