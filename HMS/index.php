<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$database = "hospitaldb";

// Connect to DB
$conn = mysqli_connect($servername, $username, $password, $database);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handle login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $username_input = mysqli_real_escape_string($conn, $_POST['username']);
    $email_input = mysqli_real_escape_string($conn, $_POST['email']);
    $password_input = md5($_POST['password']); // Use password_hash() + password_verify() in production

    // Check login using username or email + password
    $query = "SELECT * FROM users WHERE username='$username_input' AND email='$email_input' AND password='$password_input'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $_SESSION['username'] = $username_input; // store username in session
        header("Location: dashboard.php"); // redirect after login
        exit();
    } else {
        // Show alert on invalid login
        echo "<script>alert('Invalid username, email, or password!'); window.location.href='index.php';</script>";
        exit();
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="CSS/index.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <div class="container">
        <div class="from-box login">
            <form method="post" action="">
                <h1>Login</h1>
                <div class="input-box">
                    <input type="text" name="username" placeholder="Username" required>
                    <i class='bx bxs-user'></i>
                </div>
                <div class="input-box">
                    <input type="email" name="email" placeholder="Email" required>
                    <i class='bx bxs-envelope' ></i>
                </div>
                <div class="input-box">
                    <input type="password" name="password" placeholder="password" required>
                    <i class='bx bxs-lock-alt' ></i>
                </div>
                
                <button type="submit" class="btn" name="login">Login</button>
                
            </form>
        </div>
    </div>
    <script src="JS/index.js"></script>
</body>
</html>