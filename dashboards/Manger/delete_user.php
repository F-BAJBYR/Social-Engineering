<?php
session_start();

// التحقق من أن المستخدم هو مسؤول
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Unauthorized access.");
}

// التحقق من وجود معرف المستخدم (ID) في الرابط
if (isset($_GET['id'])) {
    $userId = intval($_GET['id']);

    // الاتصال بقاعدة البيانات
    $conn = new mysqli("localhost", "root", "", "social engineering defense simulation");

    // التحقق من نجاح الاتصال
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // حذف المستخدم بناءً على معرفه
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();

    // التحقق من نجاح الحذف وإرجاع رسالة مناسبة
    if ($stmt->affected_rows > 0) {
        echo "User deleted successfully.";
    } else {
        echo "Failed to delete user or user does not exist.";
    }

    // إغلاق الاتصال
    $stmt->close();
    $conn->close();
} else {
    echo "User ID is missing.";
}
?>
