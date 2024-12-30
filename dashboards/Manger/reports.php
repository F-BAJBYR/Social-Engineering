<?php
session_start();

// التحقق من تسجيل الدخول وأن المستخدم هو "admin"
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: /extra_pages/login.php"); // إعادة التوجيه إلى صفحة تسجيل الدخول إذا لم يكن المسؤول مسجلاً
    exit();
}

// الاتصال بقاعدة البيانات
$conn = new mysqli('localhost', 'root', '', 'social engineering defense simulation');

// التحقق من اتصال قاعدة البيانات
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// معالجة إرسال نموذج إنشاء تقرير جديد
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $report_type = $_POST['report_type'] ?? '';
    $report_details = $_POST['report_details'] ?? '';
    $admin_id = $_SESSION['user_id'];

    // التحقق من أن الحقول ليست فارغة
    if (!empty($report_type) && !empty($report_details)) {
        $stmt = $conn->prepare("INSERT INTO reports (admin_id, report_type, report_details) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $admin_id, $report_type, $report_details);
        $stmt->execute();
        $stmt->close();
        
        $success_message = "تم إنشاء التقرير بنجاح!";
    } else {
        $error_message = "الرجاء تعبئة جميع الحقول المطلوبة!";
    }
}

// جلب جميع التقارير من قاعدة البيانات
$reports = [];
$result = $conn->query("SELECT reports.*, users.username FROM reports JOIN users ON reports.admin_id = users.id ORDER BY reports.created_at DESC");
if ($result->num_rows > 0) {
    $reports = $result->fetch_all(MYSQLI_ASSOC);
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>صفحة التقارير</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { 
            padding: 20px; 
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px; /* تفعيل الاتجاه من اليمين إلى اليسار */
        }
        h2, h4 { 
            margin-bottom: 20px; 
            color: #343a40;
        }
        .report-form, .report-list { 
            margin-bottom: 20px; 
            padding: 20px; 
            border-radius: 8px; 
            background: #ffffff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .alert {
            margin-bottom: 20px;
        }
        .form-control {
            font-size: 16px; /* حجم الخط داخل الحقول */
            line-height: 1.5; /* ارتفاع السطر */
            width: 100%; /* ضمان عرض الحقل بالكامل */
        }
        #report_details {
            min-height: 150px; /* الحد الأدنى لارتفاع حقل التفاصيل */
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .table th {
            background-color: #007bff;
            color: white;
        }
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f2f2f2;
        }
        .table-striped tbody tr:hover {
            background-color: #e9ecef;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>إدارة التقارير</h2>

    <!-- عرض رسالة نجاح أو خطأ -->
    <?php if (isset($success_message)): ?>
        <div class="alert alert-success"><?php echo $success_message; ?></div>
    <?php elseif (isset($error_message)): ?>
        <div class="alert alert-danger"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <!-- نموذج إنشاء تقرير جديد -->
    <div class="report-form">
        <h4>إنشاء تقرير جديد</h4>
        <form method="POST" action="Manger/reports.php">
            <div class="mb-3">
                <label for="report_type" class="form-label">نوع التقرير</label>
                <input type="text" class="form-control text-right" id="report_type" name="report_type" required>
            </div>
            <div class="mb-3">
                <label for="report_details" class="form-label">تفاصيل التقرير</label>
                <textarea class="form-control text-right" id="report_details" name="report_details" rows="4" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">إنشاء التقرير</button>
        </form>
    </div>

    <!-- قائمة التقارير -->
    <div class="report-list">
        <h4>قائمة التقارير</h4>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>رقم التقرير</th>
                    <th>المسؤول</th>
                    <th>نوع التقرير</th>
                    <th>تفاصيل التقرير</th>
                    <th>تاريخ الإنشاء</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($reports)): ?>
                    <?php foreach ($reports as $report): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($report['id']); ?></td>
                            <td><?php echo htmlspecialchars($report['username']); ?></td>
                            <td><?php echo htmlspecialchars($report['report_type']); ?></td>
                            <td><?php echo htmlspecialchars($report['report_details']); ?></td>
                            <td><?php echo htmlspecialchars($report['created_at']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">لا توجد تقارير متاحة</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>

