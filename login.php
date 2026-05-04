<?php
session_start(); // Start the session engine

if(isset($_POST['login'])){
    $user = $_POST['user'];
    $pass = $_POST['pass'];

    // This is where you set your password
    if($user == "admin" && $pass == "peace2026"){
        $_SESSION['admin'] = "active"; // Give the VIP pass
        header("Location: admin.php"); // Send to the dashboard
        exit();
    } else {
        $error = "Incorrect Username or Password!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="card">
            <h2>Admin Secure Login</h2>
            <form method="POST">
                <input type="text" name="user" placeholder="Username" required>
                <input type="password" name="pass" placeholder="Password" required>
                <button type="submit" name="login">Login</button>
            </form>
            <?php if(isset($error)) echo "<p style='color:red; margin-top:10px;'>$error</p>"; ?>
        </div>
    </div>
</body>
</html>