<?php include('config.php'); ?>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    
    if (strlen($username) < 3) {
        $error = "Username must be at least 3 characters long!";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters long!";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $hashed_password);
        
        if ($stmt->execute()) {
            $success = "Account created successfully! You can now log in.";
            // Optionally redirect after a delay
            // header("refresh:2;url=index.php");
        } else {
            $error = "Username already exists! Please choose a different one.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - To-Do App</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>🚀 Create Account</h2>
    
    <?php if (isset($error)): ?>
        <div class="error-message"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <?php if (isset($success)): ?>
        <div class="success-message"><?php echo $success; ?></div>
    <?php endif; ?>
    
    <form method="post">
        <input type="text" name="username" required placeholder="👤 Choose a username" 
               minlength="3" autocomplete="username">
        <input type="password" name="password" required placeholder="🔒 Create a password" 
               minlength="6" autocomplete="new-password">
        <button type="submit">Create Account</button>
    </form>
    
    <p>Already have an account? <a href="index.php">Sign in here</a></p>
</div>

<script>
// Add form validation and loading state
document.querySelector('form').addEventListener('submit', function(e) {
    const button = this.querySelector('button');
    const username = this.querySelector('input[name="username"]').value.trim();
    const password = this.querySelector('input[name="password"]').value;
    
    if (username.length >= 3 && password.length >= 6) {
        button.classList.add('loading');
        button.disabled = true;
    }
});

// Real-time validation feedback
document.querySelector('input[name="username"]').addEventListener('input', function() {
    if (this.value.length > 0 && this.value.length < 3) {
        this.style.borderColor = '#ff6b6b';
    } else {
        this.style.borderColor = '#e1e5e9';
    }
});

document.querySelector('input[name="password"]').addEventListener('input', function() {
    if (this.value.length > 0 && this.value.length < 6) {
        this.style.borderColor = '#ff6b6b';
    } else {
        this.style.borderColor = '#e1e5e9';
    }
});

// Auto-focus the username field
document.querySelector('input[name="username"]').focus();
</script>
</body>
</html>