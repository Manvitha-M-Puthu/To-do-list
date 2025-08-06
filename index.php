<?php include('config.php'); ?>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($user_id, $hashed);
    if ($stmt->fetch() && password_verify($password, $hashed)) {
        $_SESSION['user_id'] = $user_id;
        header("Location: home.php");
        exit();
    } else {
        $error = "Invalid login!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>Login</h2>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="post">
        <input type="text" name="username" required placeholder="Username"><br>
        <input type="password" name="password" required placeholder="Password"><br>
        <button type="submit">Login</button>
    </form>
    <p>No account? <a href="register.php">Register here</a></p>
</div>
</body>
</html>
