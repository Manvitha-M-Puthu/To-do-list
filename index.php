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
        $error = "Invalid username or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - To-Do App</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>🔐 Welcome Back</h2>
    
    <?php if (isset($error)): ?>
        <div class="error-message"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <form method="post">
        <input type="text" name="username" required placeholder="👤 Username" autocomplete="username">
        <input type="password" name="password" required placeholder="🔒 Password" autocomplete="current-password">
        <button type="submit">Sign In</button>
    </form>
    
    <p>Don't have an account? <a href="register.php">Create one here</a></p>
</div>

<script>
// Add form validation and loading state
document.querySelector('form').addEventListener('submit', function(e) {
    const button = this.querySelector('button');
    const username = this.querySelector('input[name="username"]').value.trim();
    const password = this.querySelector('input[name="password"]').value;
    
    if (username && password) {
        button.classList.add('loading');
        button.disabled = true;
    }
});

// Auto-focus the username field
document.querySelector('input[name="username"]').focus();
</script>
</body>
</html>
