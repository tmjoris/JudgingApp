<?php
include_once '../includes/db.php';

$message = '';
session_start();

if (!isset($_SESSION['judge'])) {
    // Not logged in, redirect to login page
    header('Location: judgelogin.html');
    exit();
}

$judge_username = $_SESSION['judge'];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_username = $_POST['user_username'] ?? '';
    $points = (int)($_POST['points'] ?? 0);

    if ($user_username && $points >= 1 && $points <= 100) {
        // Check if score already exists
        $stmt = $conn->prepare("SELECT id FROM scores WHERE user_name = ? AND judge_name = ?");
        $stmt->bind_param("ss", $user_username, $judge_username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // Update existing score
            $stmt->close();
            $update = $conn->prepare("UPDATE scores SET points = ? WHERE user_name = ? AND judge_name = ?");
            $update->bind_param("iss", $points, $user_username, $judge_username);
            $update->execute();
            $update->close();
            $message = "Score updated.";
        } else {
            // Insert new score
            $stmt->close();
            $insert = $conn->prepare("INSERT INTO scores (user_name, judge_name, points) VALUES (?, ?, ?)");
            $insert->bind_param("ssi", $user_username, $judge_username, $points);
            $insert->execute();
            $insert->close();
            $message = "Score added.";
        }
    } else {
        $message = "Invalid input. Points must be between 1 and 100.";
    }
}

$users = $conn->query("SELECT username FROM users ORDER BY username ASC");

$judge_stmt = $conn->prepare("SELECT display_name FROM judges WHERE username = ?");
$judge_stmt->bind_param("s", $judge_username);
$judge_stmt->execute();
$judge_stmt->bind_result($judge_name);
$judge_stmt->fetch();
$judge_stmt->close();
?>


<!DOCTYPE html>
<html>
<head>
    <title>Judge Portal</title>
    <link rel="stylesheet" href="../css/style.css" />
</head>
<body>
<div class="container">

    <form method="POST" action="logout.php" class="logout-form">
    <button type="submit" class="logout">Logout</button>
    </form>

    <h1>Judge Portal </h1>
    <h2>For Judge <?= htmlspecialchars($judge_name ?? 'Unknown Judge') ?></h2>

    <?php if ($message): ?>
        <p><strong><?= htmlspecialchars($message) ?></strong></p>
    <?php endif; ?>

    <form method="POST">
        <label>Select User:</label><br>
        <select name="user_username" required>
            <option value="">-- Choose User --</option>
            <?php while ($user = $users->fetch_assoc()): ?>
                <option value="<?= htmlspecialchars($user['username']) ?>">
                    <?= htmlspecialchars($user['display_name']) ?>
                </option>
            <?php endwhile; ?>
        </select><br><br>

        <label>Assign Points (1-100):</label><br>
        <input type="number" name="points" min="1" max="100" required /><br><br>

        <button type="submit">Submit Score</button>
    </form>
</div>
</body>
</html>
