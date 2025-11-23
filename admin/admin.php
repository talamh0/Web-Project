<?php
session_start();

// لو الأدمن أصلاً مسجل دخول، يرجع للصفحة الرئيسية
if(isset($_SESSION['admin'])){
    header("Location: manageEvents.php");
    exit();
}

$error = "";

if(isset($_POST['login'])){

    $username = $_POST['username'];
    $password = $_POST['password'];

    // بيانات الأدمن 
    if($username === "admin" && $password === "admin123"){
        $_SESSION['admin'] = true;
        header("Location: manageEvents.php");
        exit();
    } else {
        $error = "Invalid username or password.";
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <link rel="stylesheet" href="../css/style.css">  <!-- نفس ملف CSS الموحد -->
</head>

<body>

<div class="container" style="max-width: 400px; margin-top: 80px;">
    
    <h2 style="text-align:center; margin-bottom:20px;">Admin Login</h2>

    <?php if($error != ""): ?>
        <p style="color:red; text-align:center;"><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="POST" class="admin-login-form">

        <label>Username:</label>
        <input type="text" name="username" required>

        <label>Password:</label>
        <input type="password" name="password" required>

        <button type="submit" name="login" class="btn btn-primary" style="width:100%;">Login</button>

    </form>
</div>

</body>
</html>
