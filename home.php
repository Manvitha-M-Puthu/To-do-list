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
<html>
<head>
    <title>To-Do List</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>Welcome! Your To-Do List</h2>
    <form method="post">
        <input type="text" name="task" placeholder="New Task" required>
        <button type="submit">Add</button>
    </form>
    <ul>
        <?php foreach ($tasks as $task): ?>
            <li>
                <?php echo htmlspecialchars($task['task']); ?>
                <a href="delete.php?id=<?php echo $task['id']; ?>">❌</a>
            </li>
        <?php endforeach; ?>
    </ul>
    <a href="logout.php">Logout</a>
</div>
</body>
</html>
