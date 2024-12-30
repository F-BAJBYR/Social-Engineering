<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if there is a success message passed from the previous page
$success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : "Your action was successful!";
unset($_SESSION['success_message']); // Clear the success message after displaying
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Success</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom CSS for styling -->
    <style>
        .success-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .success-message {
            font-size: 1.2em;
            color: #28a745;
        }
        .btn-back {
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container success-container">
        <h2 class="success-message"><?php echo htmlspecialchars($success_message); ?></h2>
        
        <a href="edit-profile.php" class="btn btn-primary btn-back">Back to Profile</a>
        <a href="dashboard.php" class="btn btn-secondary btn-back">Go to Dashboard</a>
    </div>
</body>

</html>
