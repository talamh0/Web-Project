<?php
session_start();
include("database/config.php"); // الاتصال بقاعدة البيانات لو احتجتيه لاحقاً

// إذا الأدمن حاول يدخل وهو already logged in → ودّيه للـ manageEvents
if (isset($_SESSION['admin'])) {
    header("Location: manageEvents.php");
    exit();
}

// إذا تم إرسال الفورم
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $username = $_POST['username'];
    $password = $_POST['password'];

    // بيانات الأدمن الثابتة حسب وصف المشروع
    $admin_user = "admin";
    $admin_pass = "admin123";

    if ($username === $admin_user && $password === $admin_pass) {
        $_SESSION['admin'] = true;
        header("Location: manageEvents.php");
        exit();
    } else {
        $error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>

    <!-- رابط ملف CSS الخارجي -->
    <link rel="stylesheet" href="css/admin.css">
</head>

<body>

<div class="login-box">
    <h2>Admin Login</h2>

    <?php 
    if (isset($error)) {
        echo "<p class='error'>$error</p>";
    }
    ?>

    <form action="" method="post">
        <input type="text" name="username" placeholder="Admin Username" required>
        <input type="password" name="password" placeholder="Password" required>

        <button type="submit">Login</button>
    </form>
</div>

</body>
</html>
