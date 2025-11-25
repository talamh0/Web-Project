<?php
session_start();
include 'config.php';

$error = "";

// Handle login
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = isset($_POST["email"]) ? trim($_POST["email"]) : "";
    $password = isset($_POST["password"]) ? trim($_POST["password"]) : "";
    $role = $_POST['role'] ?? 'user';

    if (empty($email) || empty($password)) {
        $error = "Please fill in all fields.";
    } else {
        if ($role === 'admin') {
            // Check admin table
            $query = "SELECT * FROM admins WHERE email = '$email'";
            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) === 1) {
                $row = mysqli_fetch_assoc($result);
                if (!empty($row["password"]) && password_verify($password, $row["password"])) {
                    $_SESSION['admin_id'] = $row['id'];
                    $_SESSION['admin_name'] = $row['name'];
                    header("Location: admin_home.php");
                    exit();
                } else {
                    $error = "Wrong password.";
                }
            } else {
                $error = "Admin not found.";
            }
        } else {
            // Check users table
            $query = "SELECT * FROM users WHERE email = '$email'";
            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) === 1) {
                $row = mysqli_fetch_assoc($result);
                if (!empty($row["password"]) && password_verify($password, $row["password"])) {
                    $_SESSION['user_id'] = $row['id'];
                    $_SESSION['name']    = $row['name'];
                    header("Location: home.php");
                    exit();
                } else {
                    $error = "Wrong password.";
                }
            } else {
                $error = "User not found.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Event Booking System</title>
    <link rel="stylesheet" href="style1.css">
</head>
<body>

<div class="auth-box">
    <h2>Login</h2>

    <?php
    if (isset($_GET["registered"])) {
        echo "<div class='success'>Account created successfully. You can now login.</div>";
    }

    if (isset($_GET["login_required"])) {
        echo "<div class='error'>Please login to continue.</div>";
    }

    if (!empty($error)) {
        echo "<div class='error'>$error</div>";
    }
    ?>

    <form method="POST" action="">
        <!-- Role Switch -->
        <div class="role-switch">
            <input type="radio" name="role" id="user" value="user" checked>
            <label for="user">User</label>

            <input type="radio" name="role" id="admin" value="admin">
            <label for="admin">Admin</label>

            <div class="switch-slider"></div>
        </div>

        <input type="email" name="email" placeholder="Email Address" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>

    <div class="link">
        Not a member? <a href="register.php">Create an account</a>
    </div>
</div>

</body>
</html>
