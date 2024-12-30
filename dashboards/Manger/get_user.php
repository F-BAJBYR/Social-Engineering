<?php
session_start();

// التحقق من أن المستخدم هو مسؤول
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Unauthorized access.");
}

// التحقق من أن معرف المستخدم (ID) تم تمريره عبر GET
if (isset($_GET['id'])) {
    $userId = intval($_GET['id']);  // تأمين الإدخال وتحويله إلى رقم صحيح

    // الاتصال بقاعدة البيانات
    $conn = new mysqli("localhost", "root", "", "social engineering defense simulation");

    // التحقق من نجاح الاتصال
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // جلب بيانات المستخدم بناءً على ID
    $stmt = $conn->prepare("SELECT id, username, email, role FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    // التحقق من وجود المستخدم
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        echo json_encode($user);  // إرسال البيانات كاستجابة JSON
    } else {
        echo json_encode(["error" => "User not found."]);
    }

    // إغلاق الاتصال
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["error" => "User ID is missing."]);
}
?>
