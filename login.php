<?php
// 1. Force errors to display right at the engine start
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    $sql = "SELECT * FROM users WHERE email = ? AND password = ? AND status = 'Active'";
    
    // Check if database variable exists from db.php
    if (isset($conn) && $conn) {
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $email, $password);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);
            $_SESSION["user_id"] = $user["user_id"];
            $_SESSION["name"] = $user["name"];
            $_SESSION["role"] = $user["role"];
            
            header("Location: dashboard.php");
            exit();
        } else {
            echo "<script>
                alert('Invalid email or password, or account is inactive.');
                window.location.href='login.php';
            </script>";
            exit();
        }
    } else {
        echo "<p style='color:red;'>Database link ($conn) is missing or broken inside db.php.</p>";
    }
}
?>

<!-- Clean HTML structure designed for the custom CSS classes -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NGO Management Login</title>
    <!-- Links directly to your existing styles folder seen in VS Code -->
    <link rel="stylesheet" href="color.css">
</head>
<body>

    <div class="login-container">
        <div class="login-header">
            <h2>Login For NGO Details</h2>
            <p>Please enter your administrative credentials to continue.</p>
        </div>

        <form action="login.php" method="POST">
            <div class="form-group">
                <label for="username">Username / Email</label>
                <input type="text" id="username" name="username" placeholder="name@organization.org" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="••••••••" required>
            </div>
            
            <button type="submit">Log In Securely</button>
        </form>

        <div class="login-footer">
            &copy; 2026 NGO Management Systems. All rights reserved.
        </div>
    </div>

</body>
</html>