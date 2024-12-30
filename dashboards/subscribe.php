<?php
// Start session if needed
session_start();

// Define MySQL database connection details
$host = 'localhost';  // Hostname of the MySQL server (usually localhost)
$user = 'root';       // MySQL username (for local environments, it's often root)
$pass = '';           // MySQL password (leave empty if no password for local setup)
$dbname = 'social engineering defense simulation'; // Database name

// Create a connection using mysqli
$conn = new mysqli($host, $user, $pass, $dbname);

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize message variables
$message = '';
$messageClass = '';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the email from the form
    $email = $_POST['email'];

    // Simple email validation
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Prepare and bind the SQL statement to prevent SQL injection
        $stmt = $conn->prepare("INSERT INTO subscribers (email) VALUES (?)");
        $stmt->bind_param('s', $email); // 's' indicates the email is a string

        // Execute the query
        if ($stmt->execute()) {
            $message = "Thanks for subscribing!";
            $messageClass = 'success';  // Add success class for styling
        } else {
            $message = "Something went wrong. Please try again.";
            $messageClass = 'error';  // Add error class for styling
        }

        // Close the statement
        $stmt->close();
    } else {
        $message = "Invalid email format.";
        $messageClass = 'error';  // Add error class for styling
    }
}

// Close the database connection
$conn->close();
?>
