<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "social engineering defense simulation";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize message variables
$success_message = "";
$error_message = "";

// Fetch user information from the database
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get data from the form
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $profile_photo = $_FILES['profile_photo'];

    // Check if the email is already in use by another user
    $email_check_sql = "SELECT id FROM users WHERE email = ? AND id != ?";
    $email_check_stmt = $conn->prepare($email_check_sql);
    $email_check_stmt->bind_param("si", $email, $user_id);
    $email_check_stmt->execute();
    $email_check_result = $email_check_stmt->get_result();

    if ($email_check_result->num_rows > 0) {
        $error_message = "This email is already taken by another user.";
    } else {
        // If email is unique, proceed with updating basic info
        $sql = "UPDATE users SET firstname = ?, lastname = ?, email = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $firstname, $lastname, $email, $user_id);

        if ($stmt->execute()) {
            $success_message = "Profile updated successfully.";
        } else {
            $error_message = "Error updating profile: " . $stmt->error;
        }
    }

    // Check if a new profile photo is uploaded
    if (!empty($profile_photo['name'])) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($profile_photo['name']);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Allow only certain file formats
        $allowed_formats = ["jpg", "jpeg", "png", "gif"];
        if (in_array($imageFileType, $allowed_formats)) {
            if (move_uploaded_file($profile_photo['tmp_name'], $target_file)) {
                // Update profile photo in database
                $sql = "UPDATE users SET profile_photo = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("si", $target_file, $user_id);
                if ($stmt->execute()) {
                    $success_message .= " Profile photo updated successfully.";
                } else {
                    $error_message .= " Error updating profile photo: " . $stmt->error;
                }
            } else {
                $error_message .= " Sorry, there was an error uploading your profile photo.";
            }
        } else {
            $error_message .= " Only JPG, JPEG, PNG & GIF files are allowed.";
        }
    }

    // Check if password fields are filled
    if (!empty($_POST['new_password']) && !empty($_POST['confirm_password'])) {
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        // Validate password match
        if ($new_password === $confirm_password) {
            // Hash the new password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            // Update password in the database
            $sql = "UPDATE users SET password = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $hashed_password, $user_id);

            if ($stmt->execute()) {
                $success_message .= " Password updated successfully.";
            } else {
                $error_message .= " Error updating password: " . $stmt->error;
            }
        } else {
            $error_message .= " New password and confirmation do not match.";
        }
    }

    // Redirect to dashboard after update
    if ($success_message) {
        header("Location: UserDashboard.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Profile</title>
    <style>
        /* Background Gradient Animation */
        body {
            background: linear-gradient(-45deg, #3a0ca3, #3f37c9, #4361ee, #4895ef, #b5179e);
            background-size: 300% 300%;
            animation: gradientAnimation 15s ease infinite;
            color: #ffffff;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
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

        .container {
            background-color: #1c1c1c;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.4);
            width: 50%;
            max-width: 600px;
            margin: 0 auto;
        }

        h2 {
            text-align: center;
            color: #ffffff;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        label {
            font-weight: bold;
            color: #bbb;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="file"],
        button {
            padding: 12px;
            font-size: 16px;
            border: 1px solid #444;
            border-radius: 6px;
            outline: none;
            width: 100%;
            box-sizing: border-box;
            background-color: #333;
            color: #fff;
        }

        input[type="file"] {
            padding: 8px;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: #4CAF50;
        }

        button {
            background-color: #3f51b5;
            color: white;
            font-weight: bold;
            cursor: pointer;
            border: none;
        }

        button:hover {
            background-color: #303f9f;
        }

        .message {
            font-size: 16px;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }

        .success {
            background-color: #388e3c;
            color: #ffffff;
        }

        .error {
            background-color: #d32f2f;
            color: #ffffff;
        }

        .section-title {
            margin-top: 30px;
            font-size: 18px;
            color: #bbb;
            font-weight: bold;
        }

        .profile-photo-container {
            text-align: center;
            margin-bottom: 20px;
            position: relative;
        }

        .profile-photo-container img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            border: 5px solid #3f51b5;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.4);
            transition: transform 0.3s ease, border-color 0.3s ease;
        }

        .profile-photo-container:hover img {
            transform: scale(1.1);
            border-color: #303f9f;
        }

        .profile-photo-container:hover::after {
            content: "→";
            position: absolute;
            bottom: -20px;
            right: 50%;
            transform: translateX(50%);
            font-size: 24px;
            color: #3f51b5;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Edit Profile</h2>

        <!-- Display Success or Error Message -->
        <?php if (!empty($success_message)) : ?>
            <div class="message success"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <?php if (!empty($error_message)) : ?>
            <div class="message error"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form action="edit-profile.php" method="post" enctype="multipart/form-data">
            <div class="profile-photo-container">
                <?php if (!empty($user['profile_photo'])) : ?>
                    <img src="<?php echo $user['profile_photo']; ?>" alt="Profile Photo">
                <?php else : ?>
                    <img src="default-profile.png" alt="Default Profile Photo">
                <?php endif; ?>
            </div>

            <!-- Basic Information -->
            <label for="firstname">First Name:</label>
            <input type="text" name="firstname" value="<?php echo htmlspecialchars($user['firstname']); ?>" required>

            <label for="lastname">Last Name:</label>
            <input type="text" name="lastname" value="<?php echo htmlspecialchars($user['lastname']); ?>" required>

            <label for="email">Email:</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

            <!-- Profile Photo Upload -->
            <label for="profile_photo">Profile Photo:</label>
            <input type="file" name="profile_photo">

            <!-- Password Change Section -->
            <div class="section-title">Change Password</div>
            <label for="new_password">New Password:</label>
            <input type="password" name="new_password">

            <label for="confirm_password">Confirm New Password:</label>
            <input type="password" name="confirm_password">

            <button type="submit">Update Profile</button>
        </form>
    </div>
</body>

</html>
