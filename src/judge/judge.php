<?php
include_once '../includes/db.php';

$message = '';

// For demo, let's pick a judge ID manually here.
// In real app, you'd have login to get this dynamically.
$judge_id = 1; // change this to test with different judges

// Handle score submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = (int)$_POST['user_id'];
    $points = (int)$_POST['points'];

    if ($user_id > 0 && $points >= 1 && $points <= 100) {
        // Check if score already exists for this user and judge (update if exists)
        $stmt = $conn->prepare("SELECT id FROM scores WHERE user_id = ? AND judge_id = ?");
        $stmt->bind_param("ii", $user_id, $judge_id);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // Update existing score
            $stmt->close();
            $update = $conn->prepare("UPDATE scores SET points = ? WHERE user_id = ? AND judge_id = ?");
            $update->bind_param("iii", $points, $user_id, $judge_id);
            $update->execute();
            $update->close();
            $message = "Score updated successfully.";
        } else {
            // Insert new score
            $stmt->close();
            $insert = $conn->prepare("INSERT INTO scores (user_id, judge_id, points) VALUES (?, ?, ?)");
            $insert->bind_param("iii", $user_id, $judge_id, $points);
            $insert->execute();
            $insert->close();
            $message = "Score added successfully.";
        }
    } else {
        $message = "Invalid input. Points must be between 1 and 100.";
    }
}

// Fetch all users
$users = $conn->query("SELECT * FROM users ORDER BY name ASC");

// Fetch judge display name
$judge_result = $conn->prepare("SELECT display_name FROM judges WHERE id = ?");
$judge_result->bind_param("i", $judge_id);
$judge_result->execute();
$judge_result->bind_result($judge_name);
$judge_result->fetch();
$judge_result->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Judge Portal</title>
    <link rel="stylesheet" href="../css/style.css" />
</head>
<body>
<div class="container">
    <h2>Judge Portal - <?= htmlspecialchars($judge_name ?: 'Unknown Judge') ?></h2>

    <?php if ($message): ?>
        <p><strong><?=htmlspecialchars($message)?></strong></p>
    <?php endif; ?>

    <form method="POST">
        <label>Select User:</label><br>
        <select name="user_id" required>
            <option value="">-- Choose User --</option>
            <?php while ($user = $users->fetch_assoc()): ?>
                <option value="<?= $user['id'] ?>">
                    <?= htmlspecialchars($user['name']) ?>
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
