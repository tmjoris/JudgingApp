<?php
include_once '../includes/db.php';

session_start();

if (!isset($_SESSION['admin'])) {
    // Not logged in, redirect to login page
    header('Location: adminlogin.html');
    exit();
}

$message = '';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $display_name = trim($_POST['display_name']);
    $password = trim($_POST['password']);
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    if($username && $display_name){
        $stmt = $conn->prepare("INSERT INTO judges (username, display_name, hashedPassword) VALUES(?, ?, ?)");
        $stmt->bind_param('sss', $username, $display_name, $hashedPassword);
        if($stmt->execute()) {
            $message = "Judge added successfully.";
        } else {
            $message = "Error: " . $conn->error;
        }

        $stmt->close();
    } else {
        $message = "Please fill in all fields.";
    }
}

$result = $conn->query("SELECT * FROM judges ORDER BY username DESC");
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Admin Dashboard</title>
        <link rel="stylesheet" href="../css/style.css"/>
    </head>

    <body>
    <header>
        <nav class="navbar">
        <ul class="nav-links">
            <li><a href="../judge/judgelogin.html">Judge</a></li>
            <li><a href="../public/scoreboard.html">Scoreboard</a></li>
            <li>
                <form method="POST" action="logout.php" class="logout-form">
                    <button type="submit" class="logout">Logout</button>
                </form>
            </li>
        </ul>
        </nav>
    </header>
    
    <div class="container">
        <h1>Admin Dashboard</h1>

        <h2>Add Judges</h2>

        <?php if ($message): ?>
            <p><strong><?=htmlspecialchars($message)?></strong></p>
        <?php endif; ?>

        <form method="POST">
            <label>Judge Username:</label><br>
            <input type="text" name="username" required /><br><br>

            <label>Judge Display Name:</label><br>
            <input type="text" name="display_name" required /><br><br>

            <label>Judge Login Password</label><br>
            <input type="password" name="password" required /><br><br>

            <button type="submit">Add Judge</button>
        </form>

        <h3>Existing Judges</h3>
        <table>
            <thead>
            <tr><th>Username</th><th>Display Name</th></tr>
            </thead>
            <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['username']) ?></td>
                    <td><?= htmlspecialchars($row['display_name']) ?></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
</div>
</body>
</html>