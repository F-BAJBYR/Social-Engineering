<?php
session_start();

// التحقق من صلاحيات المستخدم
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Unauthorized access.");
}

// التحقق من وجود البيانات اللازمة لتحديث المستخدم
if (isset($_POST['id'], $_POST['username'], $_POST['email'], $_POST['role'])) {
    $userId = intval($_POST['id']);
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    // الاتصال بقاعدة البيانات
    $conn = new mysqli("localhost", "root", "", "social engineering defense simulation");

    // التحقق من نجاح الاتصال
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // تحديث بيانات المستخدم
    $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, role = ? WHERE id = ?");
    $stmt->bind_param("sssi", $username, $email, $role, $userId);
    $stmt->execute();

    // التحقق من نجاح التحديث وإرجاع رسالة مناسبة
    if ($stmt->affected_rows > 0) {
        echo "User updated successfully.";
    } else {
        echo "No changes made or failed to update user.";
    }

    // إغلاق الاتصال
    $stmt->close();
    $conn->close();
} else {
    echo "Invalid data.";
}
?>
