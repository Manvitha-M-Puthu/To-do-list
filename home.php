<?php
include('config.php');
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['task'])) {
    $task = trim($_POST['task']);
    if ($task !== '') {
        $stmt = $conn->prepare("INSERT INTO tasks (user_id, task) VALUES (?, ?)");
        $stmt->bind_param("is", $user_id, $task);
        $stmt->execute();
    }
}

$tasks = [];
$result = $conn->query("SELECT id, task FROM tasks WHERE user_id = $user_id");
while ($row = $result->fetch_assoc()) {
    $tasks[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My To-Do List</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>✨ Your To-Do List</h2>
    
    <form method="post">
        <input type="text" name="task" placeholder="What needs to be done?" required>
        <button type="submit">Add Task</button>
    </form>
    
    <?php if (empty($tasks)): ?>
        <div class="empty-state">
            <p>No tasks yet! Add one above to get started.</p>
        </div>
    <?php else: ?>
        <ul>
            <?php foreach ($tasks as $task): ?>
                <li>
                    <span><?php echo htmlspecialchars($task['task']); ?></span>
                    <a href="delete.php?id=<?php echo $task['id']; ?>" class="delete-btn" 
                       onclick="return confirm('Are you sure you want to delete this task?')">✕</a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    
    <a href="logout.php" class="logout-btn">Logout</a>
</div>

<script>
// Add some interactivity
document.querySelector('form').addEventListener('submit', function(e) {
    const button = this.querySelector('button');
    const input = this.querySelector('input[name="task"]');
    
    if (input.value.trim()) {
        button.classList.add('loading');
        button.disabled = true;
    }
});

// Auto-focus the input field
document.querySelector('input[name="task"]').focus();
</script>
</body>
</html>